<?php

namespace App\Exports;

use App\Models\Export;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class IadpExport implements FromView, WithColumnWidths, WithEvents
{
    protected $tahun;

    public function __construct(String $tahun) {
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $exports = Export::where('jenis_infeksi', 'iadp')
                        ->where('tahun', $this->tahun)
                        ->get();

        return view('exports.iadp.index', [
            'iadp' => $exports,
            'tahun' => $this->tahun
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'B' => 13,
            'C' => 13,
            'D' => 13,
            'E' => 13,
            'F' => 13,
            'G' => 13,
            'H' => 30,
            'I' => 30,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A5:I5')
                                ->applyFromArray(
                                    [
                                        'alignment' => [
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                                        ],
                                        'fill' => [
                                            'fillType' => 'solid',
                                            'rotation' => 0, 
                                            'color' => ['rgb' => 'C4C4C4'],
                                        ]
                                    ]
                                );
                $event->sheet->getDelegate()->getStyle('A6:A17')
                                ->applyFromArray(
                                    [
                                        'alignment' => [
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                                        ],
                                        'fill' => [
                                            'fillType' => 'solid',
                                            'rotation' => 0, 
                                            'color' => ['rgb' => 'E9ECEF'],
                                        ]
                                    ]
                                );
                $event->sheet->getDelegate()->getStyle('B6:B17')
                                ->applyFromArray(
                                    [
                                        'fill' => [
                                            'fillType' => 'solid',
                                            'rotation' => 0, 
                                            'color' => ['rgb' => 'E9ECEF'],
                                        ]
                                    ]
                                );
                $event->sheet->getDelegate()->getStyle('F6:F17')
                                ->applyFromArray(
                                    [
                                        'fill' => [
                                            'fillType' => 'solid',
                                            'rotation' => 0, 
                                            'color' => ['rgb' => 'E9ECEF'],
                                        ]
                                    ]
                                );
                $event->sheet->getDelegate()->getStyle('E6:E17')
                                ->getFill()
                                ->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => '5EBA7D'],]);
            },
        ];
    }
}
