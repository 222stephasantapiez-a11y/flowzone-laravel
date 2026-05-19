<?php

namespace App\Exports;

use App\Models\Reserva;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReservasExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Reserva::with(['usuario','hotel'])->get()
            ->map(fn($r) => [
                $r->id,
                $r->usuario?->name ?? '—',
                $r->hotel?->nombre ?? '—',
                $r->fecha_entrada?->format('d/m/Y'),
                $r->fecha_salida?->format('d/m/Y'),
                $r->num_personas,
                '$'.number_format($r->precio_total,0,',','.'),
                ucfirst($r->estado),
                $r->metodo_pago ?? '—',
                $r->referencia_pago ?? '—',
            ]);
    }

    public function headings(): array
    {
        return ['ID','Usuario','Hotel','Fecha Entrada','Fecha Salida','Personas','Precio Total','Estado','Método Pago','Referencia'];
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
