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

    public function Courts(){
        return $this->hasMany('App\Models\Courts', 'id_lapangan');
    }

    public function PaketSewaBulananNormal(){
        return $this->hasMany('App\Models\PaketSewaBulananNormal', 'id_lapangan');
    }

    public function PaketSewaBulananPromo(){
        return $this->hasMany('App\Models\PaketSewaBulananPromo', 'id_lapangan');
    }

    public function HargaPerJamNormal(){
        return $this->hasMany('App\Models\HargaPerJamNormal', 'id_lapangan');
    }

    public function HargaPerJamPromo(){
        return $this->hasMany('App\Models\HargaPerJamPromo', 'id_lapangan');
    }

    public function LapanganLibur(){
        return $this->hasMany('App\Models\LapanganLibur', 'id_lapangan');
    }
}
