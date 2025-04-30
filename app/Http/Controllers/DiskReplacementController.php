<?php

namespace App\Http\Controllers;

use App\Models\DiskReplacement;
use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiskReplacementController extends Controller
{
    /**
     * 建立新的硬碟更換記錄
     */
    public function store(Request $request)
    {
        $request->validate([
            'classroom_code' => 'required|exists:classrooms,code',
            'issue' => 'required|string',
        ]);

        // 獲取該教室最新的學期資訊
        $latestSchedule = Schedule::where('classroom_code', $request->classroom_code)
                                ->orderBy('smtr', 'desc')
                                ->first();
                                
        // 如果找不到學期資訊，使用當前學年學期
        $smtr = $latestSchedule ? $latestSchedule->smtr : date('Y') . (date('n') >= 8 ? '1' : '2');

        DiskReplacement::create([
            'user_id' => Auth::id(),
            'classroom_code' => $request->classroom_code,
            'issue' => $request->issue,
            'replaced_at' => now(),
            'smtr' => $smtr,
            'disk_replaced' => $request->has('disk_replaced')
        ]);

        return redirect()->back()->with('success', '硬碟更換記錄已儲存');
    }
}
