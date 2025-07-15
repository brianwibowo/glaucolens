<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoocReflection extends Model
{
    use HasFactory;

    protected $table = 'mooc_reflection';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mooc()
    {
        return $this->belongsTo(Mooc::class, 'mooc_id');
    }
}
