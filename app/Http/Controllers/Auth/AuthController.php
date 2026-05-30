<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo'   => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = [
            'email'    => $request->correo,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['correo' => 'Correo o contraseña incorrectos.'])
                ->withInput($request->only('correo'));
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->estado === 'bloqueado') {
            Auth::logout();
            $request->session()->invalidate();
            return back()
                ->withErrors(['correo' => 'Tu cuenta ha sido bloqueada. Contacta al administrador.'])
                ->withInput($request->only('correo'));
        }

        if ($user->estado === 'pendiente') {
            Auth::logout();
            $request->session()->invalidate();
            return back()
                ->withErrors(['correo' => 'Tu cuenta está pendiente de aprobación.'])
                ->withInput($request->only('correo'));
        }

        return $this->redirectByRole($user);
    }

    public function showRegistro()
    {
        return view('auth.registro');
    }

    public function registro(Request $request)
    {
        $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'correo'   => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'rol'      => ['required', 'in:usuario,empresa'],
        ], [
            'correo.unique'      => 'Ese correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        if ($request->rol === 'empresa') {
            $request->validate([
                'empresa_nombre'    => ['required', 'string', 'max:200'],
                'tipo_empresa'      => ['nullable', 'in:hotel,restaurante,agencia_turismo,transporte,artesanias,otro'],
                'servicios'         => ['nullable', 'array'],
                'servicios.*'       => ['string', 'max:100'],
                'descripcion'       => ['nullable', 'string', 'max:1000'],
                'nit'               => ['nullable', 'string', 'max:20'],
                'sitio_web'         => ['nullable', 'url'],
                'empresa_logo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'empresa_logo_url'  => ['nullable', 'url'],
                'instagram'         => ['nullable', 'string', 'max:200'],
                'facebook'          => ['nullable', 'string', 'max:200'],
                'hotel_precio'      => ['nullable', 'numeric', 'min:0'],
                'hotel_capacidad'   => ['nullable', 'integer', 'min:1'],
            ], [
                'empresa_nombre.required' => 'El nombre de la empresa es obligatorio.',
                'sitio_web.url'           => 'El sitio web debe ser una URL válida (incluye https://).',
                'empresa_logo_url.url'    => 'La URL del logo debe ser válida.',
            ]);
        }

        $estado = $request->rol === 'empresa' ? 'pendiente' : 'activo';

        $user = User::create([
            'name'     => $request->nombre,
            'email'    => $request->correo,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'rol'      => $request->rol,
            'estado'   => $estado,
        ]);

        if ($request->rol === 'empresa') {
            // Manejar logo
            $logo = null;
            if ($request->hasFile('empresa_logo_file')) {
                $logo = Storage::disk('public')->putFile('logos/empresas', $request->file('empresa_logo_file'));
            } elseif ($request->filled('empresa_logo_url')) {
                $logo = $request->empresa_logo_url;
            }

            $empresa = \App\Models\Empresa::create([
                'usuario_id'  => $user->id,
                'nombre'      => $request->empresa_nombre,
                'telefono'    => $request->telefono,
                'direccion'   => $request->empresa_direccion,
                'tipo_empresa'=> $request->tipo_empresa,
                'servicios'   => $request->servicios ?? [],
                'descripcion' => $request->descripcion,
                'logo'        => $logo,
                'nit'         => $request->nit,
                'sitio_web'   => $request->sitio_web,
                'instagram'   => $request->instagram,
                'facebook'    => $request->facebook,
                'aprobado'    => false,
            ]);

            // Pre-crear hotel si es tipo hotel
            if ($request->tipo_empresa === 'hotel') {
                $precioHotel = $request->hotel_precio ?? 0;
                \App\Models\Hotel::create([
                    'empresa_id'     => $empresa->id,
                    'nombre'         => $request->empresa_nombre,
                    'descripcion'    => $request->descripcion,
                    'telefono'       => $request->telefono,
                    'precio'         => $precioHotel,
                    'capacidad'      => $request->hotel_capacidad,
                    'disponibilidad' => $precioHotel > 0,
                ]);
            }
        }

        return redirect()->route('registro')->with('success', $request->rol === 'empresa' ? 'empresa' : 'usuario');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    private function redirectByRole(User $user)
    {
        return match ($user->rol) {
            'admin'   => redirect()->route('admin.dashboard'),
            'empresa' => redirect()->route('empresa.dashboard'),
            default   => redirect()->route('home'),
        };
    }
}