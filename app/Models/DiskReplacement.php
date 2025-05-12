<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiskReplacement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_name',
        'classroom_code',
        'issue',
        'replaced_at',
        'smtr',
        'disk_replaced'
    ];

    protected $casts = [
        'replaced_at' => 'datetime',
        'disk_replaced' => 'boolean'
    ];

    // 移除 user 關聯方法，因為不再使用 user_id
    // 改為直接儲存 user_name

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_code', 'code');
    }
}
