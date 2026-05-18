<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GastronomiaEmpresaExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items->map(function ($item, $i) {
            return [
                '#'               => $i + 1,
                'Nombre'          => $item->nombre,
                'Tipo'            => $item->tipo ?? '—',
                'Precio (COP)'    => $item->precio_promedio ? number_format($item->precio_promedio, 0, '.', ',') : '—',
                'Descripción'     => $item->descripcion ?? '—',
                'Dirección'       => $item->direccion ?? '—',
                'Teléfono'        => $item->telefono ?? '—',
                'Ingredientes'    => $item->ingredientes ?? '—',
                'Fecha registro'  => $item->created_at ? $item->created_at->format('d/m/Y') : '—',
            ];
        });
    }

    public function headings(): array
    {
        return ['#', 'Nombre', 'Tipo', 'Precio (COP)', 'Descripción', 'Dirección', 'Teléfono', 'Ingredientes', 'Fecha registro'];
    }

    public function title(): string
    {
        return 'Platos Registrados';
    }

    public function styles(Worksheet $sheet)
    {
        // Título en fila 1
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'Platos Registrados');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2D6A4F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Cabeceras en fila 3
        $sheet->getStyle('A3:I3')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '40916C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Bordes en datos
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A3:I{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'D1FAE5']]],
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 15,
            'D' => 15,
            'E' => 35,
            'F' => 25,
            'G' => 15,
            'H' => 30,
            'I' => 14,
        ];
    }
}