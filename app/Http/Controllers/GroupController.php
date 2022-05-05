<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, Request;
use App\Group,App\Center,App\OperationDay, App\User;

class GroupController extends Controller {

	public function init(){
		$user = User::AuthenticateUser(Request::header("apiToken"));
		$group_id = Input::get('group_id');
		$group = Group::find($group_id);
		$days_list = Center::days_list();
		if($group){
			if($group->group_dos){
				$group->group_dos = date('d-m-Y',strtotime($group->group_dos));
			}
			$center = DB::table('center')->select('id','center_name','city_id')->where('id',$group->center_id)->first();
			if($center){
				$group->city_id = $center->city_id;
				$group->timings = DB::table('operation_days')->where('group_id',$group->id)->get();
				if(sizeof($group->timings) > 0){
					foreach ($group->timings as $timing) {
						$timing->day_name = (isset($days_list[$timing->day]))?$days_list[$timing->day]:'';
					}
				}
				$data['centers'] = DB::table('center')->select('id','center_name')->where('city_id',$group->city_id)->orderBy('center_name')->get();
			}

			$data['group'] = $group;
		}
		$data['days'] = Center::days();
		$cities = DB::table('city')->select('id','city_name')->orderBy('city_name')->get();
		foreach ($cities as $city) {
			$city->centers = DB::table('center')->select('id','center_name')->where('city_id',$city->id)->orderBy('center_name')->get();
		}

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

		$data['success'] = true;
		$data['cities'] = $cities;
		$data['categories'] = $categories;
		$data['even_categories'] = $even_categories;
		return Response::json($data,200,array());
	}

	public function delete(){
		$id = Input::get('id');
		if ($id) {
			$group = Group::find($id);
			if ($group) {
				$group->delete();
				$data['success'] = true;
				$data['message'] = "Group Deleted successfully";
			}else{
				$data['success'] = false;
				$data['message'] = "Group Not Exist";
			}
		}
		return Response::json($data, 200, array());
	}

	public function add(){
		$user = User::AuthenticateUser(Request::header("apiToken"));
		$request = Input::get('group_data');

		$rules = ["center_id"=>"required","group_name"=>"required"];

		$validator = Validator::make($request,$rules);
		if($validator->passes()){
			// $timings = $request['timings'];
			// if(sizeof($timings) == 0){
			// 	$data['success'] = false;
			// 	$data['message'] = "Please add group operation days";
			// 	return Response::json($data,200,array());

			// }

			$group = Group::find($request['id']);
			if(!$group){
				$group = new Group;
				$data['message'] = "New  group is added successfully";
			} else {
				$data['message'] = "Group details are updated successfully";
			}

			$group->center_id = isset($request['center_id'])?$request['center_id']:NULL;
			$group->group_name = isset($request['group_name'] )?$request['group_name'] :NULL;
			$group->capacity = isset($request['capacity'])?$request['capacity']:NULL;
			$group->group_dos = isset($request['group_dos'])?date("Y-m-d",strtotime($request['group_dos'])):NULL;
			$group->age_group_category = isset($request['age_group_category'])?
			$request['age_group_category']:'';

			$group->save();

			$group_name = '';			

			$data['success'] = true;

		}else{
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
		}
		return Response::json($data,200,array());
	}

	function getNameFromNumber($num) {
        $numeric = ($num ) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num ) / 26) - 1;
        if ($num2 >= 0) {
            return $this->getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }
	
	public function updateDos()
	{
		$groups = Group::where('group_dos','')->orWhere('group_dos',NULL)->get();
		foreach ($groups as $group) {
			$center = Center::where('id',$group->center_id)->first();
			if($center){
				
				$group->group_dos = $center->center_dos;
				$group->save();
			}
		}
	}
}


                 