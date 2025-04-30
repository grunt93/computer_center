<?php

namespace App\Http\Controllers;

use App\Models\DiskReplacement;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiskReplacementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'classroom_code' => 'required|exists:classrooms,code',
            'issue' => 'nullable|string',
        ]);

        $latestSchedule = Schedule::where('classroom_code', $request->classroom_code)
                                ->orderBy('smtr', 'desc')
                                ->first();
                                
        $smtr = $latestSchedule ? $latestSchedule->smtr : date('Y') . (date('n') >= 8 ? '1' : '2');
        $diskReplaced = $request->has('disk_replaced');

        DiskReplacement::create([
            'user_id' => Auth::id(),
            'classroom_code' => $request->classroom_code,
            'issue' => $request->issue,
            'replaced_at' => now(),
            'smtr' => $smtr,
            'disk_replaced' => $diskReplaced
        ]);

        return redirect()->back()->with('success', '硬碟更換記錄已儲存');
    }
}
