<?php

namespace App\Http\Controllers;

use App\Models\DiskReplacement;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiskReplacementController extends Controller
{
    public function index(Request $request)
    {
        $query = DiskReplacement::with(['user', 'classroom']);
        
        if ($request->has('smtr') && $request->smtr) {
            $query->where('smtr', $request->smtr);
        }
        
        if ($request->has('building') && $request->building) {
            $query->where('classroom_code', 'like', $request->building . '%');
        }
        
        if ($request->has('classroom_code') && $request->classroom_code) {
            $query->where('classroom_code', 'like', $request->classroom_code . '%');
        }
        
        if ($request->filled('user_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('replaced_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('replaced_at', '<=', $request->end_date);
        }
        
        $query->orderBy('replaced_at', 'desc');
        
        $replacements = $query->paginate(15);
        
        $semesters = DiskReplacement::select('smtr')
                    ->distinct()
                    ->orderBy('smtr', 'desc')
                    ->pluck('smtr');
                    
        $buildings = Classroom::select(DB::raw('SUBSTRING(code, 1, 1) as building'))
                    ->distinct()
                    ->pluck('building');
        
        return view('disk_placement.index', compact('replacements', 'semesters', 'buildings', 'request'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classroom_code' => 'required|exists:classrooms,code',
        ]);

        $diskReplacement = new DiskReplacement();
        $diskReplacement->user_id = Auth::user()->id;
        $diskReplacement->classroom_code = $request->input('classroom_code');
        
        $diskReplacement->issue = $request->input('issue');
        
        $diskReplacement->replaced_at = now();
        $diskReplacement->smtr = Schedule::select('smtr')
                        ->orderBy('created_at', 'desc')
                        ->first()->smtr ?? date('Y') . (date('n') >= 8 ? '1' : '2');
        $diskReplacement->disk_replaced = $request->boolean('disk_replaced');
        $diskReplacement->save();

        $queryParams = [
            'building' => $request->input('building', 'A'),
            'filter_date' => $request->input('filter_date', now()->format('Y-m-d')),
            'need_replacement' => $request->boolean('need_replacement') ? 1 : 0
        ];
        
        // 表單中的 classroom_code，提取其樓層，用於定位
        $classroomCode = $request->input('classroom_code');
        if ($classroomCode) {
            $floor = substr($classroomCode, 1, 1);  // 從教室代碼提取樓層
            $queryParams['floor'] = $floor;
        }

        return redirect()->route('classroom.status', $queryParams)
            ->with('success', '硬碟更換記錄已儲存！');
    }
}
