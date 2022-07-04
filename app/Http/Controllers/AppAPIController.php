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
                $user->id = Auth::user()->id;
				$user->name = Auth::user()->name;
				$user->email = Auth::user()->email;
				$user->mobile = Auth::user()->mobile;
				$user->is_admin = Auth::user()->role == 1 ? 1 : 0;

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

    public function signUp(Request $request){
        $email = $request->email;

        $user_id = $request->user_id;

        $credentials = [

            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];
        $rules = [
            'name' => 'required',
            'email' => 'required | email',
            'username' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);
        if ($validator->passes()) {
            
            $user = User::where("email",$email)->first();
            if($user){
                $data["success"] = false;
                $data["message"] = "This email is already registered with us. Kindly try forget password.";
            } else {
                // $user = new User;
                // $user->name = $request->name;
                // $user->email = $request->email;
                // $user->username = $request->username;
                // $user->password = Hash::make($request->password);
                // $user->password_check = $request->password;
                // $user->save();

                $data["success"] = true;
                $data["user"] = $user;
                $data["message"] = "Thanks for your intrest, We will verify your Account as soon as possible";
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
            if($att->attendance != 1){
                $markedDates->{$date}->selected = true;
                $markedDates->{$date}->selectedColor = "red";
            } else {
                $markedDates->{$date}->selected = true;
                $markedDates->{$date}->selectedColor = "green";
            }
        }

        foreach($view_attendance as $att){
            $att->date = date('d-M-Y',strtotime($att->date));
            $att->attendance = $att->attendance == 1 ? "P" : "A";
        }

        $data['success']= true;
        $data['view_attendance'] = $view_attendance;
        $data['markedDates'] = $markedDates;
        $data['token'] = $token;

        return Response::json($data, 200, []);
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

    public function uploadStudentPic(Request $request, $student_id){
        $success = false;
        $destinationPath = "uploads/";
        
        if($request->hasFile('image')){
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = 'image-'.$student_id.'-'.strtotime("now").'.'.$extension;
            $request->file('image')->move($destinationPath,$filename);

            $student = Student::find($student_id);
            if($student){
                $student->pic = $destinationPath.$filename;
                $student->save();
                $success = true;
            }
        }

        if($success){
            $data['details'] = $student_id." - ".$extension;
            $data['message'] = "The profile image has been uploaded Successfully";
            return Response::json($data, 200); 
        } else {
            $data['message'] = "Not done ".$student_id." - ".$extension;
            return Response::json($data, 409);
        }
    }

    public function groupDetail(Request $request, $center_id){

        $user = User::AuthenticateUser($request->header('apiToken'));

        $groups  = DB::table('groups')->where("center_id",$center_id)->get();

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
        
        $url = "https://www.youtube.com/";

        $plans =[];

        $data['success'] = true;
        $data['groups'] = $groups;
        $data['plans'] = $plans;
        $data['url'] = $url;

        return Response::json($data,200,array());
    }

    // *****************************EVENTS******************************
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
            
            $day = date("w",$day_ts) + 1;

            $events = DB::table("operation_days")->select("operation_days.id as operation_id","operation_days.day","operation_days.from_time","operation_days.to_time","operation_days.group_id","operation_days.center_id","groups.group_name","center.center_name")->join("groups","groups.id","=","operation_days.group_id")->join("center","center.id","=","groups.center_id");

            if($center_id){
                $events = $events->where('groups.center_id', $center_id);
            }

            if(sizeof($group_ids) > 0){
                $events = $events->whereIn('operation_days.group_id', $group_ids);
            }

            $events = $events->where("operation_days.day",$day);

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
        $date = date("Y-m-d",strtotime($request->date));

        if ($validator->passes()) {

            $check = DB::table("cancel_events")->where("date",$date)->where("group_id",$request->group_id)->first();
            if($check){
                DB::table('cancel_events')->where("id",$check->id)->update([
                    'cancel_reason' => $request->cancel_reason,
                    'cancel_remarks' => $request->cancel_remarks,
                    'op_id' => $request->operation_id,
                    'user_id' => $user->id,
                ]);
            } else {
                DB::table('cancel_events')->insert([
                    'date' => $date,
                    'cancel_reason' => $request->cancel_reason,
                    'cancel_remarks' => $request->cancel_remarks,
                    'group_id' => $request->group_id,
                    'op_id' => $request->operation_id,
                    'user_id' => $user->id,
                ]);
            }
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

        $players = DB::table('students')->select('students.id','students.name','students.group_id','students.pic','students.doe','students.inactive')->where("group_id",$group_id)->where("students.inactive",0)->orderBy("students.name")->get();

        $yellow = "#ffc107";
        $green =  "#03bd0b";
        $red =  "#e73500";

        $all_players = [];

        foreach($players as $player){
            $player->type = "player";

            $player->pic = Utilities::getPicture($player->pic,'student');
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
            'dob' => 'required',
            'remark' => 'required',
        ];

        $credentials = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'contact' => $request->contact,
            'dob' => $request->dob,
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
                'dob' => $request->dob,
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


    // ************************Account**************************


    public function getUser(Request $request){

    	$user = User::AuthenticateUser($request->header("apiToken"));

        $user_row = DB::table("users")->select("id","name","email","mobile","pic")->where("users.id",$user->id)->first();
        if(!$user_row->email) $user->email = "";
        if(!$user_row->mobile) $user->mobile = "";

    	$user_row->pic = Utilities::getPicture($user_row->pic,'student');

    	$data["success"] = true;
    	$data["user"] = $user_row;
    	return Response::json($data, 200, array());
    }

    public function getLocation(Request $request){
        
        $user = User::AuthenticateUser($request->header('apiToken'));

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
                'city_id' => $request->center_id, // cause the feild center_id not in table 
                'date' => date('Y-m-d',strtotime('today')),
                'added_by' => $user->id,
                'attendance' => 1,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);

            $data['success'] = true;
            $data['color'] = "#32a85a"; //green
            $data['message'] = "Your attendance is successfully marked";
        } else {
            $data['success'] = true;
            $data['color'] = "#d84a38"; //red
            $data['message'] = "Your attendance can not be marked as you are not at the center ,distance ".$distance;
        }

        $data["distance"] = $distance;


        return Response::json($data,200,array());
    }

     public function uploadProfile(Request $request, $user_id){

    	$success = false;
        $destinationPath = "uploads/";
        
        if($request->hasFile('image')){

            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = 'image-'.$user_id.'-'.strtotime("now").'.'.$extension;
            $request->file('image')->move($destinationPath,$filename);

            $user = User::find($user_id);
            if($user){
                $user->pic = $destinationPath.$filename;
                $user->save();
                $success = true;
            }
        }
        if($success){
            $data['success'] = true;
            $data['details'] = $user->pic;
            $data['message'] = $filename;
            return Response::json($data, 200); 
        } else {
        	$data['success'] = false;
            $data['message'] = "Not done ".$user_id." - ".$extension;
            return Response::json($data, 409);
        }
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
        	$data['message'] = "Your profile is successfully updated.";

        } else {
        	$data['success'] = false;
        	$data['message'] = "All Fields are required.";
        }

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

        foreach($month_attendance as $att){
            
            $date = date('Y-m-d', strtotime($att->date));
            $d = date('d',strtotime($att->date)) < 10 ? '0'.date('d',strtotime($att->date)) : date('d',strtotime($att->date));
            $m = date('m',strtotime($att->date)) < 10 ? '0'.date('m',strtotime($att->date)) : date('m',strtotime($att->date));

            $markedDates->{$date} = new \stdClass;
            if($att->attendance == 0){
                $markedDates->{$date}->selected = true;
                $markedDates->{$date}->selectedColor = "red";
            } else {
                $markedDates->{$date}->selected = true;
                $markedDates->{$date}->selectedColor = "green";
            }
        }


        $data['success']= true;
        $data['attendance'] = $attendance;
        $data['markedDates'] = $markedDates;
        $data['token'] = $token;
        return Response::json($data,200,array());
    }

    public function changePassword(Request $request){

        $user_auth = User::AuthenticateUser($request->header("apiToken"));
        $user_id = $user_auth->id;
        
        $cre = [
            'old_password' => $request->old_password,
            'new_password' => $request->new_password,
            'confirm_password' => $request->confirm_password,
        ];
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ];

        $validator = Validator::make($cre, $rules);
        $user = DB::table("users")->select("password")->where("id","=",$user_id)->first();

        if ($validator->passes()) { 

        	if (Hash::check($request->old_password, $user->password )) {
                $password = Hash::make($request->new_password);
                DB::table('users')->where("id", $user_id)->update([
                	"password" => $password,
                	"password_check" => $request->new_password
                ]);
                
	        	$data["success"] = true;
	        	$data["message"] ="Password has been successfully Changed!";
	        } else {
                $data["success"] = false;
                $data["message"] = "The old password you have entered is incorrect.";
	        } 

        } else {
            $error = '';
            $messages = $validator->messages()->all();
            foreach($messages as $message){
                $error = $message;
                break;
            }
            $data["success"] = false;
            $data["message"] = $error;
        }
        
        return Response::json($data,200,array());  
    }

    public function deleteReason(Request $request){
        $user = User::AuthenticateUser($request->header('apiToken'));

        $reasons = [
            array('value'=> 1, 'label'=> 'Terms of service'),
            array('value'=> 2, 'label'=> 'Security reasons'),
            array('value'=> 3, 'label'=> 'This is not technically competent enough to be trusted'),
            array('value'=> 4, 'label'=> 'The Application itself sucks'),
        ];

        $data['success'] = true;
        $data['reasons'] = $reasons;
        return Response::json($data,200,array());
    } 

    public function deleteAccount(Request $request){
        $user = User::AuthenticateUser($request->header('apiToken'));

        $data['success'] = true;
        $data['message'] = "Your profile will be removed within nex 24 hours.";
        return Response::json($data,200,array());

    }


}
