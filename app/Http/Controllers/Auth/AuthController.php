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

        // Auth::attempt usa el campo 'email' del modelo
        $credentials = [
            'email'    => $request->correo,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['correo' => 'Correo o contraseña incorrectos.'])
                ->withInput($request->only('correo'));
        }

        $user = Auth::user();

        if ($user->estado === 'bloqueado') {
            Auth::logout();
            return back()
                ->withErrors(['correo' => 'Tu cuenta ha sido bloqueada. Contacta al administrador.'])
                ->withInput($request->only('correo'));
        }

        if ($user->estado === 'pendiente') {
            Auth::logout();
            return back()
                ->withErrors(['correo' => 'Tu cuenta está pendiente de aprobación.'])
                ->withInput($request->only('correo'));
        }

        $request->session()->regenerate();

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

        $estado = $request->rol === 'empresa' ? 'pendiente' : 'activo';

        User::create([
            'name'     => $request->nombre,
            'email'    => $request->correo,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'rol'      => $request->rol,
            'estado'   => $estado,
        ]);

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
