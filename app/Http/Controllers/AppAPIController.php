<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB, Cache, Auth;

use Illuminate\Http\Request;

use App\Models\User, App\Models\Center, App\Models\Student, App\Models\Utilities;

class AppAPIController extends Controller {

	public function login(Request $request){

		$credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($credentials, $rules);
        if ($validator->passes()) {
        	
			if(Auth::attempt($credentials)){

				$user = new \stdClass;
				$user->username = Auth::user()->username;
				$user->apiToken = Auth::user()->api_key;
				$user->name = Auth::user()->name;
				$user->email = Auth::user()->email;
				$user->mobile = Auth::user()->mobile;
				$user->is_admin = Auth::user()->is_admin;

	        	$data["success"] = true;
	            $data["user"] = $user;
                
			} else {
				$data["success"] = false;
	            $data["message"] ="Username & password is invalid";
			}

        } else {
        	$data["success"] = false;
        	$data["message"] ="Please enter required fields";

        }

		return Response::json($data,200,array());		
	}

	public function academyData(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
        $user_access = User::getAccess("st-profile",$user->id);

		$students = DB::table('students')->select("students.id")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->join("city","city.id","=","center.city_id")->whereIn("students.inactive",[0,2]);
		if(!$user_access->all_access){
			$students = $students->whereIn("students.group_id",$user_access->group_ids);
		}
		$students = $students->where("students.client_id",$user->client_id)->count();

		$boys = DB::table('students')->select("students.id")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->join("city","city.id","=","center.city_id")->where("gender",1)->whereIn("students.inactive",[0,2]);
		if(!$user_access->all_access){
			$boys = $boys->whereIn("students.group_id",$user_access->group_ids);
		}
		$boys = $boys->where("students.client_id",$user->client_id)->count();

		$girls = DB::table('students')->select("students.id")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->join("city","city.id","=","center.city_id")->where("gender",2)->whereIn("students.inactive",[0,2]);
		if(!$user_access->all_access){
			$girls = $girls->whereIn("students.group_id",$user_access->group_ids);
		}
		$girls = $girls->where("students.client_id",$user->client_id)->count();

		$centers  = DB::table('center')->where("center_status",0);
		if(!$user_access->all_access){
			$centers = $centers->whereIn("center.id",$user_access->center_ids);
		}
		$centers = $centers->where("center.client_id",$user->client_id)->count();

		$rec_students = DB::table('students')->select('students.id','students.name','center.center_name','groups.group_name','students.pic')->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->whereIn("students.inactive",[0,2]);
		if(!$user_access->all_access){
			$rec_students = $rec_students->whereIn("students.group_id",$user_access->group_ids);
		}
		$rec_students = $rec_students->where("students.client_id",$user->client_id)->orderBy('students.id', 'DESC')->limit(5)->get();

		foreach($rec_students as $student){
		    $student->pic = Utilities::getPicture($student->pic,'student');
		    $student->name = (strlen($student->name) > 25) ? substr($student->name,0,25)."..." : $student->name;
		}

		$groups   = [];

		$data['students'] = $students;
		$data['rec_students'] = $rec_students;
		$data['centers']  = $centers;
		$data['groups']   = $groups;
		$data['boys']   = $boys;
		$data['girls']   = $girls;
		$data['success']  = true;

		return Response::json($data,200,array());		
	}

	public function getLocation(Request $request){
        
        $token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);
        $position = $request->position;
        $latitude = $position["latitude"];
        $longitude = $position["longitude"];

        $center_id = $request->center_id;
        $center = Center::find($center_id);
        // $distance = $this->DistAB($latitude, $longitude, $center->latitude, $center->longitude);
        $distance = 1;
        
