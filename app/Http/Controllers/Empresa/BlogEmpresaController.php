<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogEmpresaController extends Controller
{
    private function empresa(): Empresa
    {
        return Empresa::where('usuario_id', Auth::id())->where('aprobado', true)->firstOrFail();
    }

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

    public function index()
    {
        $empresa = $this->empresa();
        $posts   = BlogPost::where('empresa_id', $empresa->id)->latest()->get();
        return view('empresa.blog', compact('empresa', 'posts'));
    }

    public function store(Request $request)
    {
        $empresa = $this->empresa();
        $request->validate([
            'titulo'     => 'required|string|max:200',
            'contenido'  => 'required|string',
            'tipo'       => 'required|in:evento,noticia',
            'imagen_url' => 'nullable|url',
            'imagen_file'=> 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        BlogPost::create([
            'titulo'           => $request->titulo,
            'contenido'        => $request->contenido,
            'tipo'             => $request->tipo,
            'autor'            => $empresa->nombre,
            'empresa_id'       => $empresa->id,
            'usuario_id'       => Auth::id(),
            'publicado'        => false, // requiere aprobación admin
            'fecha_publicacion'=> now(),
            'imagen'           => $this->handleImage($request),
        ]);

        return redirect()->route('empresa.blog.index')
                         ->with('success', 'Publicación enviada. Pendiente de aprobación.');
    }

    public function edit(BlogPost $post)
    {
        $empresa = $this->empresa();
        abort_if($post->empresa_id !== $empresa->id, 403);
        $posts = BlogPost::where('empresa_id', $empresa->id)->latest()->get();
        return view('empresa.blog', compact('empresa', 'posts', 'post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $empresa = $this->empresa();
        abort_if($post->empresa_id !== $empresa->id, 403);

        $request->validate([
            'titulo'     => 'required|string|max:200',
            'contenido'  => 'required|string',
            'tipo'       => 'required|in:evento,noticia',
            'imagen_url' => 'nullable|url',
            'imagen_file'=> 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $post->update([
            'titulo'    => $request->titulo,
            'contenido' => $request->contenido,
            'tipo'      => $request->tipo,
            'publicado' => false, // vuelve a revisión
            'imagen'    => $this->handleImage($request, $post->imagen),
        ]);

        return redirect()->route('empresa.blog.index')
                         ->with('success', 'Publicación actualizada. Pendiente de revisión.');
    }

    public function destroy(BlogPost $post)
    {
        $empresa = $this->empresa();
        abort_if($post->empresa_id !== $empresa->id, 403);
        if ($post->imagen && !str_starts_with($post->imagen, 'http')) {
            Storage::disk('public')->delete($post->imagen);
        }
        $post->delete();
        return redirect()->route('empresa.blog.index')->with('success', 'Publicación eliminada.');
    }
}
