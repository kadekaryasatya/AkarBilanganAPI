<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkarBilangan extends Model
{
    use HasFactory;

    protected $fillable = ['bilangan', 'akar', 'waktu_pemrosesan'];
}
