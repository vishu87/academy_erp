<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, Request;
use App\Models\User, App\Models\ParentAppEvent, App\Models\PaymentHistory;

class StudentPerformanceController extends Controller{

    public function index(){
        return view('students.performance.index',["menu"=>"academy","sidebar"=>"performance"]);
    }

    public function session(){
        return view('students.performance.session',["menu"=>"academy","sidebar"=>"performance"]);
    }

    public function getStudents(){
        $group_id  = Input::get('group_id');
        $session_id  = Input::get('session_id');

        $records = DB::table('students as stu')->select('stu.id','stu.name','player_evaluation.status')->leftJoin("player_evaluation", function($query) use ($session_id) {
                $query->on("player_evaluation.student_id",'=','stu.id')->where("player_evaluation.session_id","=",$session_id);
            })->where("stu.group_id",$group_id)->where("stu.inactive",0)->get();

        $data["success"] = true;
        $data["students"] = $records;

        return Response::json($data, 200, array());
    }

    public function getStudentRecord(){

        $student_id  = Input::get('student_id');
        $session_id  = Input::get('session_id');

        $student = DB::table("students")->find($student_id);
        $dob = new \DateTime($student->dob);
        $now = new \DateTime();
        $playerAge = $now->diff($dob)->y;

        $student->sport_id = 1;

        // $playerAgeGroup = DB::table("performance_age_groups")->select('id')->where('min_age','<=',$playerAge)->where('max_age','>=',$playerAge)->first();
        // $age_group_id = $playerAgeGroup->id;

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

    public function saveScore(){
        $studentRecord = Input::get("studentRecord");
        $session_id = Input::get('session_id');
        $type = Input::get('type');
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

    public function updateScore(){
        $id = Input::get('id');
        $score = Input::get('score');
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

    public function getSessionList(){
        $sessionList = DB::table("sessions")->orderBy("end_date","DESC")->get();
        foreach ($sessionList as $value) {
            $value->start_date =  date('m-d-Y',strtotime($value->start_date));
            $value->end_date =  date('m-d-Y',strtotime($value->end_date));
        }
        $data["success"] = true;
        $data["sessionList"] = $sessionList;

        return Response::json($data, 200, []);
    }

    public function addSession(){
        $sessionData = Input::get("session");
        $cre = [
            "name" => $sessionData["name"],
            "start_date" => $sessionData["start_date"],
            "end_date" => $sessionData["end_date"],
        ];
        $validator = Validator::make($cre,["name"=>"required","start_date"=>"required","end_date"=>"required"]);

        if ($validator->passes()) {
            $sessionData["start_date"] = date('Y-m-d',strtotime($sessionData["start_date"]));
            $sessionData["end_date"] = date('Y-m-d',strtotime($sessionData["end_date"]));

            if (isset($sessionData["id"])) {
                DB::table('sessions')->where('id',$sessionData["id"])->update([
                    "name"=>$sessionData["name"],
                    "start_date"=>$sessionData["start_date"],
                    "end_date"=>$sessionData["end_date"],
                ]);   
                $data["message"] = "sessions updated successfully";
            }else{
                DB::table('sessions')->insert([
                    "name"=>$sessionData["name"],
                    "start_date"=>$sessionData["start_date"],
                    "end_date"=>$sessionData["end_date"],
                ]);   
                $data["message"] = "sessions added successfully";
            }
 
            $data["success"] = true;
        }else{
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return Response::json($data, 200, []);
    }

    public function deleteSession(){
        $id = Input::get("id");
        $check = DB::table("sessions")->find($id);
        if ($check) {
            DB::table("sessions")->where("id",$id)->delete();
            $data['success'] = true;
            $data['message'] = "session deleted successfully";
        }else{
            $data['success'] = false;
            $data['message'] = "Item does not exist";
        }

        return Response::json($data, 200, []);
        
    }

}


                 