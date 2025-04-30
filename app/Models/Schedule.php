<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'classroom_code',
        'time',
        'smtr'
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_code', 'code');
    }
}
