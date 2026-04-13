<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use App\Exports\BlogsExport;
use App\Imports\BlogsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    private function handleImage(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('imagen_file')) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            return $request->file('imagen_file')->store('uploads/blog', 'public');
        }
        if ($request->filled('imagen_url')) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            return $request->imagen_url;
        }
        return $current;
    }

    public function index(Request $request)
    {
        $perPage  = $request->get('per_page', 10);
        $posts    = BlogPost::with(['empresa', 'usuario'])->latest()->paginate($perPage)->withQueryString();
        $empresas = Empresa::where('aprobado', true)->orderBy('nombre')->get();
        return view('admin.blog', compact('posts', 'empresas', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'     => 'required|string|max:200',
            'contenido'  => 'required|string',
            'tipo'       => 'required|in:evento,noticia',
            'imagen_url' => 'nullable|url',
            'imagen_file'=> 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        BlogPost::create([
            'titulo'            => $request->titulo,
            'contenido'         => $request->contenido,
            'tipo'              => $request->tipo,
            'autor'             => $request->autor,
            'empresa_id'        => $request->empresa_id ?: null,
            'usuario_id'        => auth()->id(),
            'publicado'         => $request->boolean('publicado', true),
            'fecha_publicacion' => $request->fecha_publicacion ?: now(),
            'imagen'            => $this->handleImage($request),
        ]);

        return redirect()->route('admin.blog.index')
                         ->with('success', 'Publicación creada correctamente.');
    }

    public function edit(BlogPost $blog)
    {
        $perPage  = 10;
        $posts    = BlogPost::with(['empresa', 'usuario'])->latest()->paginate($perPage)->withQueryString();
        $empresas = Empresa::where('aprobado', true)->orderBy('nombre')->get();
        return view('admin.blog', compact('posts', 'empresas', 'blog', 'perPage'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $request->validate([
            'titulo'     => 'required|string|max:200',
            'contenido'  => 'required|string',
            'tipo'       => 'required|in:evento,noticia',
            'imagen_url' => 'nullable|url',
            'imagen_file'=> 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $blog->update([
            'titulo'            => $request->titulo,
            'contenido'         => $request->contenido,
            'tipo'              => $request->tipo,
            'autor'             => $request->autor,
            'empresa_id'        => $request->empresa_id ?: null,
            'publicado'         => $request->boolean('publicado', true),
            'fecha_publicacion' => $request->fecha_publicacion ?: $blog->fecha_publicacion,
            'imagen'            => $this->handleImage($request, $blog->imagen),
        ]);

        return redirect()->route('admin.blog.index')
                         ->with('success', 'Publicación actualizada.');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->imagen && !str_starts_with($blog->imagen, 'http')) {
            Storage::disk('public')->delete($blog->imagen);
        }
        $blog->delete();
        return redirect()->route('admin.blog.index')
                         ->with('success', 'Publicación eliminada.');
    }

    public function togglePublicado(BlogPost $blog)
    {
        $blog->update(['publicado' => !$blog->publicado]);
        $msg = $blog->publicado ? 'Publicación publicada.' : 'Publicación despublicada.';
        return back()->with('success', $msg);
    }
}