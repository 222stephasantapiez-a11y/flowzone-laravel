<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

trait HandlesImport
{
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
        $updated  = method_exists($import, 'getUpdatedCount') ? $import->getUpdatedCount() : 0;
        $errors   = $import->getErrors();

        // Sin resultados
        if ($imported === 0 && $updated === 0) {
            if (!empty($errors)) {
                return redirect()->route($redirectRoute)
                    ->with('error', 'No se importó ningún registro. Revisa los errores:')
                    ->with('import_errors', $errors);
            }

            return redirect()->route($redirectRoute)
                ->with('warning', 'No se encontraron registros nuevos para importar.');
        }

        // Construir mensaje legible
        $partes = [];

        if ($imported === 1) {
            $partes[] = '1 registro nuevo creado';
        } elseif ($imported > 1) {
            $partes[] = "{$imported} registros nuevos creados";
        }

        if ($updated === 1) {
            $partes[] = '1 registro actualizado';
        } elseif ($updated > 1) {
            $partes[] = "{$updated} registros actualizados";
        }

        $msg = implode(' y ', $partes) . '.';

        if (!empty($errors)) {
            return redirect()->route($redirectRoute)
                ->with('warning', $msg . ' Algunas filas fueron omitidas.')
                ->with('import_errors', $errors);
        }

        return redirect()->route($redirectRoute)
            ->with('success', $msg);
    }
}