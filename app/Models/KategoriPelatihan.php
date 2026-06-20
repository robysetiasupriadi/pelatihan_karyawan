<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPelatihan extends Model
{
    protected $table = 'kategori_pelatihan';

    protected $fillable = ['nama', 'deskripsi'];

    public function pelatihan(): HasMany
    {
        return $this->hasMany(Pelatihan::class, 'kategori_id');
    }
}
