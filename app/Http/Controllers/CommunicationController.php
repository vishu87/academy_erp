<?php

namespace App\Http\Controllers;

use Redirect,App\Concept;
use Response,Validator;
use Illuminate\Http\Request;
use DB,App\Models\User,App\Models\Student,App\Models\Communication,App\Models\CommunicationStudent,App\Models\MailQueue;
use App\Lead;

class CommunicationController extends Controller {

	public function index(){
		return view('manage.message.index',["sidebar" => "message", "only_active" => 1,"menu"=>"communication"]);
	}

	public function init(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));

		$only_active = ($request->only_active == 1) ? true : false;
		
		$data['cities'] = DB::table('city')->select("id","city_name")->orderBy('city_name')->get();
		
		$centers = DB::table('center')->select("id","center_name","city_id","center_status")->orderBy('center_status')->orderBy('center_name');
		if($only_active){
			$centers = $centers->where("center.center_status",0);
		}
		$centers = $centers->get();

		$groups = DB::table('groups')->select('groups.id','group_name','center.center_name','groups.center_id','groups.group_status')->join('center','groups.center_id','=','center.id');
		if($only_active){
			$groups = $groups->where("groups.group_status",0);
		}
		$groups = $groups->get();

		$data['student_categories'] = DB::table('student_categories')->distinct('category')->orderBy("category")->pluck('category')->all();

		$data['batch_types'] = array(
			array( "id" => 1, "name" =>"Foundation" ),
			array( "id" => 2, "name" =>"Development" ),
			array( "id" => 3, "name" =>"Residential" )
		);

		$data['centers'] = $centers;
		$data['groups'] = $groups;
		$data['templates'] = DB::table("sms_templates")->get();
		$data['success'] = true;

		return Response::json($data,200,array());
	}

	public function getStudents(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));
		$max = 100;
		$page_no = $request->pn;

		$groups = [];

		$removed_students = $request->removed_students;
		$excluded_student_ids = [0];
		foreach ($removed_students as $student) {
			array_push($excluded_student_ids,$student['id']);
		}

		$only_active = ($request->only_active == 1) ? true : false;

		$students = Student::select("students.id",'students.name','center.center_name','students.dob','students.doe')->join("groups","groups.id",'=','students.group_id')->join('center','center.id','=','groups.center_id')->whereNotIn('students.id',$excluded_student_ids);

		if($only_active){
			$students = $students->where("groups.group_status",0)->where("center.center_status",0);
		}

		$flag = false;
		if(sizeof($request->cities) > 0){
			$flag = true;
			$centers = DB::table('center')->whereIn('city_id',$request->cities)->pluck('id')->all();
			$groups = DB::table('groups')->whereIn("center_id",$centers)->pluck('id')->all();
		}
		
		if(sizeof($request->centers) > 0){
			$flag = true;
			$centers = $request->centers;
			$groups =  DB::table('groups')->whereIn("center_id",$request->centers)->pluck('id')->all();
		}

		if(sizeof($request->groups) > 0){
			$flag = true;
			$groups = $request->groups;
		}

		// if(sizeof($request->categories) > 0){
		// 	$flag = true;
		// 	$categories = DB::table('student_categories')->whereIn('category',$request->categories);

		// 	if(isset($centers)){
		// 		$categories = $categories->whereIn('center_id',$centers);
		// 	}

		// 	$categories = $categories->pluck('id')->all();

		// 	if(sizeof($categories) == 0){
		// 		$categories = [0];
		// 	}
		// 	$students = $students->whereIn('students.category_id',$categories)->where('students.category_id','!=',0);
		// }

		if(sizeof($groups) > 0){
			$flag = true;
			$students = $students->whereIn("students.group_id",$groups);
		}

		if(sizeof($request->batch_types) > 0){
			$flag = true;
			$students = $students->whereIn("groups.group_type_id",$request->batch_types);
		}

		if(sizeof($request->status) > 0){
			$flag = true;
			$status = $request->status;
			if(in_array(0, $request->status)){
				$status[] = -1;
			}

			$students = $students->whereIn("students.inactive",$status);
		}

		if($request->date_start){
			if($request->date_start){
				$date_str = strtotime($request->date_start);
				$students = $students->where("students.dob",">=",$date_str);
			}
		}

		if($request->date_end){
			if($request->date_end){
				$date_str = strtotime($request->date_end);
				$students = $students->where("students.dob","<=",$date_str);
			}
		}

		if($request->paused){
			if($request->paused == 1){
				$students = $students->where("paused",1);
			} elseif($request->paused == 2){
				$students->where("paused",0);
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

		if($request->downloaded_app){
			if($request->downloaded_app == 1){
				$students = $students->join("webserver_studentcustomermapping","webserver_studentcustomermapping.student_id","=","students.id");
			} else if($request->downloaded_app == 2){
				$student_dw_ids = DB::table("webserver_studentcustomermapping")->pluck("student_id")->toArray();
				if(sizeof($student_dw_ids) > 0){
					$students = $students->whereNotIn("students.id",$student_dw_ids);
				}
			}
		}

		if($request->mobile){
			if($request->mobile != ""){
				$mobiles = explode(',', $request->mobile);
				$mobile_numbers = [];
				foreach ($mobiles as $mob) {
					if(strlen($mob) >= 10){
						$mobile_numbers[] = trim($mob);
					}
				}
				
				$students = $students->where(function($query) use ($mobile_numbers){
					$query->whereIn("mobile",$mobile_numbers)->orWhereIn("father_mob",$mobile_numbers)->orWhereIn("mother_mob",$mobile_numbers);
				});
			}
		}

		if($flag){

			$student_ids = $students->pluck("id")->toArray();
			
			$count = $students->count();

	        if($request->sort_by != ""){
				if($request->sorting != ""){
					$students = $students->orderBy($request->sort_by, $request->sorting);
				}
			}
			
			$students = $students->skip(($page_no-1)*200)->limit(200)->orderBy("dob","ASC")->get();

		} else {
			$count = 0;
			$students = [];
			$student_ids = [];
		}

		foreach ($students as $student) {
			if($student->father_mob){
				$student->mobile_trimmed = "xxxxxx".substr($student->father_mob, 6,4);
			} elseif ($student->mother_mob) {

				$student->mobile_trimmed = "xxxxxx".substr($student->mother_mob, 6,4);
			}
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
		$data["total_pn"] = ceil($count/200);
		$data['success'] = true;

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function postMessage(Request $request)
	{	
		$user = User::AuthenticateUser($request->header("apiToken"));

		$send_type = $request->send_type;
		$types = [];
		foreach ($send_type as $key => $value) {
			if($value){
				array_push($types,$key);
			}
		}

		$sms_content = $request->sms_content;
		

		if(in_array(1,$types)){

			$template = DB::table("sms_templates")->where('id',$request->template_id)->first();
			$sms_content = '';
			if($template){

				$sms_content = $template->template;
				$sms_content = str_replace('{#var1#}',$request->variable1, $sms_content);
				$sms_content = str_replace('{#var2#}',$request->variable2, $sms_content);
				$sms_content = str_replace('{#var3#}',$request->variable3, $sms_content);
			}
		}

		if($request->demo_check){
			$data['success'] = true;

			$demo_mobile = $request->demo_mobile;
			$demo_email = $request->demo_email;

			if($demo_mobile && $template){
				// Lead::sendSMS($demo_mobile , $request->sms_content,$request->sms_type);
				Lead::sendSMS($demo_mobile , $sms_content, $request->sms_type, $template->dlt_template_id, $template->dlt_sender_id);
			}

			if($demo_email){
				Lead::sendEmail($demo_email, $request->subject, $request->content);
			}

			$data['message'] = "Demo message has been send successfully";
			$data['demo_check'] = true;

		} else {

			

			if(sizeof($types) == 0){
				$data['success'] = false;
				$data['message'] = "Please select either sms or email";
			} else {
				
				$com = new Communication;
				$com->send_type = implode(',',$types);
				$com->sms_type = $request->sms_type;
				$com->message = $request->content;
				$com->subject = $request->subject;
				$com->source_type = 1;
				$com->added_by = $user->id;


				if(in_array(1,$types)){
					$com->template_id = $request->template_id;
					$com->variable1 = $request->variable1;
					$com->variable2 = $request->variable2;
					$com->variable3 = $request->variable3;
					
				}

				$com->sms_content = $sms_content;
				$com->save();

				$filters = $request->filters;

				$student_ids = $request->student_ids;
				$removed_students = $request->removed_students;

				$removed_student_ids = [0];
				foreach ($removed_students as $rst) {
					array_push($removed_student_ids, $rst['id']);
				}


				$final_students = [];
				foreach($student_ids as $student_id){
					if(!in_array($student_id, $removed_student_ids)){
						$final_students[] = $student_id;
					}
				}

				if(sizeof($final_students) == 0) {
					$final_students = [0];
				}

				$students = Student::select('id','father_mob','mother_mob','father_email','mother_email','email','mobile')->whereIn('students.id',$final_students);

				$students = $students->get();

				foreach ($students as $student) {
					
					$student_data = ["communication_id"=>$com->id,"student_id"=>$student->id];
					CommunicationStudent::insert($student_data);

					$mobiles = [];
					$emails = [];

					if(in_array(1, $types)){
						if($student->mobile){
							MailQueue::createSMS($student->mobile, $sms_content,$request->sms_type,$request->template_id);
							$mobiles[] = $student->mobile;
						}

						if($student->father_mob && !in_array($student->father_mob,$mobiles)){
							MailQueue::createSMS($student->father_mob, $sms_content,$request->sms_type,$request->template_id);
							$mobiles[] = $student->father_mob;
						}

						if($student->mother_mob && !in_array($student->mother_mob,$mobiles)){
							MailQueue::createSMS($student->mother_mob, $sms_content,$request->sms_type,$request->template_id);
						}
					}

					if(in_array(2, $types)){

						$message = Lead::getContent($request->content);

						if($student->email){
							MailQueue::createEmail($student->email,$request->subject,  $message);
							$emails[] = $student->email;
						}
						if($student->father_email && !in_array($student->father_email,$emails)){
							MailQueue::createEmail($student->father_email,$request->subject,  $message);
							$emails[] = $student->father_email;
						}
						if($student->mother_email && !in_array($student->mother_email,$emails)){
							MailQueue::createEmail($student->mother_email,$request->subject,  $message);
						}
					}
				}

				$data['success'] = true;
				$data['message'] = "Communication message has been sent successfully";

			}

		}

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);

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
		
		$communications = $communications->skip(($page_no-1)*$max)->limit($max)->orderBy("updated_at","DESC")->get();
		
		foreach ($communications as $comm) {
			if($comm->send_type){
				$stypes = explode(',',$comm->send_type);
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

			if($comm->sms_type){
				$stypes = explode(',',$comm->sms_type);
				$arr = [];
				foreach ($stypes as $type) {
					if($type == 1){
						array_push($arr,'Promotional');
					}
					if($type == 2){
						array_push($arr,'Transactional');
					}
				}

				$comm->sms_types = implode(' , ' ,$arr);
			}

			$comm->c_date = date("d-m-Y",strtotime($comm->created_at));

			if($comm->sms_content){
				$comm->message_show = substr(strip_tags($comm->sms_content),0,100).'..';
			} else {
				$comm->message_show = substr(strip_tags($comm->message),0,100).'...';
			}
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

		$student_ids = DB::table('communication_students')->where('communication_id',$request->id)->pluck('student_id')->toArray();
		$students = Student::select("students.id",'students.name','center.center_name','students.dob','students.doe','groups.group_name')->join("groups","groups.id",'=','students.group_id')->join('center','center.id','=','groups.center_id')->whereIn('students.id',$student_ids);
		$count = $students->count();

		$students = $students->skip(($page_no-1)*200)->limit(100)->get();

		foreach ($students as $student) {
			if($student->father_mob){
				$student->mobile_trimmed = "xxxxxx".substr($student->father_mob, 6,4);
			} elseif ($student->mother_mob) {

				$student->mobile_trimmed = "xxxxxx".substr($student->mother_mob, 6,4);
			}
			if($student->dob){
				$student->dob = date("d-m-Y",$student->dob);
			}
			if($student->doe){
				$student->doe = date("d-m-Y",$student->doe);
			}
		}
		$data['students'] = $students;
		$data['count'] = $count;
		$data["total_pn"] = ceil($count/200);
		$data['success'] = true;
		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);

	}

	public function communication(){
		return view('manage.message.list',["sidebar" => "communication","menu"=>"communication"]);
	}

}
