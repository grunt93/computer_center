<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HardDiskReplacement extends Model
{
    protected $fillable = [
        'user_id',
        'classroom_id',
        'issue',
        'replaced_at'
    ];

    protected function casts(): array
    {
        return [
            'replaced_at' => 'datetime',
        ];
    }

    /**
     * 取得此更換紀錄的使用者
     * @return BelongsTo<User, HardDiskReplacement>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 取得此更換紀錄相關的教室
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
