<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketSewaBulananNormal extends Model
{
    use HasFactory;

    protected $table = 'tb_paket_sewa_bulanan_normal';

    protected $guarded = ['id'];

    public function Lapangan(){
        return $this->belongsTo('App\Models\Lapangan', 'id');
    }
}
