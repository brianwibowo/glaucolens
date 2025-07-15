<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent_Scouting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'talent_scoutings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status_seleksi' => 'string',
    ];

    /**
     * Get the status seleksi options.
     *
     * @return array
     */
    public static function getStatusSeleksiOptions()
    {
        return [
            'lolos' => 'Lolos',
            'tidak lolos' => 'Tidak Lolos',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($talent_scouting) {
            if ($talent_scouting->file_cv) {
                Storage::disk('public')->delete($talent_scouting->file_cv);
            }

            if ($talent_scouting->file_ijazah) {
                Storage::disk('public')->delete($talent_scouting->file_ijazah);
            }

            if ($talent_scouting->file_pernyataan) {
                Storage::disk('public')->delete($talent_scouting->file_pernyataan);
            }
        });
    }

    public function batch()
    {
        return $this->belongsTo(ScoutingBatch::class, 'batch_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
