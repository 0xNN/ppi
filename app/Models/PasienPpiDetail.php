<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasienPpiDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'diagnosa' => 'array',
        'jenis_infeksi_rs' => 'array',
        'jenis_kumen' => 'array',
        'jenis_kultur_pendukun_hais' => 'array',
        'antibiotik' => 'array',
        'infeksi_rs_lain' => 'array',
        'alat_digunakan_id' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pasien_ppi_id',
        'ruang_id',
        'diagnosa',
        'is_operasi',
        'tindakan_operasi_id',
        'jenis_operasi_id',
        'lama_operasi_id',
        'asa_score_id',
        'risk_score_id',
        'tgl_sensus',
        'alat_digunakan_id',
        'kegiatan_sensus_id',
        'foto_hasil_rontgen',
        'hasil_rontgen',
        'jenis_infeksi_rs',
        'jenis_kumen',
        'tgl_infeksi_kuman',
        'jenis_kultur_pendukun_hais',
        'antibiotik',
        'transmisi_id',
        'infeksi_rs_lain'
    ];
}
