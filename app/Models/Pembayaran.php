<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'tb_pembayaran';

    public function Booking(){
        return $this->hasMany('App\Models\Booking', 'id_booking');
    }   

    public function RiwayatStatusPembayaran(){
        return $this->hasMany('App\Models\RiwayatStatusPembayaran', 'id_pembayaran');
    } 
}