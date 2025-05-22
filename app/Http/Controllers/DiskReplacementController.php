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
        $query = DiskReplacement::with(['classroom']);
        
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
            $query->where('user_name', 'like', '%' . $request->user_name . '%');
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
    
        // 為編輯表單準備所需資料，排除指定教室
        $classrooms = Classroom::whereNotIn('code', ['A220', 'A221', 'A319'])
                    ->orderBy('code')
                    ->get();
        $users = User::orderBy('name')->pluck('name');
        
        // 檢查用戶是否為管理員或超級管理員
        $canManage = Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin');
        
        return view('disk_placement.index', compact(
            'replacements', 
            'semesters', 
            'buildings', 
            'request',
            'classrooms',
            'users',
            'canManage'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classroom_code' => 'required|exists:classrooms,code',
        ]);

        $diskReplacement = new DiskReplacement();
        $diskReplacement->user_name = Auth::user()->name; // 儲存使用者名稱而非 ID
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
    
    /**
     * 獲取編輯硬碟更換記錄的資料
     */
    public function edit(DiskReplacement $diskReplacement)
    {
        // 返回格式化的日期，方便前端顯示
        $diskReplacement->replaced_at_formatted = $diskReplacement->replaced_at->format('Y-m-d\TH:i');
        
        return response()->json($diskReplacement);
    }

    /**
     * 更新硬碟更換記錄
     */
    public function update(Request $request, DiskReplacement $diskReplacement)
    {
        $request->validate([
            'classroom_code' => 'required|exists:classrooms,code',
            'replaced_at' => 'required|date',
            'user_name' => 'required|string|max:255',
            'smtr' => 'required|string|max:10',
        ]);

        $diskReplacement->classroom_code = $request->input('classroom_code');
        $diskReplacement->issue = $request->input('issue');
        $diskReplacement->replaced_at = $request->input('replaced_at');
        $diskReplacement->user_name = $request->input('user_name');
        $diskReplacement->smtr = $request->input('smtr');
        $diskReplacement->disk_replaced = $request->boolean('disk_replaced');
        $diskReplacement->save();

        return redirect()->route('disk-replacement.index')
            ->with('success', '硬碟更換記錄已成功更新！');
    }

    /**
     * 刪除硬碟更換記錄
     */
    public function destroy(DiskReplacement $diskReplacement)
    {
        $diskReplacement->delete();
        
        return redirect()->route('disk-replacement.index')
            ->with('success', '硬碟更換記錄已成功刪除！');
    }
}
