<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassroomController extends Controller
{
    // 學院對應表
    private $buildings = [
        'A' => '管理學院',
        'C' => '商學院',
        'D' => '電資學院',
        'E' => '工學院一館',
        'L' => '民生與設計學院'
    ];

    public function index(Request $request)
    {
        // 取得台灣時間
        $now = now()->setTimezone('Asia/Taipei');
        $weekday = $now->dayOfWeek; // 0=星期日, 1=星期一, ..., 6=星期六

        // 判斷現在是第幾節課
        $hour = (int)$now->format('H');
        $minute = (int)$now->format('i');
        
        $period = null;
        if (($hour == 8 && $minute >= 0) && ($hour == 8 && $minute <= 50)) {
            $period = 1; // 8:00-8:50
        } elseif (($hour == 9 && $minute >= 0) && ($hour == 9 && $minute <= 50)) {
            $period = 2; // 9:00-9:50
        } elseif (($hour == 10 && $minute >= 0) && ($hour == 10 && $minute <= 50)) {
            $period = 3; // 10:00-10:50
        } elseif (($hour == 11 && $minute >= 0) && ($hour == 11 && $minute <= 50)) {
            $period = 4; // 11:00-11:50
        } elseif (($hour == 13 && $minute >= 0) && ($hour == 13 && $minute <= 50)) {
            $period = 5; // 13:00-13:50
        } elseif (($hour == 13 && $minute >= 55) || ($hour == 14 && $minute <= 45)) {
            $period = 6; // 13:55-14:45
        } elseif (($hour == 14 && $minute >= 55) || ($hour == 15 && $minute <= 45)) {
            $period = 7; // 14:55-15:45
        } elseif (($hour == 15 && $minute >= 50) || ($hour == 16 && $minute <= 40)) {
            $period = 8; // 15:50-16:40
        } elseif (($hour == 16 && $minute >= 45) || ($hour == 17 && $minute <= 35)) {
            $period = 9; // 16:45-17:35
        } elseif (($hour == 17 && $minute >= 35) || ($hour == 18 && $minute <= 25)) {
            $period = 10; // 17:35-18:25
        } elseif (($hour == 18 && $minute >= 30) || ($hour == 19 && $minute <= 15)) {
            $period = 11; // 18:30-19:15
        } elseif (($hour == 19 && $minute >= 15) || ($hour == 20 && $minute <= 0)) {
            $period = 12; // 19:15-20:00
        } elseif (($hour == 20 && $minute >= 10) && ($hour == 20 && $minute <= 55)) {
            $period = 13; // 20:10-20:55
        } elseif (($hour == 20 && $minute >= 55) || ($hour == 21 && $minute <= 40)) {
            $period = 14; // 20:55-21:40
        }

        // 如果不在上課時間內，回傳空陣列
        if (is_null($period)) {
            return response()->json([]);
        }

        // 計算目前的 time 值
        $currentTime = ($weekday * 100) + $period;

        // 取得所有現在有課的教室
        $query = Classroom::whereHas('schedules', function($query) use ($currentTime) {
            $query->where('time', $currentTime);
        });

        // 如果有指定學院，則進行過濾
        $building = $request->query('building');
        if ($building && array_key_exists($building, $this->buildings)) {
            $query->where('code', 'like', $building . '%');
        }

        $classrooms = $query->get();

        // 建立回傳陣列
        $result = [];
        foreach ($classrooms as $classroom) {
            $result[$classroom->code] = 'Y';
        }

        return response()->json($result);
    }

    public function status(Request $request)
    {
        $building = $request->query('building');
        
        // 如果未指定學院，預設使用第一個學院 (管理學院 A)
        if (!$building || !array_key_exists($building, $this->buildings)) {
            return redirect()->route('classroom.status', ['building' => 'A']);
        }
        
        // 直接查詢指定學院的教室，避免不必要的資料庫查詢
        $classrooms = Classroom::where('code', 'like', $building . '%')
                              ->orderBy('code')
                              ->get();
        
        // 取得當前上課中的教室列表 (僅查詢特定學院的)
        $request->merge(['building' => $building]); // 確保 index 方法能接收到學院參數
        $response = $this->index($request);
        $busyClassrooms = json_decode($response->getContent(), true);
        
        return view('classroom.status', [
            'classrooms' => $classrooms,
            'busyClassrooms' => $busyClassrooms,
            'buildings' => $this->buildings,
            'currentBuilding' => $building
        ]);
    }

    public function showRefreshForm()
    {
        return view('classroom.refresh');
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'smtr' => 'required|numeric'
        ]);

        $smtr = $request->input('smtr');

        try {
            Schedule::query()->where('smtr', $smtr)->delete();

            // 取得所有教室
            $classrooms = Classroom::all();

            foreach ($classrooms as $classroom) {
                // 移除空白
                $roomCode = trim($classroom->code);

                // 發送請求獲取 XML
                $response = Http::get("https://cos.uch.edu.tw/course_info/classroom/roomlist.aspx", [
                    'smtr' => $smtr,
                    'room' => $roomCode
                ]);

                if ($response->successful()) {
                    // 解析 XML
                    $xml = new SimpleXMLElement($response->body());

                    foreach ($xml->room_list as $course) {
                        Schedule::create([
                            'classroom_code' => (string)$course['schd_room_id'],
                            'time' => (int)$course['schd_time'],
                            'smtr' => $smtr
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', '課表更新成功！');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', '課表更新失敗：' . $e->getMessage());
        }
    }
}
