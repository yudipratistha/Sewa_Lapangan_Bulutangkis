<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courts extends Model
{
    use HasFactory;

    protected $table = 'tb_courts';

    protected $guarded = ['id'];

    public function Lapangan(){
        return $this->belongsTo('App\Models\Lapangan', 'id');
    }

    public function StatusCourt(){
        return $this->hasMany('App\Models\StatusCourt', 'id_court');
    }
}
