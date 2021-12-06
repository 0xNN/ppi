<?php

namespace App\Exports;

use App\Models\AlatDigunakan;
use App\Models\Export;
use App\Models\PasienPpi;
use App\Models\PasienPpiDetail;
use App\Models\Ruang;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapExport implements FromView, WithColumnWidths, WithEvents, ShouldAutoSize
{
    protected $tahun;
    protected $bulan;

    public function __construct(String $tahun, String $bulan) {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function view(): View
    {
        $month = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $exports = Export::where('jenis_infeksi', 'vap')
                ->where('tahun', $this->tahun)
                ->get();
        
        $ruangs = Ruang::all();
        $nama_ruang = array();

        $nama_ruangs = array();

        $nama_ruangss = array();

        foreach($ruangs as $ruang) {
            $nama_ruangs[$ruang->id]['jumlah'] = 0;
            $nama_ruangss[$ruang->id]['jumlah'] = 0;
        }

        foreach($ruangs as $ruang) {
            $nama_ruang[$ruang->id]['jumlah_pasien'] = 0;
            $nama_ruang[$ruang->id]['jumlah_hari'] = 0;
        }

        // dd($nama_ruang);
        $model = AlatDigunakan::all();

        $arr_data = array();

        $nama_alat = array();
        foreach($model as $item) {
            array_push($nama_alat, $item->nama_alat_digunakan);
        }

        array_push($nama_alat, "Tirah Baring");
        // dd($nama_alat);
        foreach($model as $data) 
        {
            $ppi_detail = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                            ->whereMonth('tgl_masuk', $this->bulan)
                                            ->whereYear('tgl_masuk', $this->tahun)
                                            ->get();
    
            $ppi_detail_distinct = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                ->whereMonth('tgl_masuk', $this->bulan)
                                ->whereYear('tgl_masuk', $this->tahun)
                                ->groupBy('no_rm')
                                ->get();
    
            $nama_alat[$data->nama_alat_digunakan] = 0;
            foreach($ppi_detail as $detail) {
                $i = 0;
                foreach(json_decode($detail->alat_digunakan_id) as $alat) {
                    if($alat == $data->id) {
                        $nama_alat[$data->nama_alat_digunakan] = $nama_alat[$data->nama_alat_digunakan] + 1;
                        $jumlah_hari = $nama_alat[$data->nama_alat_digunakan];
                        
                        foreach($nama_ruang as $key => $nr) {
                            if($key == $detail->ruang_id) {
                                $i = $i + 1;
                                $nama_ruang[$key]['jumlah_hari'] += $i;
                            }
                        }
                    }
                }
            }
            $nama_alat[$data->nama_alat_digunakan] = 0;
            foreach($ppi_detail_distinct as $detail) {
                $i = 0;
                foreach(json_decode($detail->alat_digunakan_id) as $alat) {
                    if($alat == $data->id) {
                        $nama_alat[$data->nama_alat_digunakan] = $nama_alat[$data->nama_alat_digunakan] + 1;
                        $jumlah_pasien = $nama_alat[$data->nama_alat_digunakan];

                        foreach($nama_ruang as $key => $nr) {
                            if($key == $detail->ruang_id) {
                                $i += 1;
                                $nama_ruang[$key]['jumlah_pasien'] += $i;
                            }
                        }
                    }
                }
            }

            $arr_data[$data->nama_alat_digunakan] = [
                'jumlah_pasien' => !isset($jumlah_pasien) ? 0: $jumlah_pasien,
                'jumlah_hari' => !isset($jumlah_hari) ? 0: $jumlah_hari,
                'ruang' => $nama_ruang
            ];
        }
        
        $ppi_detail = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                ->whereMonth('tgl_masuk', $this->bulan)
                                ->whereYear('tgl_masuk', $this->tahun)
                                ->get();
        
        $online_operasi = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                                ->whereMonth('tgl_masuk', $this->bulan)
                                ->whereYear('tgl_masuk', $this->tahun)
                                ->where('is_operasi', 1)
                                ->count();

        foreach($ppi_detail as $ppi) {
            $i = 0;
            if($ppi->is_operasi == 1) {
                foreach($nama_ruangss as $key => $nr) {
                    if($key == $ppi->ruang_id) {
                        $i += 1;
                        $nama_ruangss[$key]['jumlah'] += $i;
                    }
                }
            }
        }

        $arr_data["Jumlah Operasi"] = [
            "online" => $online_operasi,
            "ruang" => $nama_ruangss
        ];

        $online_tirah = 0;
        foreach($ppi_detail as $ppi) {
            $i = 0;
            foreach(json_decode($ppi->jenis_infeksi_rs) as $data) {
                if(isset($data->tirah_baring)) {
                    foreach($nama_ruangs as $key => $nr) {
                        if($key == $ppi->ruang_id) {
                            $i += 1;
                            $nama_ruangs[$key]['jumlah'] += $i;
                        }
                    }

                    if($data->tirah_baring != null) {
                        $online_tirah += 1;
                    }
                }
            }
        }

        $arr_data["Tirah Baring"] = [
            "online" => $online_tirah,
            "ruang" => $nama_ruangs
        ];

        // dd($arr_data);
        return view('exports.rekap.index', [
            'rekap' => $exports,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'arr_data' => $arr_data,
            'ruangs' => $ruangs,
            'month' => $month
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A4:I4')
                                ->applyFromArray(
                                    [
                                        'alignment' => [
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                                        ],
                                        // 'fill' => [
                                        //     'fillType' => 'solid',
                                        //     'rotation' => 0, 
                                        //     'color' => ['rgb' => 'C4C4C4'],
                                        // ]
                                    ]
                                );
                $event->sheet->getDelegate()->getStyle('A5:A16')
                                ->applyFromArray(
                                    [
                                        'alignment' => [
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                                        ],
                                        // 'fill' => [
                                        //     'fillType' => 'solid',
                                        //     'rotation' => 0, 
                                        //     'color' => ['rgb' => 'E9ECEF'],
                                        // ]
                                    ]
                                );
                $event->sheet->getDelegate()->getStyle('B5:B16')
                                ->applyFromArray(
                                    [
                                        // 'fill' => [
                                        //     'fillType' => 'solid',
                                        //     'rotation' => 0, 
                                        //     'color' => ['rgb' => 'E9ECEF'],
                                        // ]
                                        'alignment' => [
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                                        ],
                                    ]
                                );
            $event->sheet->getDelegate()->getStyle('C5:C16')
                                ->applyFromArray(
                                    [
                                        // 'fill' => [
                                        //     'fillType' => 'solid',
                                        //     'rotation' => 0, 
                                        //     'color' => ['rgb' => 'E9ECEF'],
                                        // ]
                                        'alignment' => [
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                                        ],
                                    ]
                                );
                 $event->sheet->getDelegate()->getStyle('D5:D16')
                                ->applyFromArray(
                                    [
                                        // 'fill' => [
                                        //     'fillType' => 'solid',
                                        //     'rotation' => 0, 
                                        //     'color' => ['rgb' => 'E9ECEF'],
                                        // ]
                                        'alignment' => [
                                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                                        ],
                                    ]
                                );
                $event->sheet->getDelegate()->getStyle('F5:F16')
                                ->applyFromArray(
                                    [
                                        // 'fill' => [
                                        //     'fillType' => 'solid',
                                        //     'rotation' => 0, 
                                        //     'color' => ['rgb' => 'E9ECEF'],
                                        // ]
                                    ]
                                );
            },
        ];
    }
}
