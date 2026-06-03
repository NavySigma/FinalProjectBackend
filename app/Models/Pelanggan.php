<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $primaryKey = 'pelanggan_id';

    protected $fillable = [
        'pelanggan_nama',
        'pelanggan_alamat',
        'pelanggan_notelp',
        'pelanggan_email',
    ];

    // 1 pelanggan punya 1 pelanggan_data
    public function pelangganData()
    {
        return $this->hasOne(PelangganData::class, 'pelanggan_data_pelanggan_id', 'pelanggan_id');
    }

    // 1 pelanggan punya banyak penyewaan
    public function penyewaan()
    {
        return $this->hasMany(Penyewaan::class, 'penyewaan_pelanggan_id', 'pelanggan_id');
    }
}
