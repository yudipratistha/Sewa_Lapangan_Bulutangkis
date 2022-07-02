<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStatusPembayaran extends Model
{
    use HasFactory;

    protected $table= 'tb_riwayat_status_pembayaran';

    public function Pembayaran(){
        return $this->belongsTo('App\Models\Pembayaran', 'id');
    }
}
