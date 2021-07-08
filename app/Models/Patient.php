<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that can be mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'phone',
        'cpf',
        'health_plan',
    ];
    //-------------------------------------------
    // Accessors
    //-------------------------------------------

    //-------------------------------------------
    // Mutators
    //-------------------------------------------

    //-------------------------------------------
    // Relations
    //-------------------------------------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
