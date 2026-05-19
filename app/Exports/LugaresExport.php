<?php

namespace App\Exports;

use App\Models\Lugar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LugaresExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Lugar::select('id','nombre','descripcion','ubicacion','categoria','precio_entrada','horario','created_at')->get()
            ->map(fn($l) => [
                $l->id, $l->nombre, $l->descripcion ?? '—',
                $l->ubicacion ?? '—', $l->categoria ?? '—',
                $l->precio_entrada > 0 ? '$'.number_format($l->precio_entrada,0,',','.') : 'Gratuito',
                $l->horario ?? '—', $l->created_at?->format('d/m/Y'),
            ]);
    }

    public function headings(): array
    {
        return ['ID','Nombre','Descripción','Ubicación','Categoría','Precio Entrada','Horario','Fecha Registro'];
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold'=>true,'color'=>['argb'=>'FFFFFFFF'],'size'=>11],
            'fill'      => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['argb'=>'FF2d7a2d']],
            'alignment' => ['horizontal'=>Alignment::HORIZONTAL_CENTER,'vertical'=>Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        for ($row = 2; $row <= $lastRow; $row++) {
            $color = ($row % 2 === 0) ? 'FFF1F8F1' : 'FFFFFFFF';
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'      => ['fillType'=>Fill::FILL_SOLID,'startColor'=>['argb'=>$color]],
                'alignment' => ['vertical'=>Alignment::VERTICAL_CENTER],
            ]);
        }

        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle'=>Border::BORDER_THIN,'color'=>['argb'=>'FFD0E8D0']]],
        ]);

        return [];
    }
}
