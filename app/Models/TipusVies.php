<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipusVies extends Model
{
    use HasFactory;

    protected $table = 'tipus_vies';
    protected $guarded = ['id'];
    public $timestamps = false;
}
