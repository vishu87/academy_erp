<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\ParentAppEvent, App\Models\PaymentHistory, App\Models\SessionData, App\Models\PlayerEvaluation, App\Models\Utilities, App\Models\Student, App\Models\MailQueue;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class StudentPerformanceController extends Controller{

    public function index(){
        return view('students.performance.index',["menu"=>"academy","sidebar"=>"performance"]);
    }

    public function session(){
        return view('students.performance.session',["menu"=>"academy","sidebar"=>"performance"]);
    }

    public function getStudents(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $group_id  = $request->group_id;
        $session_id  = $request->session_id;

        $records = DB::table('students as stu')->select('stu.id','stu.name','player_evaluation.status','player_evaluation.uuid','player_evaluation.mailed')->leftJoin("player_evaluation", function($query) use ($session_id) {
                $query->on("player_evaluation.student_id",'=','stu.id')->where("player_evaluation.session_id","=",$session_id);
            })->where("stu.group_id",$group_id)->where("stu.inactive",0)->where("stu.client_id",$user->client_id)->get();

        $data["success"] = true;
        $data["students"] = $records;

        return Response::json($data, 200, array());
    }

    public function getStudentRecord(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $student_id  = $request->student_id;
        $session_id  = $request->session_id;

        $student = DB::table("students")->where("client_id",$user->client_id)->where("students.id",$student_id)->first();
        $dob = new \DateTime($student->dob);
        $now = new \DateTime();
        $playerAge = $now->diff($dob)->y;
        $student->sport_id = 1;

        $skill_categories = DB::table("skill_categories")->select("id","category_name")->where('sport_id',$student->sport_id)->where("skill_categories.client_id",$user->client_id)->get();

        foreach ($skill_categories as $skill_category) {
            
            $attributes = DB::table("skill_attributes")->select('skill_attributes.id','skill_attributes.attribute_name as name','skill_attributes.type')
            ->where('skill_attributes.sport_id',$student->sport_id)
            ->where('skill_attributes.category_id',$skill_category->id)
            ->orderBy('priorities')
            ->get();

            foreach ($attributes as $attr) {
                $value = DB::table("player_skills")->where("student_id",$student_id)->where("session_id",$session_id)->where("skill_attribute_id",$attr->id)->first();
                if ($value) {
                    $attr->value = $value->value;
                    $attr->remarks = $value->remarks;
                }
            }
            $skill_category->attributes = $attributes;
        }

        $entry = PlayerEvaluation::where("student_id",$student_id)->where("session_id",$session_id)->first();
        if($entry){
            $mailed = $entry->mailed;
            $uuid = $entry->uuid;
        } else {
            $mailed = 0;
            $uuid = "";
        }

        $data['success'] = true;    
        $data["skill_categories"] = $skill_categories;
        $data["mailed"] = $mailed;
        $data["uuid"] = $uuid;

        return Response::json($data, 200, array());
    }

    public function saveScore(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $studentRecord = $request->studentRecord;
        $session_id = $request->session_id;
        $type = $request->type;
        $student_id =  $studentRecord["student_id"];

        $entry = PlayerEvaluation::where("student_id",$student_id)->where("session_id",$session_id)->first();
        if(!$entry){
            $entry = new PlayerEvaluation;
            $uniqid = Utilities::getUniqueInTable("player_evaluation", "uuid");
            $entry->uuid = $uniqid;
            $entry->student_id = $student_id;
            $entry->session_id = $session_id;
            $entry->created_by = $user->id;
            $entry->sport_id = 1;
        }

        DB::table("player_skills")->where("student_id",$student_id)->where("session_id",$session_id)->delete();

        $store_results = [];
        foreach ($studentRecord["skill_categories"] as $category) {

            foreach ($category["attributes"] as $attib) {
                if($attib["type"] == 1){
                    if(isset($attib["value"])){
                        $store_results[] = [
                            "student_id" => $student_id,
                            "session_id" => $session_id,
                            "skill_attribute_id" => $attib["id"],
                            "value" => $attib["value"],
                            "remarks" => isset($attib["remarks"]) ? $attib["remarks"] : null,
                        ];
                    }
                } else {
                    if(isset($attib["remarks"])){
                        $store_results[] = [
                            "student_id" => $student_id,
                            "session_id" => $session_id,
                            "skill_attribute_id" => $attib["id"],
                            "value" => 0,
                            "remarks" => isset($attib["remarks"]) ? $attib["remarks"] : null,
                        ];
                    }
                }
            }
        }

        DB::table("player_skills")->insert($store_results);

        $entry->modified_by = $user->id;
        $entry->save();

        $data["success"] = true;
        $data["message"] = "Performance is successfully saved";
        $data["uuid"] = $entry->uuid;
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

    public function performancePDF($eval_code){

        $response = $this->PDFCreate($eval_code);

        if(!$response["success"]){
            return $response["message"];
        }

        return $response["pdf"]->stream();
        die();

    }

    public function mailPerformancePDF(Request $request){

        $eval_code = $request->uuid;

        $user = User::AuthenticateUser($request->header("apiToken"));

        $response = $this->PDFCreate($eval_code);

        if(!$response["success"]){
            $data["success"] = false;
            $data["message"] = $response["message"];
        }

        $performance = PlayerEvaluation::select("player_evaluation.*","sessions.name as session_name")->join("sessions","sessions.id","=","player_evaluation.session_id")->where("player_evaluation.uuid",$eval_code)->first();
        $student_emails = Student::getContactDetails("email",$performance->student_id);
        $student = Student::listing()->where("students.id",$performance->student_id)->first();

        if(sizeof($student_emails) > 0){

            $destination = "uploads/";
            $filename = $destination.Utilities::cleanName($student->name."_".date("YmdHis")).".pdf";

            $pdf = $response["pdf"];
            $pdf->save($filename);

            $performance->pdf_file = $filename;
            $performance->mailed = 1;
            $performance->mailed_at = date("Y-m-d H:i:s");
            $performance->mailed_by = $user->id;
            $performance->save();

            $mail = new MailQueue;
            $mail->mailto = implode(', ', $student_emails);
            $mail->subject = "Performance Record";
            $mail->content = "content";
            $mail->at_file = $filename;
            $mail->tb_name = "player_evaluation";
            $mail->tb_id = $performance->id;
            $mail->student_id = $performance->student_id;
            $mail->user_id = $user->id;
            $mail->client_id = $user->client_id;
            $mail->save();

            $data["success"] = true;
            $data["message"] = "Email is successfully sent to ".implode(', ', $student_emails);

        } else {
            $data["success"] = false;
            $data["message"] = "No emails found for this student profile";
        }

        return Response::json($data, 200, []);

    }

    private function PDFCreate($eval_code){

        $performance = PlayerEvaluation::select("player_evaluation.*","sessions.name as session_name")->join("sessions","sessions.id","=","player_evaluation.session_id")->where("player_evaluation.uuid",$eval_code)->first();
        if(!$performance){
            return [
                "success" => false,
                "message" => "No performance found"
            ];
        }

        $student = Student::listing()->where("students.id",$performance->student_id)->first();

        $category_ids = [];
        $player_skills = DB::table("player_skills")->select("player_skills.skill_attribute_id","skill_attributes.attribute_name","player_skills.value","player_skills.remarks","skill_categories.category_name","skill_attributes.category_id","skill_attributes.type")->join("skill_attributes","skill_attributes.id","=","player_skills.skill_attribute_id")->join("skill_categories","skill_categories.id","=","skill_attributes.category_id")->where("session_id",$performance->session_id)->where("student_id",$performance->student_id)->get();
        foreach($player_skills as $player_skill){
            $category_ids[] = $player_skill->category_id;
        }

        $categories = DB::table("skill_categories")->whereIn("id",$category_ids)->get();
        foreach($categories as $category){
            $attributes = [];
            foreach($player_skills as $player_skill){
                if($player_skill->category_id == $category->id){
                    $attributes[] = $player_skill;
                }
            }
            $category->attributes = $attributes;
        }
        

        $pdf = PDF::loadView('students.performance.pdf',['student' => $student, 'categories' => $categories, "performance" => $performance]);
        return [
            "success" => true,
            "pdf" => $pdf
        ];

    }

}


                 