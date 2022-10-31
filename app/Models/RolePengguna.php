<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePengguna extends Model
{
    use HasFactory;

    protected $table = 'tb_role_pengguna';

    protected $guarded = ['id'];

    public function User(){
        return $this->belongsTo('App\Models\User', 'id_role_pengguana', 'id');
    }
}
