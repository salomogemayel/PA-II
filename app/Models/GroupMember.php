<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = ['undangan_id', 'name', 'email', 'phone'];

    public function undangan()
    {
        return $this->belongsTo(UndanganPengunjung::class, 'undangan_id');
    }
}

