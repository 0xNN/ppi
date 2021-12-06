<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAntibiotik extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nama_kategori_antibiotik'];

    public function antibiotik()
    {
        return $this->hasMany(Antibiotik::class);
    }
}
