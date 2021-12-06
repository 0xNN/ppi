<?php

namespace App\Exports;

use App\Models\AlatDigunakan;
use App\Models\Export;
use App\Models\PasienPpi;
use App\Models\PasienPpiDetail;
use App\Models\Patient;
use App\Models\Ruang;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapPasienExport implements FromView, WithColumnWidths, WithEvents, ShouldAutoSize
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

        $exports = PasienPpi::join('pasien_ppi_details','pasien_ppi_details.pasien_ppi_id','pasien_ppis.id')
                            ->whereMonth('tgl_masuk', $this->bulan)
                            ->whereYear('tgl_masuk', $this->tahun)
                            ->get();

        $model = [
            'darah',
            'sputurn',
            'swab_luka',
            'urine'
        ];
        $datas = array();

        foreach($exports as $item)
        {
            $asal_kultur = "";
            $patient = Patient::where('MedicalNo', $item->no_rm)->first();
            foreach(json_decode($item->jenis_kultur_pendukun_hais) as $j) {
                foreach($model as $m) {
                    if(isset($j->$m)) {
                        if($j->$m != null) {
                            $asal_kultur .= strtoupper(str_replace("_"," ",$j->$m)).", ";
                        }
                    }
                }
            }
            $ruang = Ruang::where('id', $item->ruang_id)->first();
            $d["nama_pasien"] = $patient->PatientName;
            $d["tanggal_lahir"] = $patient->DateOfBirth;
            $d["no_rm"] = $patient->MedicalNo;
            $d["asal_kultur"] = $asal_kultur;
            $d["tanggal_kultur"] = $item->tgl_infeksi_kuman;
            $d["hasil_kultur_kuman"] = $item->hasil_rontgen;
            $d["status"] = '';
            $d["ruang"] = $ruang->nama_ruang;

            array_push($datas, $d);
        }
        return view('exports.rekap.pasien', [
            'exports' => $exports,
            'datas' => $datas,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
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
                $event->sheet->getDelegate()->getStyle('A')
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
            },
        ];
    }
}
