<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'tb_pembayaran';

    public function Booking(){
        return $this->belongsTo('App\Models\Booking', 'id_booking', 'id');
    }   
}