<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitWaktuBookingLapangan extends Model
{
    use HasFactory;

    protected $table = 'tb_limit_waktu_booking_lapangan';

    protected $guarded = ['id'];
}
