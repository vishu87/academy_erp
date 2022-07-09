<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use  App\Models\User, App\Models\Center, App\Models\CenterImage;
use  App\Models\CenterPerson, App\Models\Group, App\Models\OperationDay, App\Models\Student, App\Models\OperationCoach, App\Models\Utilities;

class CenterController extends Controller{ 

    public function getCentersList(){
        User::pageAccess(15);
       	return view('manage.centers.index',["sidebar"=>"center" ,"menu" => "admin"]);
    }

    public function addCenter($center_id){ 
        User::pageAccess(15);
       	return view('manage.centers.add',['center_id'=>$center_id,"sidebar"=>"center" ,"menu" => "admin"]);
    }

    public function getCentersListData(Request $request){

    	$user = User::AuthenticateUser($request->header("apiToken"));

    	$list = DB::table('center')->select('center.id','center.center_name','center.address', 'center.city_id','city.city_name','city.state_id','states.state_name');

    	if ($request->city_id > 0) {
    		$list = $list->where('center.city_id',$request->city_id);
    	}

    	$list = $list->leftJoin('city','city.id' , '=', 'center.city_id')
    	->leftJoin('states','states.id' , '=', 'city.state_id')
    	->where("center.client_id",$user->client_id)->where("center.center_status",0)
    	->get();

    	$data['success'] = true;
    	$data['data'] = $list;
    	return Response::json($data, 200, array());
    }

    public function getCentersParams(Request $request){

    	$user = User::AuthenticateUser($request->header("apiToken"));

    	$categories = [];
		for ($i= 4; $i < 19; $i++) { 
			if($i > 9){
				$categories[] = ["id"=>$i , "name"=>'U-'.$i];
			}else{
				$categories[] = ["id"=>$i , "name"=>'U-0'.$i];
			}
		}

		$categories[] = ["id"=>"30","name"=>"Senior"];

		$even_categories = [];
		for ($i= 4; $i < 19; $i += 2) { 
			if($i > 9){
				$even_categories[] = ["id"=>$i , "name"=>'U-'.$i];
			}else{
				$even_categories[] = ["id"=>$i , "name"=>'U-0'.$i];
			}
		}
		$even_categories[] = ["id"=>"30","name"=>"Senior"];

		$data['days'] = Center::days();
    	$data['cities'] = DB::table('city')->select('id','city_name')->where('client_id',$user->client_id)->get();
    	$data['cordinators'] = DB::table('users')->select('id','name')->where('client_id',$user->client_id)->orderBy("name")->get();
    	$data['categories'] = $categories;
		$data['even_categories'] = $even_categories;
    	$data['success'] = true;
    	$data['message'] = "";

    	return Response::json($data, 200, []);

    }

    public function createNewCenter(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$check_access = User::getAccess("center",$user->id, $request->city_id, 'city_ids');
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

    	$cre = [
    		"city" => $request->city_id,
    		"center_name" => $request->center_name
    	];
    	$rules = [
    		"city" => "required",
    		"center_name" => "required",
    	];

    	$validator = Validator::make($cre,$rules);
    	if($validator->fails()){
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
		}else{
			$center = new Center;
			$center->center_name = $request->center_name;
			$center->city_id = $request->city_id;
			$center->client_id = $user->client_id;
			$center->save();

			$data['success'] = true;
			$data['id'] = $center->id;
		}

		return Response::json($data, 200 ,[]);
    }