        if($distance <= 0.8){
       
            DB::table('staff_attendance')->where('date','=',date('Y-m-d',strtotime('today')))->delete();

            DB::table('staff_attendance')->insert([
                'user_id' => $user->id,
                'center_id' => $request->center_id,
                'date' => date('Y-m-d',strtotime('today')),
                'attendance' => 'P',
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);

            $data['success'] = true;
            $data['message'] = "Your attendance is successfully marked";
        } else {
            $data['success'] = true;
            $data['message'] = "Your attendance can not be marked as you are not at the center ,distance ".$distance;
        }

        $data["distance"] = $distance;


        return Response::json($data,200,array());
    }

    public function DistAB($lat_a,$lon_a,$lat_b,$lon_b){

        $delta_lat = $lat_b - $lat_a ;
        $delta_lon = $lon_b - $lon_a ;

        $earth_radius = 6372.795477598;

        $alpha    = $delta_lat/2;
        $beta     = $delta_lon/2;
        $a        = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($lat_a)) * cos(deg2rad($lat_b)) * sin(deg2rad($beta)) * sin(deg2rad($beta)) ;
        $c        = asin(min(1, sqrt($a)));
        $distance = 2*$earth_radius * $c;
        $distance = round($distance, 4);

        return $distance;

    }

    public function changePassword(Request $request, $user_id){

        $cre = [
            'password_o' => $request->password_o,
            'password_n' => $request->password_n,
            'password_c' => $request->password_c,
        ];
        $rules = [
            'password_o' => 'required',
            'password_n' => 'required',
            'password_c' => 'required|same:password_n',
        ];

        $validator = Validator::make($cre, $rules);
        $user = DB::table("users")->select("password")->where("id","=",$user_id)->first();

        if ($validator->passes()) { 

        	if (Hash::check($request->password_o, $user->password )) {
                $password = Hash::make($request->password_n);
                DB::table('users')->where("id", $user_id)->update([
                	"password" => $password,
                	"password_check" => $request->password_n
                ]);
                
	        	$data["success"] = true;
	        	$data["message"] ="Password has been successfully Changed";
	        } else {
                $data["success"] = false;
                $data["message"] = "The Old Password you entered is incorrect";
	        } 

        } else {
            $data["success"] = false;
            $data["message"] ="Please enter required fields";
        }
        
        return Response::json($data,200,array());  
    }

    public function getUser(Request $request){

    	$token = $request->header("apiToken");
    	$user = User::AuthenticateUser($token);
    	$user->pic = User::getPicture($user->pic);

    	$data["success"] = true;
    	$data["user"] = $user;
    	return Response::json($data, 200, array());
    }

    public function coachAttendList(Request $request){

    	$token = $request->header("apiToken");
    	$user = User::AuthenticateUser($token);
    	$user_id = $user->id;
    	
    	$month = $request->month;
        $year = $request->year;

        if($month == "") {
            $month = date("n");
            $year = date("Y");
        }

        $attendance = DB::table('staff_attendance')->select('date','attendance')->where('user_id', $user_id)->orderBy('date','DESC')->limit(10)->get();

        if(sizeof($attendance) > 0){
        	foreach($attendance as $att){
        		$att->date = date('d-m-Y',strtotime($att->date));
        		$att->attendance = $att->attendance == 1 ? "P" : "A";
        	}
        }

        $markedDates = new \stdClass;
        $month_attendance = DB::table('staff_attendance')->select('id','date','attendance')->where('user_id',$user_id)->orderBy("date")->get();

        // foreach($month_attendance as $att){
        //     $d = $att->date < 10 ? '0'.$att->date : $att->date;
        //     $m = $att->month < 10 ? '0'.$att->month : $att->month;

        //     $date = $att->year.'-'.$m.'-'.$d;

        //     $markedDates->{$date} = new \stdClass;
        //     if($att->attendance == "A"){
        //         $markedDates->{$date}->selected = true;
        //         $markedDates->{$date}->selectedColor = "red";
        //     } else {
        //         $markedDates->{$date}->selected = true;
        //         $markedDates->{$date}->selectedColor = "green";
        //     }
        // }


        $data['success']= true;
        $data['attendance'] = $attendance;
        $data['markedDates'] = $markedDates;
        $data['token'] = $token;
        return Response::json($data,200,array());
    }

    public function updateUser(Request $request){
		
		$user = $request->user;

		$user_id = $user['id'];

		$values = [
			"name" => $user['name'],
            "email" => $user['email'],
            "mobile" => $user['mobile'],
        ];

        $rules = [
            "name" => "required",
            "email" => "required",
            "mobile" => "required",
        ];

        $validator = Validator::make($values, $rules);
        
        if ($validator->passes()) {
        	DB::table('users')->where("id", $user_id)->update([
            	"name" => $user['name'],
            	"email" => $user['email'],
            	"mobile" => $user['mobile'],
            ]);

            $data['success'] = true;
        	$data['message'] = "User has been Udatted successfully.";

        } else {
        	$data['success'] = false;
        	$data['message'] = "All Fields are required.";
        }

        return Response::json($data, 200, array());
    }

    public function uploadProfile(Request $request, $user_id){

    	$success = false;
        $destinationPath = "../images";
        
        if($request->hasFile('image')){

            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = 'image-'.$user_id.'-'.strtotime("now").'.'.$extension;
            $request->file('image')->move($destinationPath,$filename);

            $user = User::find($user_id);
            if($user){
                $user->pic = $filename;
                $user->save();
                $success = true;
            }
        }
        if($success){
            // $data['pic'] = $user->pic;
            $data['success'] = true;
            $data['details'] = $user_id." - ".$extension;
            $data['message'] = $filename;

            return Response::json($data, 200); 
        } else {
        	$data['success'] = false;
            $data['message'] = "Not done ".$user_id." - ".$extension;
            return Response::json($data, 409);
        }
    }

    // ************************ STUDENTS ************************

    public function studentInfo(Request $request){
    	
    	$token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);
        $student_id = $request->student_id; 

        $student = DB::table('students')->select("students.*",'groups.group_name','center.center_name','states.state_name', 'cities.city_name')->leftJoin('groups', 'students.group_id','=', 'groups.id')->leftJoin('center', 'groups.center_id','=', 'center.id')->leftJoin('states', 'students.state_id','=','states.id')->leftJoin('cities', 'students.state_city_id','=','cities.id')->where('students.id', $student_id)->first();

        $student_tags = DB::table('student_tags')->select('student_tags.tag_id')->join('tags', 'tags.id','=', 'student_tags.tag_id')->where('student_tags.student_id',$student->id)->pluck("student_tags.tag_id")->toArray();


        $student->student_tags = $student_tags;

        if($student->dob){
            $student->dob = date("Y",strtotime($student->dob));
            $student->age = date("Y",strtotime('now')) - $student->dob;
        }
        
        $student->pic = Student::getPhoto($student->pic);

        $yellow = "#d4d40f";
        $green =  "#1b9439";
        $red =  "#d84a38";
        if($student->doe < strtotime("today")){
            $student->pending = true;
        } else {
            $student->pending = false;
        }

        if($student->inactive == 0){
            $student->color = $red;
        } else {
            $student->color = $student->pending ? $yellow : $green;
        }

        $student->doe = $student->doe ? date("d-m-Y",strtotime($student->doe)) : "";

        // if($student->father_mob){
        //     $student->father_mob = $student->father_mob;
        //     $student->father_mob_masked = "XXXXXX".substr($student->father_mob, -4);
        // } else {
        //     $student->father_mob = "-";
        //     $student->father_mob_masked = "-";
        // }
        // if($student->mother_mob){
        //     $student->mother_mob = $student->mother_mob;
        //     $student->mother_mob_masked = "XXXXXX".substr($student->mother_mob, -4);
        // } else {
        //     $student->mother_mob = "-";
        //     $student->mother_mob_masked = "-";
        // }

        $student->gender = ($student->gender == 1) ? "Male" : "Female";

        $payments = DB::table("payment_history")->where("student_id",$student->id)->orderBy("invoice_date",'DESC')->limit(5)->get();

        foreach($payments as $payment){
            $payment->invoice_date = date("d-m-Y", strtotime($payment->invoice_date));
            $payment->payment_date = date("d-m-Y", strtotime($payment->payment_date));
        }
        $student->payments = $payments;

        $tags = DB::table("tags")->get();

        if($student){
            $data["success"] = true;
            $data["student"] = $student;
            $data["tags"] = $tags;
        } else {
            $data["success"] = false;
            $data["message"] = "Student profile not found";
        }

        return Response::json($data,200,array());
    }

    public function saveTags(Request $request){
    	$token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);
        $student_id = $request->student_id;
        $tags_arr = $request->selected_tags_id;

        DB::table('student_tags')->where('student_id',$student_id)->delete();

        foreach($tags_arr as $tag_id){
            DB::table('student_tags')->insert([
                'student_id' => $student_id,
                'tag_id' =>  $tag_id,
                'user_id' => $user->id,
            ]);
        }

        if(sizeof($tags_arr) > 0){
            $tags = DB::table("tags")->whereIn("id",$tags_arr)->pluck("tag")->toArray();
            $tag_names = implode(', ',$tags);
        } else {
            $tag_names = "";
        }

        $student = Student::find($student_id);
        $student->tags = $tag_names;
        $student->save();

        $data['success'] = true;
        $data['message'] = "Tags has been inserted successfully.";
        $data['tag_names'] = $tag_names;
        return Response::json($data, 200, []);
    }

    public function studentEdit(Request $request, $student_id){
    	// SELECT students.name, students.email, states.state_name, city.city_name FROM students JOIN states ON students.state_id = states.id JOIN city ON city.id = students.state_city_id
        $token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $student = DB::table('students')->find($student_id);
        if($student->dob){
            // $student->dob = date("Y-m-d",$student->dob);
            $student->dob = date("d-m-Y",strtotime($student->dob));

        }
        $student->pic = Student::getPhoto($student->pic);

        $states  = DB::table('states')->select("id as key","state_name as label")->get();

        $cities  = DB::table('cities')->select("id as key","city_name as label")
        // ->where("state_id",$states->id)
        ->get();

        if($student){
            $data["success"] = true;
            $data["student"] = $student;
            $data["states"] = $states;
            $data["cities"] = $cities;
        } else {
            $data["success"] = false;
            $data["message"] = "Student profile not found";
        }

        return Response::json($data,200,array());
    }

    public function getStateList(Request $request){

        $token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $states  = DB::table('states')->select("id as key","state_name as label")->get();
        
        $data['success'] = true;
        $data['states'] = $states;
        return Response::json($data,200,array());
    }

    public function getCityList(Request $request, $state_id ){
    	
    	$token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $cities  = DB::table('cities')->select("id as key","city_name as label")->where("state_id",$state_id)->get();

        $data['success'] = true;
        $data['cities'] = $cities;
        $data['state_id'] = $state_id;

        return Response::json($data,200,array());	
    }

    public function uploadStuPic(Request $request, $student_id){
    	$success = false;
        $destinationPath = "../images";
        
        if($request->hasFile('image')){

            $student_id = ($student_id != '') ? $student_id : $request->id;

            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = 'image-'.$student_id.'-'.strtotime("now").'.'.$extension;
            $request->file('image')->move($destinationPath,$filename);

            $student = Student::find($student_id);
            if($student){
                $student->pic = $filename;
                $student->save();
                $success = true;
            }
        }

        if($success){
            // $data['pic'] = $student->pic;
            $data['details'] = $student_id." - ".$extension;
            $data['message'] = "The profile image ".$filename." has been uploaded Successfully";
            return Response::json($data, 200); 
        } else {
            $data['message'] = "Not done ".$student_id." - ".$extension;
            return Response::json($data, 409);
        }
    }

    public function saveStudent(Request $request, $student_id){

        $credentials = [
            'name'     => $request->name,
            'email'   => $request->email,
            'dob'      => $request->dob,
            'gender'   => $request->gender,
            'father'   => $request->father,
            'mother'   => $request->mother,

        ];
        $rules = [
            'name'      => 'required',
            'email'      => 'required',
            'dob'       => 'required',
            'gender'    => 'required',
            'father'    => 'required',
            'mother'    => 'required'
        ];

        $validator = Validator::make($credentials, $rules);
        if ($validator->passes()) {
            if($student_id){
                $students = Student::find($student_id);
            }else{
                $students = new Student;
            }
            if($request->file('pic')){
                $destination = "../images";
                $file = $request->file('pic');
                $extension = $file->getClientOriginalExtension();
                $name = 'student_'.strtotime("now").'.'.strtolower($extension);
            	$file = $file->move($destination, $name);
                $students->pic = $name;
             }

            $students->name   = $request->name;
            $students->dob    = $request->dob ? date('Y-m-d', strtotime($request->dob)) : "";
            $students->gender = $request->gender;
            
            $students->email  = $request->email;
            $students->school = $request->school;
            $students->mobile = $request->mobile;

            $students->father = $request->father;
            // $students->father_mob = $request->father_mob;
            // $students->father_email = $request->father_email;

            $students->mother = $request->mother;
            // $students->mother_mob = $request->mother_mob;
            // $students->mother_email = $request->mother_email;
            
            $students->address = $request->address;
            $students->state_city_id = $request->state_city_id;
            $students->state_id = $request->state_id;

            if($request->tags){
	            $students->tags = $request->tags;
            }
            // $students->kit_size = $request->kit_size;
            // $students->zip_code = $request->zip_code;

            $students->save();
            $data['success'] = true;
            $data['message'] = "data inserted successfully.";
        }else{
            $data['success'] = false;
            $data['message'] = "fill requied field.";            
        }
        return Response::json($data,200,array());
    } 

    public function groupShift(Request $request, $student_id){
    	$group_id = $request->group_id;
        $token  = $request->header('apiToken');
        $member = User::AuthenticateUser($token);
        
        DB::table('students')->where("id",$student_id)->update([
            'group_id' => $group_id
        ]);

        $data['success'] = true;
        $data['message'] = "Group has been shifted successfully!";
        return Response::json($data,200,array());
    }

    public function studAttndList(Request $request, $student_id){
    	$token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $month = $request->month;
        $year = $request->year;

        if($month == "") {
            $month = date("n");
            $year = date("Y");
        }

        $view_attendance = DB::table('student_attendance')->select('id','date','attendance')->where('student_id',$student_id)->orderBy('date','DESC')->orderBy('date','DESC')->limit(10)->get();

        $markedDates = new \stdClass;
        
        $month_attendance = DB::table('student_attendance')->select('id','date','attendance')->where('student_id',$student_id)->orderBy("date", "DESC")->get();
        
        foreach($month_attendance as $att){

            $date = date('Y-m-d',strtotime($att->date));
            $markedDates->{$date} = new \stdClass;
            if($att->attendance == "A"){
                $markedDates->{$date}->selected = true;
                $markedDates->{$date}->selectedColor = "red";
            } else {
                $markedDates->{$date}->selected = true;
                $markedDates->{$date}->selectedColor = "green";
            }
        }

        $data['success']= true;
        $data['view_attendance'] = $view_attendance;
        $data['markedDates'] = $markedDates;
        $data['token'] = $token;

        return Response::json($data, 200, []);
    }

    public function getInActiveReasons(Request $request){
    	$token = $request->header('apiToken');
        $user = User::AuthenticateUser($token);
        $reasons = DB::table('reasons')->select('id as key', 'reason as label')->get();

        $data['success'] = true;
        $data['reasons'] = $reasons;

        return Response::json($data,200,array());
    }

    public function markInActive(Request $request){
    	$token = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $student_id = $request->student_id;
        $reason_id = $request->reason_id;
        $other_reason = $request->other_reason;
        $last_class = $request->last_class;

        $credentials = [
            'reason_id'     => $reason_id,
            'last_class'    => $last_class
        ];
        $rules = [
            'reason_id'     => 'required',
            'last_class'    => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->passes()){
            DB::table('inactive')->insert([
                'student_id'  => $student_id,
                'reason_id' => $reason_id,
                'other_reason' => $other_reason,
                'inactive_from' => date('Y-m-d', strtotime("now")),
                'last_class' => date('Y-m-d',strtotime ($last_class)),
                'added_by' => $user->id
            ]);

            $data['success']= true;
            $data['message']= "student has been the Inactive mode";
        } else{
            $data['success']= false;
            $data['message']= "Please fill the requied fields";
        }
	    $data['success']= true;
	    $data['last_class']=$last_class;
        $data["token"] = $token;
        return Response::json($data,200,array());
    }

    public function groupDetail(Request $request, $center_id){

        $token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $groups  = DB::table('groups')->select("groups.*")->where("center_id",$center_id)->get();

        $days_names = ["","Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        foreach($groups as $group){
            $group->timing = "";
            $group->days = "";

            $operation_days = DB::table("operation_days")->where("group_id",$group->id)->get();

            if(sizeof($operation_days) > 0){
                $group->timing = $operation_days[0]->from_time." - ".$operation_days[0]->to_time;
                $days = [];
                foreach($operation_days as $op_day){
                    $days[] = isset($days_names[$op_day->day]) ? $days_names[$op_day->day] : "";
                }
                $group->days = implode(', ',$days);
                $group->days = "Monday";

                $coaches = DB::table('group_coachs')->select('users.name')->join('users','group_coachs.coach_id','=','users.id')->where('group_coachs.group_id',$group->id)->pluck('users.name')->toArray();
                $group->coaches = implode(", ",$coaches);
            }
        }

        $plans =[];
         // DB::table("payment_table")->where("center_id",$center_id)->orderBy("month_plan")->get();
        
        $url = "https://www.youtube.com/";
        $data['success'] = true;
        $data['groups'] = $groups;
        $data['plans'] = $plans;
        $data['url'] = $url;

        return Response::json($data,200,array());
    }


    // ************************ APP EVENTS ************************

    public function getEventsList(Request $request){
    	$token  = $request->header('apiToken');
		$user = User::AuthenticateUser($token);
        
        $filters = $request->filters;
        $date = $request->date;
        $no_of_days = 6;

        if(!$date){
            $date = date("Y-m-d");
        } else {
            $no_of_days = 1;
        }

        $date_ts = strtotime($date);

        $all_events = [];

        $count = 1;

        $center_id = 0;

        $group_ids = [];
        if(isset($filters['center_id'])){
            if($filters['center_id']){
                $center_id = $filters["center_id"];
            }
        }

        if(isset($filters['my_group'])){
            if(is_array($filters['my_group'])){
                $group_ids = $filters["my_group"];
            }
        }

        for($day_ts = $date_ts; $day_ts  < $no_of_days*86400 + $date_ts; $day_ts = $day_ts + 86400) { 
            
            $day = date("w",$date_ts) + 1;

            $events = DB::table("operation_days")->select("operation_days.id as operation_id","operation_days.from_time","operation_days.to_time","operation_days.group_id","operation_days.center_id","groups.group_name","center.center_name")->join("groups","groups.id","=","operation_days.group_id")->join("center","center.id","=","groups.center_id");

            if($center_id){
                $events = $events->where('groups.center_id', $center_id);
            }

            if(sizeof($group_ids) > 0){
                $events = $events->whereIn('operation_days.group_id', $group_ids);
            }

            // $events = $events->where("operation_days.day",$day);

            // $group_ids = DB::table("group_coachs")->distinct("group_id")->where("coach_id",$user->id)->pluck("group_id")->toArray();

            // if(sizeof($group_ids) == 0 ) $group_ids = [0];
                // $events = $events->whereIn("operation_days.group_id",$group_ids);
          

            $events = $events->get();

            foreach($events as $event){
                $event->op_id = $event->operation_id."_".$count;
                $event->date_show = date("M d",$day_ts);
                $event->day_show = date("d",$day_ts);
                $event->month_show = date("M",$day_ts);
                $event->date = date("Y-m-d",$day_ts);
                $event->day = $day;
                $all_events[] = $event;
            }
            
            $count++;
        }

        $data['success']  = true;
        $data['events'] = $all_events;
        $data['center_id'] = $center_id;
        $data['groups'] = $group_ids;

        $data["token"] = $token;
        return Response::json($data,200,array());
    }

    public function getCenterList(Request $request){

    	$token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $centers  = DB::table('center')->select("id as key","center_name as label")->where("center_status", 0)->get();
        
        $data['success'] = true;
        $data['centers'] = $centers;
        return Response::json($data,200,array());
    }

    public function getGroupList(Request $request, $center_id ){
    	
    	$token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $groups  = DB::table('groups')->select("id as key","group_name as label")->where("center_id",$center_id)->where('group_status', 0)->get();

        $data['success'] = true;
        $data['groups'] = $groups;
        $data['center_id'] = $center_id;

        return Response::json($data,200,array());	
    }

    public function getReasons(Request $request){
        $token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $reasons = [
            array('key'=> 1, 'label'=> 'Timing'),
            array('key'=> 2, 'label'=> 'Weather'),
            array('key'=> 3, 'label'=> 'Migration'),
            array('key'=> 4, 'label'=> 'Other')
        ];

        $data['success'] = true;
        $data['reasons'] = $reasons;
        return Response::json($data,200,array());
    }

    public function cancelEvent(Request $request){
        
        $token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $cre = [
            'cancel_reason' => $request->cancel_reason,
        ];
        $rules = [
            'cancel_reason' => 'required',
        ];
        
        // if($request->cancel_reason) == "Other"){
        //     $cre["cancel_remarks"] = $request->cancel_remarks;
        //     $rules["cancel_remarks"] = 'required';
        // }

        $validator = Validator::make($cre, $rules);
        $date = $request->date;

        if ($validator->passes()) {

            DB::table('cancel_events')->insert([
                'date' => date('Y-m-d',strtotime($date)),
                'cancel_reason' => $request->cancel_reason,
                'cancel_remarks' => $request->cancel_remarks,
                'group_id' => $request->group_id,
                'op_id' => $request->operation_id,
                'user_id' => $user->id,
            ]);
            $data["success"] = true;
            $data["message"] ="Event has been Canceled Successfully";

        } else {
            $error = '';
            $messages = $validator->messages();
            foreach($messages->all() as $message){
                $error = $message;
                break;
            }
            $data["success"] = false;
            $data["message"] = $error;

        }
        
        return Response::json($data,200,array()); 
    }

    public function eventPlayers(Request $request){

        $token  = $request->header('apiToken');
        $user = User::AuthenticateUser($token);

        $group_id = $request->group_id;
        $date = date('Y-m-d',strtotime($request->date));

        $players = DB::table('students')->select('students.id','students.name','students.group_id','students.pic','students.doe','students.inactive','students.tags')->where("group_id",$group_id)->where("students.inactive",0)->orderBy("students.name")->get();

        $yellow = "#ffc107";
        $green =  "#03bd0b";
        $red =  "#e73500";

        $all_players = [];

        foreach($players as $player){
            $player->type = "player";

            $player->pic = Student::getPhoto($player->pic);
            $player->name = (strlen($player->name) > 25) ? substr($player->name,0,25)."..." : $player->name;
            $check = DB::table('student_attendance')->select('attendance')->where('student_id',$player->id)->where('group_id',$group_id)->where('date',$date)->first();

            if($check){
                $player->attendance = $check->attendance;
            } else {
                $player->attendance = 'A';
            }
            
            if($player->doe < strtotime("today")){
                $player->pending = true;
            } else {
                $player->pending = false;
            }

            if($player->doe > strtotime("today")){
                $player->doe = "Next due date - ".date("d-M-y", $player->doe);
            } else {
                $player->doe = "Fee due on ".date("d-M-y", $player->doe);
            }

            if($player->inactive == 0){
                $player->color = $red;
            } else {
                $player->color = $player->pending ? $yellow : $green;
            }

            $all_players[] = $player;
        }


        $guest_players = DB::table("guest_students")->select("id","full_name as name","group_id as first_group")->where("group_id",$group_id)->where("date",$date)->where("status",0)->get();
        
        foreach($guest_players as $player){
            $player->type = "guest";
            $player->pic = Student::getPhoto("");
            $player->attendance = "";
            $player->doe = "Guest Student";
            $all_players[] = $player;
        }

        $data['success']  = true;
        $data['players'] = $all_players;

        $data["token"] = $token;
        return Response::json($data,200,array());
    } 


    public function savePlayerAttendance(Request $request){
        
        $event = $request->event;
        $students = $request->students;
        $group_id = $event["group_id"];
		$date = date('y-m-d',strtotime($event["date"]));

        if(isset($students)){

            DB::table("guest_students")->where("date",$date)->where("group_id",$group_id)->update(array(
                "status" => 0
            ));

            foreach($students as $student){
                
                if($student["type"] == "player"){
                    $check = DB::table('student_attendance')->where('student_id',$student['id'])->where('group_id',$student['group_id'])->where('date', $date)->first();
                    if($check){
                        DB::table('student_attendance')->where('id',$check->id)->update([
                            'attendance' => $student['attendance']
                        ]);
                    }else{
                        DB::table('student_attendance')->insert([
                            'student_id' => $student['id'],
                            'group_id' => $student['group_id'],
                            'attendance' => $student['attendance'],
                            'date' => $date,
                        ]);
                    }
                } else {
                    DB::table("guest_students")->where("id",$student["id"])->update(array(
                        "status" => 1
                    ));
                }
                
            }
        }

        $data["event"] = $event;
        $data["students"] = $students;

        $data["success"] = true;
        $data["message"] ="Attendance updated";
        
        return Response::json($data,200,array()); 
    }

    public function saveGuestPlayer(Request $request){
    	$guest_player = $request->guest_player;
        
        $rules = [
            'full_name' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'age' => 'required',
            'remark' => 'required',
        ];

        $credentials = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'contact' => $request->contact,
            'age' => $request->age,
            'remark' => $request->remark,
        ];


        $validator = Validator::make($credentials, $rules);

        if ($validator->passes()) {
            $date = date('Y-m-d',strtotime('now'));
            $insert_id = DB::table('guest_students')->insertGetId([
                'date' => $date,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'contact' => $request->contact,
                'age' => $request->age,
                'remark' => $request->remark,
                'group_id' => $request->group_id,
                'date' => $request->date,
            ]);

            $guest_student = DB::table("guest_students")->select("id","full_name as name","group_id as group_id")->where("id",$insert_id)->first();

            $guest_student->pic = Student::getPhoto("");
            $guest_student->attendance = "";
            $guest_student->doe = "Guest Student";
            $guest_student->type = "guest";

            $data["success"] = true;
            $data["guest_student"] = $guest_student;
            $data["message"] ="Added Successfully";

        } else {
            $error = '';
            $messages = $validator->messages();
            foreach($messages->all() as $message){
                $error = $message;
                break;
            }
            $data["success"] = false;
            $data["message"] = $error;

        }
        
        return Response::json($data,200,array()); 
    }

    public function guestStudentRemove($student_id){
    	DB::table('guest_students')->where('id', $student_id)->delete();
    	
    	$data["success"] = true;
        $data["message"] ="The Record hasbeen removed successfully";

        return Response::json($data, 200, array());
    }

}
