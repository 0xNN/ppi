<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Antibiotik extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_antibiotik',
        'jumlah',
        'tanggal_awal',
        'tanggal_akhir',
        'kategori_antibiotik_id',
        'is_active'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function kategori_antibiotik()
    {
        return $this->belongsTo(KategoriAntibiotik::class);
    }
}
