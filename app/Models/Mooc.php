<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MOOC extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'moocs';

    public function user()
    {
        return $this->belongsTo(User::class, 'perusahaan_id');
    }

    public function modules()
    {
        return $this->hasMany(MoocModule::class, 'mooc_id');
    }
    
    public function nilai()
    {
        return $this->hasMany(MoocScore::class, 'mooc_id');
    }

    public function reflections()
    {
        return $this->hasMany(MoocReflection::class, 'mooc_id');
    }
}