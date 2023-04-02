<?php

namespace App\Models;

use App\Models\Expedient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstatExpedient extends Model
{
    use HasFactory;

    protected $table = 'estat_expedients';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function expedients()
    {
        return $this->hasMany(Expedient::class, 'estat_expedients_id');
    }
}

