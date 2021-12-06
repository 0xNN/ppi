<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql2';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'registration';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function patient()
    {
        return $this->belongsTo(Patient::class,'MedicalNo','MedicalNo');
    }

    public function service_room()
    {
        return $this->belongsTo(ServiceRoom::class,'RoomID','RoomID');
    }
}
