<?php
namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WompiController extends Controller
{
    // El usuario envía el formulario → creamos reserva pendiente → redirigimos a Wompi
    public function iniciarPago(Request $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);

        $request->validate([
            'fecha_entrada' => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'  => ['required', 'date', 'after:fecha_entrada'],
            'num_personas'  => ['required', 'integer', 'min:1', 'max:' . $hotel->capacidad],
        ]);

        $dias         = now()->parse($request->fecha_entrada)->diffInDays($request->fecha_salida);
        $total        = (int) round($dias * $hotel->precio);
        $referencia   = 'FZ-' . now()->format('ymd') . '-' . strtoupper(\Str::random(6));

        // Guardamos la reserva en "pendiente" ANTES de ir a Wompi
        Reserva::create([
            'usuario_id'      => Auth::id(),
            'hotel_id'        => $hotel->id,
            'fecha_entrada'   => $request->fecha_entrada,
            'fecha_salida'    => $request->fecha_salida,
            'num_personas'    => $request->num_personas,
            'precio_total'    => $total,
            'estado'          => 'pendiente',
            'metodo_pago'     => 'wompi',
            'referencia_pago' => $referencia,
            'estado_pago'     => 'pendiente',
        ]);

        // Construimos la firma de integridad (obligatoria desde 2024)
        $amountCents  = $total * 100;
        $moneda       = 'COP';
        $cadena       = $referencia . $amountCents . $moneda . config('wompi.integrity_key');
        $signature    = hash('sha256', $cadena);


 $widgetUrl = 'https://checkout.wompi.co/p/?'
        . 'public-key='           . urlencode(config('wompi.public_key'))
        . '&currency='            . $moneda
        . '&amount-in-cents='     . $amountCents
        . '&reference='           . urlencode($referencia)
        . '&redirect-url='        . urlencode('https://httpbin.org/get')
        . '&signature:integrity=' . $signature
        . '&customer-data:email=' . urlencode(Auth::user()->email)
        . '&customer-data:full-name=' . urlencode(Auth::user()->name);

 

    return redirect()->away($widgetUrl);
        }

    // Wompi nos devuelve aquí con ?id=<transaction_id>
    public function retorno(Request $request)
    {
        $transactionId = $request->query('id');

        if (!$transactionId) {
            return redirect()->route('mis-reservas')->with('error', 'No se recibió respuesta de Wompi.');
        }

        $transaction = $this->consultarTransaccion($transactionId);

        if (!$transaction) {
            return redirect()->route('mis-reservas')->with('error', 'No pudimos verificar tu pago. Contacta soporte.');
        }

        $referencia = $transaction['reference'] ?? null;
        $estado     = $transaction['status']    ?? 'ERROR';
        $reserva    = Reserva::where('referencia_pago', $referencia)->first();

        if (!$reserva) {
            return redirect()->route('mis-reservas')->with('error', 'Reserva no encontrada. Ref: ' . $referencia);
        }

        $this->actualizarReserva($reserva, $estado, $transactionId);

        return view('pages.wompi_resultado', [
            'reserva'     => $reserva->fresh(),
            'transaction' => $transaction,
            'estado'      => $estado,
        ]);
    }

    // Wompi llama aquí de forma asíncrona para pagos PSE que tardan
    public function webhook(Request $request)
    {
        $signature  = $request->header('X-Event-Checksum');
        $timestamp  = $request->header('X-Timestamp') ?? '';
        $body       = $request->getContent();
        $expected   = hash('sha256', $timestamp . $body . config('wompi.eventos_key'));

        if ($signature !== $expected) {
            return response()->json(['error' => 'Firma inválida'], 401);
        }

        $event = $request->json('event', '');
        $tx    = $request->json('data.transaction', []);

        if ($event === 'transaction.updated' && !empty($tx)) {
            $reserva = Reserva::where('referencia_pago', $tx['reference'] ?? '')->first();
            if ($reserva) {
                $this->actualizarReserva($reserva, $tx['status'] ?? 'ERROR', $tx['id'] ?? null);
            }
        }

        return response()->json(['received' => true]);
    }

    private function consultarTransaccion(string $id): ?array
    {
        try {
            $response = Http::withToken(config('wompi.private_key'))
                ->get(config('wompi.api_url') . "/transactions/{$id}");
            return $response->successful() ? $response->json('data') : null;
        } catch (\Exception $e) {
            Log::error('Wompi error: ' . $e->getMessage());
            return null;
        }
    }

    private function actualizarReserva(Reserva $reserva, string $estadoWompi, ?string $txId): void
    {
        $map = [
            'APPROVED' => ['confirmada', 'pagado'],
            'DECLINED' => ['pendiente',  'fallido'],
            'VOIDED'   => ['cancelada',  'fallido'],
            'ERROR'    => ['pendiente',  'fallido'],
        ];
        [$estadoReserva, $estadoPago] = $map[$estadoWompi] ?? ['pendiente', 'pendiente'];

        $reserva->update([
            'estado'               => $estadoReserva,
            'estado_pago'          => $estadoPago,
            'wompi_transaction_id' => $txId,
        ]);
    }
}