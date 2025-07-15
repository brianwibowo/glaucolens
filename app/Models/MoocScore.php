<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoocScore extends Model
{
    use HasFactory;

    protected $table = 'mooc_scores';

    protected $guarded = ['id'];

    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mooc()
    {
        return $this->belongsTo(Mooc::class, 'mooc_id');
    }
}
