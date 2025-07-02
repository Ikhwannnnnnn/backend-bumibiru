<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mentor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'mentors';

    protected $fillable = [
        'name',
        'id_user',
        'description',
        'phone',
        'image',
        'cv',
        'skills',
        'status',
        'alamat',
        'latitude',
        'longitude',
        'tanggal_lapor'
    ];
    protected $casts = [
        'tanggal_lapor' => 'date', // agar otomatis jadi objek Carbon
    ];

    protected $dates = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
