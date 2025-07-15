<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $guarded = ['id'];    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }public function pkl()
    {
        return $this->belongsTo(PKL::class, 'pkl_id');
    }

    public function logbookContents()
    {
        return $this->hasMany(LogbookContent::class);
    }
}
