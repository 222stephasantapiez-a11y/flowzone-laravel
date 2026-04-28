<?php

namespace App\Imports;

use App\Models\Gastronomia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GastronomiaEmpresaImport implements ToModel, WithHeadingRow
{
    protected int $empresaId;

    public function __construct(int $empresaId)
    {
        $this->empresaId = $empresaId;
    }

    // La fila 3 es donde están los encabezados (fila 1 = título, fila 2 = vacía)
    public function headingRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        // Normaliza claves
        $r = [];
        foreach ($row as $key => $value) {
            $clean = strtolower(trim((string)$key));
            $clean = strtr($clean, ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n']);
            $clean = preg_replace('/[\s\/\(\)]+/', '_', $clean);
            $clean = trim($clean, '_');
            $r[$clean] = $value;
        }

        if (empty($r['nombre'])) return null;

        // Acepta: precio_cop, precio, precio_promedio
        $precio = $r['precio_cop'] ?? $r['precio'] ?? $r['precio_promedio'] ?? null;
        if ($precio !== null) {
            $precio = (float) preg_replace('/[^0-9.]/', '', (string)$precio);
            if ($precio == 0) $precio = null;
        }

        return new Gastronomia([
            'empresa_id'      => $this->empresaId,
            'nombre'          => $r['nombre'],
            'tipo'            => $r['tipo'] ?? null,
            'precio_promedio' => $precio,
            'descripcion'     => $r['descripcion'] ?? null,
            'direccion'       => $r['direccion'] ?? null,
            'telefono'        => $r['telefono'] ?? null,
            'ingredientes'    => $r['ingredientes'] ?? null,
        ]);
    }
}