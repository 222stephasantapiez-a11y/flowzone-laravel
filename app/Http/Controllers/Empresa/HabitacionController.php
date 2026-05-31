<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Hotel;
use App\Models\Habitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HabitacionController extends Controller
{
    private function empresa(): Empresa
    {
        return Empresa::where('usuario_id', Auth::id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $empresa = $this->empresa();
        $hoteles = $empresa->hoteles()->orderBy('nombre')->get();

        // Si la empresa es tipo hotel y no tiene hotel registrado, crearlo automáticamente
        if ($hoteles->isEmpty() && $empresa->tipo_empresa === 'hotel') {
            $hotel = Hotel::create([
                'empresa_id'     => $empresa->id,
                'nombre'         => $empresa->nombre,
                'descripcion'    => $empresa->descripcion,
                'precio'         => 0,
                'ubicacion'      => $empresa->direccion,
                'telefono'       => $empresa->telefono,
                'disponibilidad' => true,
            ]);
            $hoteles = collect([$hotel]);
        }

        $hotelId = $request->get('hotel_id', $hoteles->first()?->id);
        $hotelActual = $hoteles->firstWhere('id', $hotelId);

        $habitaciones = $hotelActual
            ? Habitacion::where('hotel_id', $hotelActual->id)->orderBy('nombre')->get()
            : collect();

        return view('empresa.habitaciones', compact('empresa', 'hoteles', 'hotelActual', 'habitaciones'));
    }

    public function store(Request $request, Hotel $hotel)
    {
        $empresa = $this->empresa();
        abort_if($hotel->empresa_id !== $empresa->id, 403);

        $request->validate([
            'nombre'             => 'required|string|max:100',
            'tipo'               => 'required|in:sencilla,doble,triple,suite,familiar',
            'num_camas'          => 'required|integer|min:1',
            'tipo_cama'          => 'required|in:individual,doble,queen,king,mixta',
            'capacidad_personas' => 'required|integer|min:1',
            'precio_noche'       => 'required|numeric|min:0',
            'descripcion'        => 'nullable|string|max:500',
            'amenidades'         => 'nullable|array',
        ]);

        Habitacion::create([
            'hotel_id'           => $hotel->id,
            'nombre'             => $request->nombre,
            'tipo'               => $request->tipo,
            'num_camas'          => $request->num_camas,
            'tipo_cama'          => $request->tipo_cama,
            'capacidad_personas' => $request->capacidad_personas,
            'precio_noche'       => $request->precio_noche,
            'disponible'         => $request->boolean('disponible', true),
            'descripcion'        => $request->descripcion,
            'amenidades'         => $request->amenidades ?? [],
        ]);

        return redirect()->route('empresa.habitaciones.index', ['hotel_id' => $hotel->id])
            ->with('success', 'Habitación creada correctamente.');
    }

    public function update(Request $request, Habitacion $habitacion)
    {
        $empresa = $this->empresa();
        abort_if($habitacion->hotel->empresa_id !== $empresa->id, 403);

        $request->validate([
            'nombre'             => 'required|string|max:100',
            'tipo'               => 'required|in:sencilla,doble,triple,suite,familiar',
            'num_camas'          => 'required|integer|min:1',
            'tipo_cama'          => 'required|in:individual,doble,queen,king,mixta',
            'capacidad_personas' => 'required|integer|min:1',
            'precio_noche'       => 'required|numeric|min:0',
            'descripcion'        => 'nullable|string|max:500',
            'amenidades'         => 'nullable|array',
        ]);

        $habitacion->update([
            'nombre'             => $request->nombre,
            'tipo'               => $request->tipo,
            'num_camas'          => $request->num_camas,
            'tipo_cama'          => $request->tipo_cama,
            'capacidad_personas' => $request->capacidad_personas,
            'precio_noche'       => $request->precio_noche,
            'disponible'         => $request->boolean('disponible', true),
            'descripcion'        => $request->descripcion,
            'amenidades'         => $request->amenidades ?? [],
        ]);

        return redirect()->route('empresa.habitaciones.index', ['hotel_id' => $habitacion->hotel_id])
            ->with('success', 'Habitación actualizada.');
    }

    public function destroy(Habitacion $habitacion)
    {
        $empresa = $this->empresa();
        abort_if($habitacion->hotel->empresa_id !== $empresa->id, 403);
        $hotelId = $habitacion->hotel_id;
        $habitacion->delete();
        return redirect()->route('empresa.habitaciones.index', ['hotel_id' => $hotelId])
            ->with('success', 'Habitación eliminada.');
    }

    public function toggleDisponibilidad(Habitacion $habitacion)
    {
        $empresa = $this->empresa();
        abort_if($habitacion->hotel->empresa_id !== $empresa->id, 403);
        $habitacion->update(['disponible' => !$habitacion->disponible]);
        return back()->with('success', 'Disponibilidad actualizada.');
    }

    public function storeHotel(Request $request)
    {
        $empresa = $this->empresa();

        $request->validate([
            'nombre'    => 'required|string|max:200',
            'ubicacion' => 'nullable|string|max:400',
            'telefono'  => 'nullable|string|max:30',
            'email'     => 'nullable|email|max:200',
        ]);

        $hotel = Hotel::create([
            'empresa_id'     => $empresa->id,
            'nombre'         => $request->nombre,
            'precio'         => 0,
            'ubicacion'      => $request->ubicacion,
            'telefono'       => $request->telefono,
            'email'          => $request->email,
            'disponibilidad' => true,
        ]);

        return redirect()->route('empresa.habitaciones.index', ['hotel_id' => $hotel->id])
            ->with('success', 'Hotel registrado. Ahora puedes agregar habitaciones.');
    }

    public function destroyHotel(Hotel $hotel)
    {
        $empresa = $this->empresa();
        abort_if($hotel->empresa_id !== $empresa->id, 403);
        $hotel->delete();
        return redirect()->route('empresa.habitaciones.index')
            ->with('success', 'Hotel eliminado correctamente.');
    }

    public function updateHotel(Request $request, Hotel $hotel)
    {
        $empresa = $this->empresa();
        abort_if($hotel->empresa_id !== $empresa->id, 403);

        $request->validate([
            'nombre'      => 'required|string|max:200',
            'precio'      => 'nullable|numeric|min:0',
            'capacidad'   => 'nullable|integer|min:1',
            'ubicacion'   => 'nullable|string|max:400',
            'telefono'    => 'nullable|string|max:30',
            'email'       => 'nullable|email|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'servicios'   => 'nullable|string|max:500',
            'imagen_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagen = $hotel->imagen;
        if ($request->hasFile('imagen_file')) {
            if ($imagen && !str_starts_with($imagen, 'http')) {
                Storage::disk('public')->delete($imagen);
            }
            $imagen = $request->file('imagen_file')->store("hoteles/{$hotel->id}", 'public');
        } elseif ($request->filled('imagen_url')) {
            $imagen = $request->imagen_url;
        }

        $hotel->update([
            'nombre'      => $request->nombre,
            'precio'      => $request->precio ?? 0,
            'capacidad'   => $request->capacidad,
            'ubicacion'   => $request->ubicacion,
            'telefono'    => $request->telefono,
            'email'       => $request->email,
            'descripcion' => $request->descripcion,
            'servicios'   => $request->servicios,
            'imagen'      => $imagen,
        ]);

        return redirect()->route('empresa.habitaciones.index', ['hotel_id' => $hotel->id])
            ->with('success', 'Información del hotel actualizada.');
    }
}
