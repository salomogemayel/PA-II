<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class undangan_host extends Model
{
    use HasFactory;
    protected $table = 'undangan_host';
    protected $fillable = [];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    public function detailUndanganHosts()
    {
        return $this->hasMany(DetailUndanganHost::class);
    }
}
