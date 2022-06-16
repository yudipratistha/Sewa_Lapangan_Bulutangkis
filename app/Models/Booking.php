<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'tb_booking';

    public function User(){
        return $this->belongsTo('App\Models\User', 'id_pengguna', 'id');
    }   

    public function Lapangan(){
        return $this->belongsTo('App\Models\Lapangan', 'id');
    }

    public function Pembayaran(){
        return $this->belongsTo('App\Models\Pembayaran', 'id');
    }
}
