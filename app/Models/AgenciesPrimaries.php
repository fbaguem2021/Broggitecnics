<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgenciesPrimaries extends Model
{
    use HasFactory;

    protected $table = 'agenciesprimaries';
    public $incrementing = false;
    public $timestamps = false;

    //Relacion 1:N con agencies
    public function agencia(){
        return $this->hasMany(Agencia::class, 'agencies_primaries_id');
    }
}
