<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiskReplacement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'classroom_code',
        'issue',
        'replaced_at',
        'smtr'
    ];

    protected $casts = [
        'replaced_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_code', 'code');
    }
}
