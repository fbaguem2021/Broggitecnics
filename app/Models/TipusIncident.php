<?php

namespace App\Models;

use App\Models\Incident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipusIncident extends Model
{
    use HasFactory;

    protected $table = 'tipus_incidents';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function incidents() {
        return $this->hasMany(Incident::class);
    }
}
