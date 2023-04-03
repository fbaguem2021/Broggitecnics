<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    use HasFactory;
    
    protected $table = 'agencies';
    public $timestamps = false;

    //Relacion 1:N agenciesPrimaries(N)
    public function agenciesPrimaries(){
        return $this->belongsTo(AgenciesPrimaries::class, 'AgenciesPrimaries_id');
    }
}
