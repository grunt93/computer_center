<?php

namespace App\Http\Controllers;

use App\Models\DiskReplacement;
use App\Models\Schedule;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiskReplacementController extends Controller
{
    public function index(Request $request)
    {
        $query = DiskReplacement::with(['user', 'classroom']);
        
        // 篩選條件
        if ($request->has('smtr') && $request->smtr) {
            $query->where('smtr', $request->smtr);
        }
        
        if ($request->has('building') && $request->building) {
            $query->where('classroom_code', 'like', $request->building . '%');
        }
        
        if ($request->has('classroom_code') && $request->classroom_code) {
            $query->where('classroom_code', 'like', $request->classroom_code . '%');
        }
        
        // 新增用戶名稱查詢
        if ($request->filled('user_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }
        
        // 新增日期篩選
        if ($request->filled('start_date')) {
            $query->whereDate('replaced_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('replaced_at', '<=', $request->end_date);
        }
        
        // 排序
        $query->orderBy('replaced_at', 'desc');
        
        $replacements = $query->paginate(15);
        
        // 取得所有學期供篩選
        $semesters = DiskReplacement::select('smtr')
                    ->distinct()
                    ->orderBy('smtr', 'desc')
                    ->pluck('smtr');
                    
        // 取得所有建築物供篩選
        $buildings = Classroom::select(DB::raw('SUBSTRING(code, 1, 1) as building'))
                    ->distinct()
                    ->pluck('building');
        
        return view('disk_placement.index', compact('replacements', 'semesters', 'buildings', 'request'));
    }

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
