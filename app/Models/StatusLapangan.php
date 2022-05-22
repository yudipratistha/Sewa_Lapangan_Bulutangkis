<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLapangan extends Model
{
    use HasFactory;

    protected $table = 'tb_status_lapangan';

    public function Lapangan(){
        return $this->belongsTo('App\Models\Lapangan', 'id');
    }
}
