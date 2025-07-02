<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Titik extends Model
{
    protected $table = 'mentors'; // pakai tabel mentors
    public $timestamps = false;   // jika tabel mentors tidak punya created_at dan updated_at
}
