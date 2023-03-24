<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedient extends Model
{
    use HasFactory;

    protected $table = 'expedients';
    public $timestamps = false;

   /*  public function rol(): BelongsTo{
        return $this->belongsTo(Rol::class, 'tipus_usuaris_id');
    } */
}