    public function editCenter(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
    	$center_id = $request->center_id;

    	$center = DB::table('center')->where("id",$center_id)->where("client_id",$user->client_id)->first();

    	if($center){
    		$check_access = User::getAccess("center",$user->id, $center->city_id, 'city_ids');
	        if(!$check_access) {
	            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
	        }
	        $center->center_dos = Utilities::convertDate($center->center_dos);
			$data['success'] = true;
			$data['center'] = $center;
			$data['message'] = "";
    	} else {
    		$data['success'] = false;
			$data['message'] = 'Center details does not exist , something went wrong';
    	}

		$data['success'] = true;
		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

    public function updateCenter(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
		$cre = [
            "center_name" => $request->center_name,
            "city_id" => $request->city_id,
            "center_status" => $request->center_status
        ];
        $validator = Validator::make($cre, [
            "center_name" => "required",
            "city_id" => "required",
            "center_status" => "required"
        ]);

		if($validator->fails()){
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
		} else {

			$center = Center::where("id",$request->id)->where("client_id",$user->client_id)->first();
			if(!$center){
				dd("not authorized");
			}
			$center->city_id = $request->city_id;
			$center->center_name = $request->center_name;
			$center->center_dos = Utilities::convertDateToDB($request->center_dos);
			$center->address = $request->address;
			$center->center_capacity = $request->center_capacity;
			$center->center_status = $request->center_status;
			$center->cordinator_id = $request->cordinator_id;
			$center->ground_width = $request->ground_width;
			$center->ground_length = $request->ground_length;
			$center->hide_on_app = ($request->hide_on_app)?$request->hide_on_app:0;
			$center->short_url = isset($request->short_url)?$request->short_url:'';
			$center->latitude = isset($request->latitude)?$request->latitude:'';
			$center->longitude = isset($request->longitude)?$request->longitude:'';
			$center->save();

			$data['success'] = true;
			$data['message'] = "Center details are updated successfully";
		}
		return Response::json($data,200,array());
	}

    public function deleteCenter(Request $request, $id){

    	$user = User::AuthenticateUser($request->header("apiToken"));

    	$center = DB::table('center')->find($id);

		$check_access = User::getAccess("center",$user->id, $center->city_id, 'city_ids');
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

    	DB::table('center')->where('id',$id)->update(["center_status" => 1]);	
    	$data['success'] = true;
    	$data['message'] = "Center Deleted successfully"; 
    	return Response::json($data, 200, array()); 
    }



	public function images(Request $request, $center_id){
		
		$user = User::AuthenticateUser($request->header("apiToken"));

		$images = DB::table('center_images')->select("center_images.*")->join("center","center.id","=","center_images.center_id")->where('center_id',$center_id)->where("client_id",$user->client_id)->get();

		$data['images'] = $images;
		$data['success'] = true;
		$data['message'] = "";
		return Response::json($data,200,[]);
	}

	public function saveImage(Request $request){

        $image = new CenterImage;
        $image->center_id = $request->center_id;
        $image->image = $request->path_url;
        $image->image_thumb = $request->thumb_url;
        $image->save();

        $data['success'] = true;
        $data['message'] ='Image successfully saved..';

        return Response::json($data, 200, array());
    }

    public function removeImage(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

    	$center_image = DB::table('center_images')->select('center_images.id')
    	->join('center','center.id','=','center_images.center_id')
    	->where("center_images.id",$request->id)->where("center.client_id",$user->client_id)->first();

    	if($center_image){
    		DB::table('center_images')->where("id",$request->id)->delete();
    		$data['message'] = "Image successfully removed";
    		$data['success'] = true;
    	}else{
    		$data['success'] = false;
    		$data['message'] = "Image not found";
    	}
        return Response::json($data, 200, array());
    }

	public function groups(Request $request, $center_id){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$groups = DB::table('groups')->select('id','group_name','capacity')->where('center_id',$center_id)->where('client_id',$user->client_id)->get();
		
        foreach ($groups as $group) {

        	$group->group_coach = DB::table('group_coaches')->select('group_coaches.*','users.name')->where('group_id',$group->id)->leftJoin('users','users.id','group_coaches.coach_id')->orderBy('id','DESC')->get();

            $group->operation_timings = OperationDay::where('center_id',$center_id)->where('group_id',$group->id)->orderBy('day')->get();
            $group->active_students = Student::where('group_id',$group->id)->where('inactive',0)->count();
            $group->pending_renewals = Student::where('group_id',$group->id)->where('doe','<',strtotime("today"))->where('inactive',0)->count();
            if(sizeof($group->operation_timings) > 0){
                foreach ($group->operation_timings as $timing) {
                	$days_list = Center::days_list();
                	$timing->day_name = $days_list[$timing->day];
                    $coaches = OperationCoach::select('users.name','coach_id')->join('users','users.id','=','center_operation_coaches.coach_id')->where('operation_id',$timing->id);
                    $coaches_list = $coaches->pluck('name')->all();
                    if(sizeof($coaches_list) > 0){

                        $timing->coaches_list = implode(' , ',$coaches_list);
                    }
                    $coaches = $coaches->get();
                    $coach_arr = array();
                    if(sizeof($coaches) > 0){
                        foreach ($coaches as $count => $coach) {
                            $coach_arr[$count+1] = $coach->coach_id;
                        }
                    }
                    $timing->coaches = $coach_arr;
                }
            }
        }

		$data['groups'] = $groups;
		$data['success'] = true;
		$data['message'] = "";
		return Response::json($data,200,[]);
	}

	public function addGroup(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$cre = [
            "group_name" => $request->group_name,
            "age_group_category" => $request->age_group_category,
        ];
        $validator = Validator::make($cre, [
            "group_name" => "required",
            "age_group_category" => "required",
        ]);

		if($validator->fails()){
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
		} else {

			if($request->id){
				$group = Group::find($request->id);
				$data['message'] ="Group successfully updated";
			} else {
				$data['message'] ="Group successfully created";
				$group = new Group;
			}

			$group->center_id = $request->center_id;
			$group->group_name = $request->group_name;
			$group->client_id = $user->client_id;
			$group->capacity = $request->capacity;
			$group->group_dos = Utilities::convertDateToDB($request->group_dos);
			$group->age_group_category = $request->age_group_category;
			$group->group_type_id = $request->group_type_id;
			$group->save();
			$data['success'] = true;

		}
		return Response::json($data,200,array());
	}

	public function editGroup(Request $request, $group_id){

		$group = DB::table('groups')->where('id',$group_id)->first();
		$group->group_dos = Utilities::convertDate($group->group_dos);
		$data['group'] = $group;
		$data['success'] = true;
    	$data['message'] = ""; 

    	return Response::json($data, 200, []);
	}

	public function addGroupTiming(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
		
    	$cre = [
    		"day" => $request->day,
    		"from_time" => $request->from_time,
    		"to_time" => $request->to_time
    	];

		$validator = Validator::make($cre, [
			"day" => "required",
			"from_time" => "required",
			"to_time" => "required"
		]);

		if($request->id){
			$cre["effective_date"] = $request->effective_date;
			$rules["effective_date"] = "required";
		}

		if($validator->fails()){
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
			return Response::json($data,200,array());
		}else{
			if($request->id){
				$operation = OperationDay::find($request->id);
				$data['message'] = 'Timing is updated successfully';
			}else{

				$operation = new OperationDay;
				$data['message'] = 'New timings are added successfully';
				$operation->center_id = $request->center_id;
				$operation->group_id = $request->group_id;
				$operation->day = $request->day;
			}
			$operation->from_time = $request->from_time;
			$operation->to_time = $request->to_time;
			$operation->save();
			$data['success'] = true;
		}
		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}



 //    public function updateCenter1(Request $request){
 //        $user = User::AuthenticateUser($request->header("apiToken"));
	// 	$flag = false;
	// 	$request = $request->data;
		
	// 	$center_validator['center_name'] = $request['center_name']; 
	// 	$center_validator['city_id'] = $request['city_id']; 
	// 	$center_validator['invoice_city'] = $request['invoice_city']; 
	// 	$center_validator['invoice_state'] = $request['invoice_state']; 
	// 	$center_validator['center_status'] = $request['center_status']; 

	// 	$validator = Validator::make(
	// 		$center_validator,[
	// 		"center_name" => "required",
	// 		"city_id" => "required",
	// 		// "invoice_city" => "required",
	// 		// "invoice_state" => "required",
	// 		"center_status" => "required",
	// 	]);

	// 	if($validator->fails()){
	// 		$data['success'] = false;
	// 		$data['message'] = $validator->errors()->first();
	// 	}else{
	// 		if($request->center_status == 1){
	// 			$center_groups = Group::where('center_id',$request['id'])->where("group_status",0)->pluck('id')->all();
	// 			$active_students = Student::whereIn('first_group',$center_groups)->where('active',0)->count();
	// 			if($active_students > 0 || sizeof($center_groups) > 0){
	// 				$flag = true;
	// 			}
	// 		}
			
	// 		if(!$flag){
	// 			if (!isset($request['id'])) {
	// 				$center = new Center;
	// 			}else{
	// 				$center = Center::find($request['id']);
	// 			}
	// 			$center->center_name = $request['center_name'];
	// 			$center->center_app_name = $request['center_app_name'];
	// 			$center->city_id = $request['city_id'];
	// 			$center->invoice_city = $request['invoice_city'];
	// 			$center->center_capacity = $request['center_capacity'];
	// 			$center->cordinator_id = $request['cordinator_id'];
	// 			$center->invoice_state = $request['invoice_state'];
	// 			$center->relationship_manager_id = isset($request['relationship_manager_id'])?$request['relationship_manager_id']:'';
	// 			$center->relationship_manager = (isset($request['relationship_manager_id']))?User::getMemberName($request['relationship_manager_id']):'';
	// 			$center->contract_start = Utilities::convertDateToDB($request->contract_start);
	// 			$center->contract_end = (isset($request['contract_end']))?date("Y-m-d",strtotime($request['contract_end'])):NULL;
	// 			$center->previous_contract = isset($request['previous_contract'])?$request['previous_contract']:'';
	// 			$center->center_status = $request['center_status'];
	// 			$center->address = $request['address'];
	// 			$center->longitude = isset($request['longitude'])?$request['longitude']:'';
	// 			$center->latitude = isset($request['latitude'])?$request['latitude']:'';
	// 			$center->short_url = isset($request['short_url'])?$request['short_url']:'';
	// 			$center->hide_on_app = ($request->hide_on_app)?$request->hide_on_app:0;
	// 			$center->meta_title = isset($request['meta_title'])?$request['meta_title']:'';
	// 			$center->meta_description = isset($request['meta_description'])?$request['meta_description']:'';
	// 			$center->meta_keywords = isset($request['meta_keywords'])?$request['meta_keywords']:'';
	// 			$center->slug = isset($request['slug'])?$request['slug']:'';

	// 			$center->center_dos = ($request['center_dos'] != '')?date("Y-m-d",strtotime($request['center_dos'])):NULL;
	// 			$center->tech_lead_id = isset($request['tech_lead_id'])?$request['tech_lead_id']:'';
	// 			$center->mentor_id = $request['mentor_id'];
	// 			$center->rent_type = $request['rent_type'];
	// 			$center->rent_amount = $request['rent_amount'];
	// 			$center->ground_width = $request['ground_width'];
	// 			$center->ground_length = $request['ground_length'];
	// 			$center->match_format = $request['match_format'];


	// 			$center->save();
	// 			if (!isset($request['id'])) {
	// 				$data['id'] = $center->id;
	// 				$data['success'] = true;
	// 				$data['message'] = "Center details are Added successfully";
	// 				return Response::json($data, 200, array());
	// 			}
	// 			//revenue entries
	// 			if($request['years']){

	// 				$yearly = $request['years'];

	// 				CenterRevenue::where('center_id',$request['id'])->where('year',$yearly['year_id'])->delete();
	// 				$year_revenue = CenterRevenue::where('center_id',$request['id'])->where('year',$yearly['year_id'])->where('month',0)->where('quater',0)->first();
	// 				$flag_new = false;

	// 				if(!$year_revenue) $flag_new = true;
	// 				else {
	// 					if($yearly['renewals'] != $year_revenue->renewals || $yearly['new_registration'] != $year_revenue->new_registration) {
	// 						$flag_new = true;
	// 						$year_revenue->status = 1;
	// 						$year_revenue->save();
	// 					}
	// 				}
	// 				if( $flag_new ){
	// 					$year_revenue = new CenterRevenue;
	// 					$year_revenue->center_id = $request['id'];
	// 					$year_revenue->year = $yearly['year_id'];
	// 					$year_revenue->renewals = isset($yearly['renewals'])?$yearly['renewals']:'';
	// 					$year_revenue->new_registration = isset($yearly['new_registration'])?$yearly['new_registration']:'';
	// 					$year_revenue->status = 0;
	// 					$year_revenue->month = 0;
	// 					$year_revenue->quater = 0;
	// 					$year_revenue->total = $year_revenue->renewals + $year_revenue->new_registration;
	// 					$year_revenue->added_by = $user->id;
	// 					$year_revenue->save();
	// 				}

	// 			}

	// 			$monthly = $request['months'];
	// 			if($monthly && sizeof($monthly) > 0){
	// 				$flag_new = false;
	// 				foreach ($monthly as $key => $month) {
	// 					$monthly_revenue = CenterRevenue::where('center_id',$request['id'])->where("month",$key)->where('year',$yearly['year_id'])->first();

	// 					if(!$monthly_revenue) $flag_new = true;
	// 					else {
	// 						if($month['renewals'] != $monthly_revenue->renewals || $month['new_registration'] != $monthly_revenue->new_registration) {
	// 							$flag_new = true;
	// 							$monthly_revenue->status = 1;
	// 							$monthly_revenue->save();
	// 						}
	// 					}

	// 					if($flag_new){

	// 						$monthly_revenue = new CenterRevenue;
	// 						$monthly_revenue->center_id = $request['id'];
	// 						$monthly_revenue->year = $yearly['year_id'];
	// 						$monthly_revenue->month = $key;
	// 						$monthly_revenue->quater = 0;
	// 						$monthly_revenue->renewals = isset($month['renewals'])?$month['renewals']:'';
	// 						$monthly_revenue->new_registration = isset($month['new_registration'])?$month['new_registration']:'';
	// 						$monthly_revenue->total = $monthly_revenue->renewals + $monthly_revenue->new_registration;

	// 						$monthly_revenue->status = 0;
	// 						$monthly_revenue->added_by = $user->id;
	// 						$monthly_revenue->save();
	// 					}


	// 				}
	// 			}

	// 			$quaterly = $request['quaters'];
	// 			if($quaterly && sizeof($quaterly) > 0){
	// 				foreach ($quaterly as $key=>$quater) {
	// 					$quaterly_revenue = CenterRevenue::where('center_id',$request['id'])->where("quater",$key)->where('year',$yearly['year_id'])->first();

	// 					if(!$quaterly_revenue) $flag_new = true;
	// 					else {
	// 						if($quater['renewals'] != $quaterly_revenue->renewals || $quater['new_registration'] != $quaterly_revenue->new_registration) {
	// 							$flag_new = true;
	// 							$quaterly_revenue->status = 1;
	// 							$quaterly_revenue->save();
	// 						}
	// 					}

	// 					if($flag_new){

	// 						$quaterly_revenue = new CenterRevenue;
	// 						$quaterly_revenue->center_id = $request['id'];
	// 						$quaterly_revenue->year = $yearly['year_id'];
	// 						$quaterly_revenue->quater = $key;
	// 						$quaterly_revenue->month = 0;

	// 						$quaterly_revenue->renewals = isset($quater['renewals'])?$quater['renewals']:'';
	// 						$quaterly_revenue->new_registration = isset($quater['new_registration'])?$quater['new_registration']:'';
	// 						$quaterly_revenue->status = 0;
	// 						$quaterly_revenue->total = $quaterly_revenue->renewals + $quaterly_revenue->new_registration;
	// 						$quaterly_revenue->added_by = $user->id;
	// 						$quaterly_revenue->save();
	// 					}

						
	// 				}
	// 			}


	// 			//revenue entries ends

	// 			// CenterPerson::where('center_id',$request['id'])->delete();
	// 			// foreach ($request['contact_persons'] as $person) {
	// 			// 	if($person['member_name'] && $person['member_name'] != ''){
	// 			// 		$contact_person = new CenterPerson;
	// 			// 		$contact_person->center_id = $request['id'];
	// 			// 		$contact_person->member_name = $person['member_name']; 
	// 			// 		$contact_person->designation = (isset($person['designation']))?$person['designation']:''; 
	// 			// 		$contact_person->email = (isset($person['email']))?$person['email']:''; 
	// 			// 		$contact_person->mobile = (isset($person['mobile']))?$person['mobile']:''; 
	// 			// 		$contact_person->save(); 
	// 			// 	}
	// 			// }

	// 			if(sizeof($request['groups']) > 0){
	// 				foreach ($request['groups'] as $group) {
	// 					$update_group = Group::find($group['id']);
	// 					$update_group->capacity = $group['capacity'];
	// 					$update_group->save();
	// 				}
	// 			}

	// 			$data['message'] = "Center details are updated successfully";
	// 		}else{
	// 			$data['success'] = false;
	// 			$data['message'] = "This center could not be mark as inactive as it has active students or active groups";
	// 		}


	// 	}
	// 	return Response::json($data,200,array());
	// }

	// public function addContactPerson1(){
	// 	$user = User::AuthenticateUser(Request::header("apiToken"));
	// 	$request = Input::get('data');
	// 	CenterPerson::where('center_id',$request['id'])->delete();
	// 	foreach ($request['contact_persons'] as $person) {
	// 		if($person['member_name'] && $person['member_name'] != ''){
	// 			$contact_person = new CenterPerson;
	// 			$contact_person->center_id = $request['id'];
	// 			$contact_person->member_name = $person['member_name']; 
	// 			$contact_person->designation = (isset($person['designation']))?$person['designation']:''; 
	// 			$contact_person->email = (isset($person['email']))?$person['email']:''; 
	// 			$contact_person->mobile = (isset($person['mobile']))?$person['mobile']:''; 
	// 			$contact_person->save(); 
	// 		}
	// 	}

	// 	$data["success"] = true;
	// 	return Response::json($data,200,array());
	// }

	// public function groupSchedule(){

	// 	$group_id = Input::get('group_id');
	// 	$events = BBFSEvent::select('bbfs_events.id','bbfs_events.start_date','training_plans.concept_type','training_plans.concept','training_plans.guideline','training_plans.objective','concept_levels.level_name')->leftJoin("training_plans","bbfs_events.training_plan_id","=","training_plans.id")->leftJoin('concept_levels','concept_levels.id','=','training_plans.concept_level_id')->where("group1_id",$group_id)->where("type",1)->where("start_date",">=",date("Y-m-d"))->orderBy("start_date","ASC")->get();

	// 	$data['success'] = true;
	// 	$data['events'] = $events;

	// 	return Response::json($data,200,array());
	// }



 //    public function deleteTiming1(){
	// 	$user = User::AuthenticateUser(Request::header("apiToken"));
	// 	$request = Input::get('timing');
	// 	// dd($request);
	// 	$rules = [
	// 		"effective_date"=>"required|date|after:today",
	// 	];
	// 	$validator = Validator::make($request,$rules);
	// 	if($validator->fails()){
	// 		$data['success'] = false;
	// 		$data['message'] = $validator->errors()->first();
	// 	}else{

	// 		$OperationDay  = OperationDay::find($request['id']);
	// 		if($OperationDay){
	// 			OperationCoach::where('operation_id',$request['id'])->delete();

	// 			DB::table('effective_timings')->insert(["effective_date"=>date('Y-m-d',strtotime($request['effective_date'])),"operation_id"=>$request['id'] , "status"=>2 ]);//2 for delete timing
				
	// 			$OperationDay->delete();

	// 			$data['success'] = true;
	// 			$data['message'] = 'Timing was removed successfully';
	// 			$data['center'] = Center::details($request['center_id']);
	// 		}else{
	// 			$data['success'] = false;
	// 			$data['message'] = 'No details found for this entry please refresh your page';
	// 		}
	// 	}
		
	// 	return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	// }

    public function all_coach(){
        $coachs =  DB::table('users')->select('id','name')->get();
        $data['success'] = true; 
        $data['coachs']   = $coachs; 
        return Response::json($data,200,array());
    } 


    public function save_coach(Request $request){

        foreach ($request->coach as  $ch) {
            DB::table('group_coaches')->insert([
                "coach_id" => $ch,
                "group_id" => $request->group_id,
            ]);   
        }

        $data['success'] = true; 
        $data['message']   = "coach successfully added"; 
        return Response::json($data,200,array());

    } 

    public function remove_coach($id){
        DB::table('group_coaches')->where('id',$id)->delete();
        $data['success'] = true; 
        $data['message']   = "coach successfully deleted"; 
        return Response::json($data,200,array());
    } 

    public function groupTypes(Request $request){
    	$user = User::AuthenticateUser($request->header("apiToken"));
        $group_types = DB::table('group_types')->select('id','name')->where('client_id',$user->client_id)->get();
        $data['group_types'] = $group_types; 
        $data['success'] = true; 
        return Response::json($data,200,array());
    } 

}


                 