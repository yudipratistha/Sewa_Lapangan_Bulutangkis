<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'tb_lapangan';

    public function User(){
        return $this->belongsTo('App\Models\User', 'id_pengguna', 'id');
    }   

    public function Booking(){
        return $this->hasMany('App\Models\Booking', 'id_lapangan');
    }

    public function StatusLapangan(){
        return $this->hasMany('App\Models\StatusLapangan', 'id_lapangan');
    }
}
