<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'code',
        'smtr',
        'time'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'smtr' => 'integer',
            'time' => 'integer'
        ];
    }

    /**
     * 取得此教室的所有硬碟更換紀錄
     */
    public function hardDiskReplacements(): HasMany
    {
        return $this->hasMany(HardDiskReplacement::class);
    }
}
