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
