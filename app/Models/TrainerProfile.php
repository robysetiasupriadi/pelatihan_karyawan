<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerProfile extends Model
{
    protected $table = 'trainer_profile';

    protected $fillable = [
        'user_id',
        'spesialisasi',
        'bio',
        'no_sertifikasi_trainer',
        'rating',
        'total_pelatihan_diampu',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}