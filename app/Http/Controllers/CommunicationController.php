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

				$students = DB::table('students')->select('students.id','students.email','students.mobile','student_guardians.email as guardian_email','student_guardians.mobile as guardian_mob')
				->leftJoin('student_guardians','student_guardians.student_id','=','students.id')->whereIn('students.id',$final_students);

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

						// if($student->father_mob && !in_array($student->father_mob,$mobiles)){
						// 	MailQueue::createSMS($student->father_mob, $sms_content,$request->sms_type,$request->template_id);
						// 	$mobiles[] = $student->father_mob;
						// }

						// if($student->mother_mob && !in_array($student->mother_mob,$mobiles)){
						// 	MailQueue::createSMS($student->mother_mob, $sms_content,$request->sms_type,$request->template_id);
						// }
					}

					if(in_array(2, $types)){

						$message = Lead::getContent($request->content);

						if($student->email){
							MailQueue::createMail($student->email, null, null, $request->subject, $message);
							$emails[] = $student->email;
						}
						// if($student->father_email && !in_array($student->father_email,$emails)){
						// 	MailQueue::createEmail($student->father_email,$request->subject,  $message);
						// 	$emails[] = $student->father_email;
						// }
						// if($student->mother_email && !in_array($student->mother_email,$emails)){
						// 	MailQueue::createEmail($student->mother_email,$request->subject,  $message);
						// }
					}
				}

				$data['success'] = true;
				$data['message'] = "Communication message has been sent successfully";

			}

		}

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

}
