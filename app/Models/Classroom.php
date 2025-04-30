<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = [
        'code',
        'smtr'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'classroom_code', 'code');
    }

    public function diskReplacements(): HasMany
    {
        return $this->hasMany(DiskReplacement::class, 'classroom_code', 'code');
    }
}
