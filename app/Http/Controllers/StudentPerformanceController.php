<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\ParentAppEvent, App\Models\PaymentHistory, App\Models\SessionData;
use Illuminate\Http\Request;

class StudentPerformanceController extends Controller{

    public function index(){
        return view('students.performance.index',["menu"=>"academy","sidebar"=>"performance"]);
    }

    public function session(){
        return view('students.performance.session',["menu"=>"academy","sidebar"=>"performance"]);
    }

    public function getStudents(Request $request){
        $group_id  = $request->group_id;
        $session_id  = $request->session_id;

        $records = DB::table('students as stu')->select('stu.id','stu.name','player_evaluation.status')->leftJoin("player_evaluation", function($query) use ($session_id) {
                $query->on("player_evaluation.student_id",'=','stu.id')->where("player_evaluation.session_id","=",$session_id);
            })->where("stu.group_id",$group_id)->where("stu.inactive",0)->get();

        $data["success"] = true;
        $data["students"] = $records;

        return Response::json($data, 200, array());
    }

    public function getStudentRecord(Request $request){

        $student_id  = $request->student_id;
        $session_id  = $request->session_id;

        $student = DB::table("students")->find($student_id);
        $dob = new \DateTime($student->dob);
        $now = new \DateTime();
        $playerAge = $now->diff($dob)->y;
        $student->sport_id = 1;
        $role = 5;
        $skill_categories = DB::table("skill_categories")->where('sport_id',$student->sport_id)->get();

        foreach ($skill_categories as $skill_category) {
            $attributes = DB::table("skill_attributes")->select('skill_attributes.id','skill_attributes.attribute_name as name','skill_attributes.type')
            ->where('skill_attributes.sport_id',$student->sport_id)
            ->where('skill_attributes.category_id',$skill_category->id)
            ->orderBy('priorities')
            ->get();
            foreach ($attributes as $attr) {
                $value = DB::table("player_skills")->where("student_id",$student_id)->where("session_id",$session_id)->where("skill_attribute_id",$attr->id)->pluck("attribute_value");
                if (sizeof($value) > 0) {
                    $attr->value = $value[0];
                }
            }
            $skill_category->attributes = $attributes;
        }

        $data['success'] = true;    
        $data["skill_categories"] = $skill_categories;

        return Response::json($data, 200, array());
    }

    public function saveScore(Request $request){
        $studentRecord = $request->studentRecord;
        $session_id = $request->session_id;
        $type = $request->type;
        $student_id =  $studentRecord["student_id"];

        foreach ($studentRecord["skill_categories"] as $category) {

            foreach ($category["attributes"] as $attib) {
                if (isset($attib["value"])) {
                    DB::table("player_skills")->where("student_id",$student_id)
                    ->where("session_id",$session_id)->where("skill_attribute_id",$attib["id"])
                    ->delete();

                    DB::table("player_skills")->insert([
                        "student_id" => $student_id,
                        "session_id" => $session_id,
                        "skill_attribute_id" => $attib["id"],
                        "attribute_value" => $attib["value"],
                    ]);
                }
            }
        }
        $data["success"] = true;
        $data["message"] = "Performance is successfully saved";
        return Response::json($data, 200, []);
    }

    public function updateScore(Request $request){
        $id = $request->id;
        $score = $request->score;
        foreach ($score as $item) {
            if (isset($item['score'])) {
                DB::table('student_performance')->where('student_id',$id)
                ->where('stu_performance_parm_id',$item['stu_performance_parm_id'])
                ->update([
                    "score"=>$item['score'],
                ]);
                $data['success'] = true;
                $data['message'] = "data updated successfully";
            }else{
                $data['success'] = false;
                $data['message'] = "something went wrong";
            }
        }

        return Response::json($data, 200, []);
    }

    public function getSessionList(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        $sessionList = DB::table("sessions")->where('client_id',$user->client_id)->orderBy("end_date","DESC")->get();
        foreach ($sessionList as $value) {
            $value->start_date =  date('m-d-Y',strtotime($value->start_date));
            $value->end_date =  date('m-d-Y',strtotime($value->end_date));
        }
        $data["success"] = true;
        $data["sessionList"] = $sessionList;

        return Response::json($data, 200, []);
    }

    public function addSession(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        $cre = [
            "name" => $request->name,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
        ];
        $validator = Validator::make($cre,["name"=>"required","start_date"=>"required","end_date"=>"required"]);

        if ($validator->passes()) {
           $request->start_date = date('Y-m-d',strtotime($request->start_date));
           $request->end_date = date('Y-m-d',strtotime($request->end_date));

            if(isset($request->id)) {
                $sessions = SessionData::find($request->id);
                $data["message"] = "sessions updated successfully";
            } else {
                $sessions = new SessionData();
                $sessions->client_id    = $user->client_id;
                $sessions->added_by     = $user->id;
                $data["message"] = "sessions added successfully";
            }
            $sessions->name = $request->name;
            $sessions->start_date = $request->start_date;
            $sessions->end_date = $request->end_date;
            $sessions->save();
            $data["success"] = true;

        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return Response::json($data, 200, []);
    }

    public function deleteSession(Request $request, $id){
        $user = User::AuthenticateUser($request->header("apiToken"));
        DB::table("sessions")->where("id",$id)->where('client_id',$user->client_id)->delete();
        $data['success'] = true;
        $data['message'] = "Session successfully deleted";
        return Response::json($data, 200, []);
    }

}


                 