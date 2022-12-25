<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LapanganLibur extends Model
{
    use HasFactory;

    protected $table = 'tb_lapangan_libur';

    protected $guarded = ['id'];
}
