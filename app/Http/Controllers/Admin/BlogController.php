<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BlogsExport;
use App\Imports\BlogsImport;

class BlogController extends Controller
{
    // ==========================
    // LISTAR + FILTROS
    // ==========================
    public function index(Request $request)
    {
        $query = BlogPost::with(['empresa', 'usuario']);

        // Filtros
        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_publicacion', $request->fecha);
        }

        if ($request->filled('autor')) {
            $query->where('autor', 'like', '%' . $request->autor . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $perPage = $request->get('per_page', 10);

        $posts = $query->orderBy('id', 'desc')
                       ->paginate($perPage)
                       ->withQueryString();

        $empresas = Empresa::where('aprobado', true)
                           ->orderBy('nombre')
                           ->get();

        return view('admin.blog', compact('posts', 'empresas', 'perPage'));
    }

    // ==========================
    // MANEJO DE IMÁGENES
    // ==========================
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

    // ==========================
    // CREAR
    // ==========================
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

    // ==========================
    // EDITAR
    // ==========================
    public function edit(BlogPost $blog)
    {
        $perPage = 10;

        $posts = BlogPost::with(['empresa', 'usuario'])
                         ->orderBy('id', 'desc')
                         ->paginate($perPage)
                         ->withQueryString();

        $empresas = Empresa::where('aprobado', true)
                           ->orderBy('nombre')
                           ->get();

        return view('admin.blog', compact('posts', 'empresas', 'blog', 'perPage'));
    }

    // ==========================
    // ACTUALIZAR
    // ==========================
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

    // ==========================
    // ELIMINAR
    // ==========================
    public function destroy(BlogPost $blog)
    {
        if ($blog->imagen && !str_starts_with($blog->imagen, 'http')) {
            Storage::disk('public')->delete($blog->imagen);
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')
                         ->with('success', 'Publicación eliminada.');
    }

    // ==========================
    // PUBLICAR / DESPUBLICAR
    // ==========================
    public function togglePublicado(BlogPost $blog)
    {
        $blog->update(['publicado' => !$blog->publicado]);

        return back()->with(
            'success',
            $blog->publicado ? 'Publicación publicada.' : 'Publicación despublicada.'
        );
    }

    // ==========================
    // EXPORTAR EXCEL
    // ==========================
    public function exportExcel()
    {
        return Excel::download(new BlogsExport, 'blogs.xlsx');
    }

    // ==========================
    // IMPORTAR EXCEL
    // ==========================
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new BlogsImport, $request->file('archivo'));

        return back()->with('success', 'Blogs importados correctamente');
    }

    // ==========================
    // EXPORTAR PDF
    // ==========================
    public function exportPdf()
    {
        $blogs = BlogPost::all();

        $pdf = Pdf::loadView('admin.pdf.blog', compact('blogs'));

        return $pdf->download('blogs.pdf');
    }
}