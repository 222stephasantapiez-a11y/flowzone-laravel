<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        // Regenerar sesión INMEDIATAMENTE después del attempt, antes de cualquier otra lógica
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
            'correo.unique'       => 'Ese correo ya está registrado.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
            'password.min'        => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        if ($request->rol === 'empresa') {
            $request->validate([
                'empresa_nombre' => ['required', 'string', 'max:200'],
            ], [
                'empresa_nombre.required' => 'El nombre de la empresa es obligatorio.',
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

        // Crear registro en tabla empresas si el rol es empresa
        if ($request->rol === 'empresa') {
            \App\Models\Empresa::create([
                'usuario_id' => $user->id,
                'nombre'     => $request->empresa_nombre,
                'telefono'   => $request->telefono,
                'direccion'  => $request->empresa_direccion,
                'aprobado'   => false,
            ]);
        }

        return back()->with('success', $request->rol === 'empresa' ? 'empresa' : 'usuario');
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
