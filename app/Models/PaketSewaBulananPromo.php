<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketSewaBulananPromo extends Model
{
    use HasFactory;

    protected $table = 'tb_paket_sewa_bulanan_promo';

    protected $guarded = ['id'];

    public function Lapangan(){
        return $this->belongsTo('App\Models\Lapangan', 'id');
    }
}
