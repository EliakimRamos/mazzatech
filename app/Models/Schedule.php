<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that can be mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'specialty',
        'scheduling',
        'status',
    ];

    const STATUS = [
        1 => 'Agendado',
        2 => 'Confirmado',
        3 => 'Realizado',
        4 => 'Cancelado',
    ];
    //-------------------------------------------
    // Accessors
    //-------------------------------------------
    public function getSchedulingAttribute($value)
    {
        $dataptbr =Carbon::parse($value);
        return  $dataptbr->format('d/m/Y H:i:S');
    }

    //-------------------------------------------
    // Mutators
    //-------------------------------------------

    //-------------------------------------------
    // Relations
    //-------------------------------------------

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
