<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

trait HandlesImport
{
    /**
     * Ejecuta la importación y devuelve una redirección con mensajes claros.
     *
     * @param  Request  $request
     * @param  object   $import   Instancia del Import (debe usar BaseImport trait)
     * @param  string   $redirectRoute
     * @return RedirectResponse
     */
    protected function runImport(Request $request, object $import, string $redirectRoute): RedirectResponse
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'archivo.required' => 'Debes seleccionar un archivo.',
            'archivo.mimes'    => 'Solo se aceptan archivos .xlsx, .xls o .csv.',
            'archivo.max'      => 'El archivo no puede superar 5 MB.',
        ]);

        try {
            Excel::import($import, $request->file('archivo'));
        } catch (Throwable $e) {
            return redirect()->route($redirectRoute)
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }

        $imported = $import->getImportedCount();
        $errors   = $import->getErrors();

        if ($imported === 0 && !empty($errors)) {
            return redirect()->route($redirectRoute)
                ->with('error', 'No se importó ningún registro. Revisa los errores:')
                ->with('import_errors', $errors);
        }

        $msg = "Se importaron {$imported} registro(s) correctamente.";

        if (!empty($errors)) {
            return redirect()->route($redirectRoute)
                ->with('warning', $msg . ' Algunas filas fueron omitidas.')
                ->with('import_errors', $errors);
        }

        return redirect()->route($redirectRoute)->with('success', $msg);
    }
}
