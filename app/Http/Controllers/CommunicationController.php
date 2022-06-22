<?php

namespace App\Http\Controllers;

use Redirect,App\Concept;
use Response,Validator;
use Illuminate\Http\Request;
use DB,App\Models\User,App\Models\Student,App\Models\Communication,App\Models\CommunicationStudent,App\Models\MailQueue;
use App\Models\Lead;

class CommunicationController extends Controller {

	public function index(){
		return view('manage.message.index',["sidebar" => "message", "only_active" => 1,"menu"=>"communication"]);
	}

	public function communication(){
		return view('manage.message.list',["sidebar" => "communication","menu"=>"communication"]);
	}

	public function init(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));

		$data['sms_templates'] = DB::table("sms_templates")->select("id as value","name as label","type")->where('client_id',$user->client_id)->get();
		$data['email_templates'] = DB::table("email_templates")->select("id as value","template_name as label")->where('client_id',$user->client_id)->get();

		$data['success'] = true;

		return Response::json($data,200,array());
	}

	public function getStudents(Request $request){
		
		$user = User::AuthenticateUser($request->header("apiToken"));
		$user_access = User::getAccess("comm_user",$user->id);
		
		$max = $request->max;
		$page_no = $request->pn;

		$groups = [];

		$removed_students = $request->removed_students;
		$excluded_student_ids = [0];
		foreach ($removed_students as $student) {
			array_push($excluded_student_ids,$student['id']);
		}

		// $only_active = ($request->only_active == 1) ? true : false;
		$only_active = true;

		$students = Student::select("students.id",'students.name','center.center_name','students.dob','students.doe')->join("groups","groups.id",'=','students.group_id')->join('center','center.id','=','groups.center_id')->whereNotIn('students.id',$excluded_student_ids)->where("students.client_id",$user->client_id);

		if($user_access->all_access){

		} else {
			$students = $students->whereIn("students.group_id",$user_access->group_ids);
		}

		$flag = false;
		if(sizeof($request->cities) > 0){
			$flag = true;
			$students = $students->whereIn("center.city_id",$request->cities);
		}
		
		if(sizeof($request->centers) > 0){
			$flag = true;
			$students = $students->whereIn("groups.center_id",$request->centers);
		}

		if(sizeof($request->groups) > 0){
			$flag = true;
			$students = $students->whereIn("students.group_id",$request->groups);
		}

		if(sizeof($request->status) > 0){
			$flag = true;
			$status = $request->status;
			$students = $students->whereIn("students.inactive",$status);
		}

		if($request->date_start){
			if($request->date_start){
				$date_str = date("Y-m-d",strtotime($request->date_start));
				$students = $students->where("students.dob",">=",$date_str);
			}
		}

		if($request->date_end){
			if($request->date_end){
				$date_str = date("Y-m-d",strtotime($request->date_end));
				$students = $students->where("students.dob","<=",$date_str);
			}
		}

		if($request->min_renew_days){
			if($request->min_renew_days != ""){
				$date_ref = strtotime($request->min_renew_days);
				$students = $students->where("students.doe",">=",$date_ref);
			}
		}

		if($request->max_renew_days){
			if($request->max_renew_days != ""){
				$date_ref = strtotime($request->max_renew_days);
				$students = $students->where("students.doe","<=",$date_ref);
			}
		}

		if($request->name){
			if($request->name != ""){
				$students = $students->where("students.name","LIKE",$request->name."%");
			}
		}

		if($flag){
			$student_ids = $students->pluck("id")->toArray();
			$count = $students->count();
			$students = $students->skip(($page_no-1)*$max)->limit($max)->orderBy("dob","ASC")->get();
		} else {
			$count = 0;
			$students = [];
			$student_ids = [];
		}

		foreach ($students as $student) {
			if($student->dob){
				$student->dob = date("d-m-Y",strtotime($student->dob));
			}
			if($student->doe){
				$student->doe = date("d-m-Y",strtotime($student->doe));
			}
		}


		$data['students'] = $students;
		$data['student_ids'] = $student_ids;
		$data['count'] = $count;
		$data["total_pn"] = ceil($count/$max);
		$data['success'] = true;

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function getContent(Request $request){
		
		$user = User::AuthenticateUser($request->header("apiToken"));
		$type = $request->type;
		$template_id = $request->template_id;

		$content = "";

		if($type == 1){
			$entry = DB::table("sms_templates")->select("template as content")->where("id",$template_id)->where("client_id",$user->client_id)->first();
		} else {
			$entry = DB::table("email_templates")->select("content")->where("id",$template_id)->where("client_id",$user->client_id)->first();
		}

		if($entry){
			$content = $entry->content;
		}

		$data['success'] = true;
		$data['content'] = $content;

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function postMessage(Request $request)
	{	
		$user = User::AuthenticateUser($request->header("apiToken"));
		
		$com = new Communication;
		$com->type = $request->type;
		$com->source_type = "students";
		$com->subject = $request->subject;
		$com->content = $request->content;
		$com->template_id = $request->template_id;
		$com->added_by = $user->id;
		$com->client_id = $user->client_id;
		$com->save();

		$student_ids = $request->student_ids;
		$removed_student_ids = $request->removed_students;

		$final_students = [];
		foreach($student_ids as $student_id){
			if(!in_array($student_id, $removed_student_ids)){
				$final_students[] = $student_id;
			}
		}

		foreach($final_students as $student_id){
			DB::table("communication_students")->insert(array(
				"communication_id" => $com->id,
				"student_id" => $student_id
			));
		}

		$data['success'] = true;
		$data['message'] = "Communication message is successfully saved";

		return Response::json($data,200,[]);

	}

	public function listing(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));
		
		$max = 100;
		$page_no = $request->pn;

		$communications = DB::table('communications')->select('communications.*','users.name')->leftJoin('users','users.id','=','communications.added_by');
		
		$count = $communications->count();

        if($request->sort_by != ""){
			if($request->sorting != ""){
				$communications = $communications->orderBy($request->sort_by, $request->sorting);
			}
		}
		
		$communications = $communications->where("communications.client_id",$user->client_id)->skip(($page_no-1)*$max)->limit($max)->orderBy("updated_at","DESC")->get();
		
		foreach ($communications as $comm) {
			if($comm->type){
				$stypes = explode(',',$comm->type);
				$arr = [];
				foreach ($stypes as $type) {
					if($type == 1){
						array_push($arr,'SMS');
					}
					if($type == 2){
						array_push($arr,'Email');
					}
				}

				$comm->send_types = implode(' , ' ,$arr);
			}


			$comm->c_date = date("d-m-Y",strtotime($comm->created_at));

			$comm->message_show = substr(strip_tags($comm->content),0,100).'..';
		}


		$data['communications'] = $communications;
		$data['count'] = $count;
		$data["total_pn"] = ceil($count/200);
		$data['success'] = true;

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function comm_students(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));
		$max = 100;
		$page_no = $request->pn;

		$students = DB::table('communication_students')->select("students.id",'students.name','center.center_name','students.dob','students.doe','groups.group_name','communication_students.status')->join("students","students.id","=","communication_students.student_id")->join("groups","groups.id",'=','students.group_id')->join('center','center.id','=','groups.center_id')->where("students.client_id",$user->client_id)->where("communication_students.communication_id",$request->id);
		$count = $students->count();

		$students = $students->skip(($page_no-1)*200)->limit(100)->get();

		foreach ($students as $student) {
			if($student->dob){
				$student->dob = date("d-m-Y",strtotime($student->dob));
			}
			if($student->doe){
				$student->doe = date("d-m-Y",strtotime($student->doe));
			}
		}
		$data['students'] = $students;
		$data['count'] = $count;
		$data["total_pn"] = ceil($count/200);
		$data['success'] = true;
		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);

	}

}
