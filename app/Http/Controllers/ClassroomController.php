<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    private $buildings = [
        'A' => '管理學院',
        'C' => '商學院',
        'D' => '電資學院',
        'E' => '工學院一館',
        'L' => '民生與設計學院'
    ];

    public function index(Request $request)
    {
        $now = now()->setTimezone('Asia/Taipei');
        $weekday = $now->dayOfWeek;
        $hour = (int)$now->format('H');
        $minute = (int)$now->format('i');
        
        $period = null;
        if (($hour == 8 && $minute >= 0) && ($hour == 8 && $minute <= 50)) {
            $period = 1;
        } elseif (($hour == 9 && $minute >= 0) && ($hour == 9 && $minute <= 50)) {
            $period = 2;
        } elseif (($hour == 10 && $minute >= 0) && ($hour == 10 && $minute <= 50)) {
            $period = 3;
        } elseif (($hour == 11 && $minute >= 0) && ($hour == 11 && $minute <= 50)) {
            $period = 4;
        } elseif (($hour == 13 && $minute >= 0) && ($hour == 13 && $minute <= 50)) {
            $period = 5;
        } elseif (($hour == 13 && $minute >= 55) || ($hour == 14 && $minute <= 45)) {
            $period = 6;
        } elseif (($hour == 14 && $minute >= 55) || ($hour == 15 && $minute <= 45)) {
            $period = 7;
        } elseif (($hour == 15 && $minute >= 50) || ($hour == 16 && $minute <= 40)) {
            $period = 8;
        } elseif (($hour == 16 && $minute >= 45) || ($hour == 17 && $minute <= 35)) {
            $period = 9;
        } elseif (($hour == 17 && $minute >= 35) || ($hour == 18 && $minute <= 25)) {
            $period = 10;
        } elseif (($hour == 18 && $minute >= 30) || ($hour == 19 && $minute <= 15)) {
            $period = 11;
        } elseif (($hour == 19 && $minute >= 15) || ($hour == 20 && $minute <= 0)) {
            $period = 12;
        } elseif (($hour == 20 && $minute >= 10) && ($hour == 20 && $minute <= 55)) {
            $period = 13;
        } elseif (($hour == 20 && $minute >= 55) || ($hour == 21 && $minute <= 40)) {
            $period = 14;
        }

        if (is_null($period)) {
            return response()->json([]);
        }

        $currentTime = ($weekday * 100) + $period;

        $query = Classroom::whereHas('schedules', function($query) use ($currentTime) {
            $query->where('time', $currentTime);
        });

        $building = $request->query('building');
        if ($building && array_key_exists($building, $this->buildings)) {
            $query->where('code', 'like', $building . '%');
        }

        $classrooms = $query->get();

        $result = [];
        foreach ($classrooms as $classroom) {
            $result[$classroom->code] = 'Y';
        }

        return response()->json($result);
    }

    public function status(Request $request)
    {
        $building = $request->query('building');
        
        if (!$building || !array_key_exists($building, $this->buildings)) {
            return redirect()->route('classroom.status', ['building' => 'A']);
        }
        
        $classrooms = Classroom::where('code', 'like', $building . '%')
                              ->orderBy('code')
                              ->get();
        
        $request->merge(['building' => $building]);
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
            $classrooms = Classroom::all();

            foreach ($classrooms as $classroom) {
                $roomCode = trim($classroom->code);
                $response = Http::get("https://cos.uch.edu.tw/course_info/classroom/roomlist.aspx", [
                    'smtr' => $smtr,
                    'room' => $roomCode
                ]);

                if ($response->successful()) {
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
