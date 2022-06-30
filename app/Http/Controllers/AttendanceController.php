<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\Student, App\Models\Attendance;
use App\Models\StaffAttendance;

class AttendanceController extends Controller{

    public function index(){
        User::pageAccess(13);
        return view('students.attendance.index',['menu' => "academy","sidebar"=>"attendance"]);
    }

    public function init(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("staff-attendance",$user->id, $request->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $group_id  = $request->group_id;
        $month  = $request->month;
        $year  = $request->year;

        $students = DB::table('students as stu')->select('stu.id','stu.name','stu.group_id')->where("stu.group_id",$group_id)->where("stu.inactive",0)->get();
        foreach ($students as $student) {
            $student->present = Attendance::where("student_id",$student->id)->where("group_id",$student->group_id)->where("attendance",1)->pluck("date");
            $student->absent = Attendance::select("date")->where("student_id",$student->id)->where("group_id",$student->group_id)->where("attendance",0)->pluck("date");
        }

        $start_date_ts = strtotime($year."-".$month."-01");
        $end_date_ts = strtotime("+1 month",$start_date_ts) - 86400;

        $dates = [];
        for ($date_ts = $start_date_ts; $date_ts  <= $end_date_ts ; $date_ts = $date_ts + 86400) { 
        	$dates[] = array(
        		"date" => date("Y-m-d",$date_ts),
        		"date_show" => date("d/m",$date_ts)
        	);
        }

        $data["success"] = true;
        $data["students"] = $students;
        $data["dates"] = $dates;

        return Response::json($data, 200, array());
    }

    public function initStaff(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("staff-attendance",$user->id, $request->city_id, 'city_ids');
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $city_id  = $request->city_id;
        $month  = $request->month;
        $year  = $request->year;

        $staffMembers = DB::table("users")->select('users.id','users.name','users.city_id')->where("users.city_id",$city_id)->where("client_id",$user->client_id)->where("inactive",0)->where("deleted",0)->get();
        foreach ($staffMembers as $staff) {
            $staff->present = StaffAttendance::where("user_id",$staff->id)
            ->where("city_id",$staff->city_id)->where("attendance",1)->pluck("date");

            $staff->absent = StaffAttendance::select("date")->where("user_id",$staff->id)
            ->where("city_id",$staff->city_id)->where("attendance",0)->pluck("date");
        }

        $start_date_ts = strtotime($year."-".$month."-01");
        $end_date_ts = strtotime("+1 month",$start_date_ts) - 86400;

        $dates = [];
        for ($date_ts = $start_date_ts; $date_ts  <= $end_date_ts ; $date_ts = $date_ts + 86400) { 
            $dates[] = array(
                "date" => date("Y-m-d",$date_ts),
                "date_show" => date("d/m",$date_ts)
            );
        }

        $data["success"] = true;
        $data["staffMembers"] = $staffMembers;
        $data["dates"] = $dates;

        return Response::json($data, 200, array());
    }

    public function saveAttendance(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("staff-attendance",$user->id, $request->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $students = $request->students;
        $dates = $request->dates;
        $start_date = date('Y-m-d',strtotime($dates[0]["date"]));
        $end_date = date('Y-m-d',strtotime($dates[sizeof($dates)-1]["date"]));

        foreach ($students as $student) {
            $check = Student::find($student["id"]);
            if ($check) {

                Attendance::where("student_id",$check->id)->whereBetween('date', [$start_date, $end_date])->delete();
                if (sizeof($student['absent'])) {
                    foreach ($student['absent'] as $absent) {

                        $attendance = new Attendance;
                        $attendance->student_id = $student["id"];
                        $attendance->date = date('Y-m-d',strtotime($absent));
                        $attendance->event_id = Null;
                        $attendance->group_id = $check->group_id;
                        $attendance->attendance = 0;
                        $attendance->added_by = $user->id;
                        $attendance->save();

                    }
                }

                if (sizeof($student['present'])) {

                    foreach ($student['present'] as $present) {

                        $attendance = new Attendance;
                        $attendance->student_id = $student["id"];
                        $attendance->date = date('Y-m-d',strtotime($present));
                        $attendance->event_id = Null;
                        $attendance->group_id = $check->group_id;
                        $attendance->attendance = 1;
                        $attendance->added_by = $user->id;
                        $attendance->save();

                    }
                    
                }

            }
        }

        $data ["success"] = true;
        $data ["message"] = "Attendance marked successfully";

        return Response::json($data, 200, []);
    }


    public function saveStaffAttendance(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("staff-attendance",$user->id, $request->city_id, 'city_ids');
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $staffMembers = $request->staffMembers;
        $dates = $request->dates;
        $start_date = date('Y-m-d',strtotime($dates[0]["date"]));
        $end_date = date('Y-m-d',strtotime($dates[sizeof($dates)-1]["date"]));

        foreach ($staffMembers as $staff) {
            $check = User::find($staff["id"]);
            if ($check) {

                StaffAttendance::where("user_id",$check->id)->whereBetween('date', [$start_date, $end_date])->delete();
                if (sizeof($staff['absent'])) {
                    foreach ($staff['absent'] as $absent) {

                        $attendance = new StaffAttendance;
                        $attendance->user_id = $staff["id"];
                        $attendance->date = date('Y-m-d',strtotime($absent));
                        $attendance->city_id = $staff["city_id"];
                        $attendance->attendance = 0;
                        $attendance->added_by = $user->id;
                        $attendance->save();

                    }
                }

                if (sizeof($staff['present'])) {

                    foreach ($staff['present'] as $present) {

                        $attendance = new StaffAttendance;
                        $attendance->user_id = $staff["id"];
                        $attendance->date = date('Y-m-d',strtotime($present));
                        $attendance->city_id = $staff["city_id"];
                        $attendance->attendance = 1;
                        $attendance->added_by = $user->id;
                        $attendance->save();

                    }
                    
                }

            }
        }

        $data ["success"] = true;
        $data ["message"] = "Attendance marked successfully";

        return Response::json($data, 200, []);
    }

}