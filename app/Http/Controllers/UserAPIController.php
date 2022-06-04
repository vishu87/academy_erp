<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB, Cache;

use Illuminate\Http\Request;

use App\Models\User, App\Models\Utilities;

class UserAPIController extends Controller {

	public function listRoles(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("admin_rights",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

		$user_roles = DB::table('roles')->select('id','title','access_rights','client_id')->whereIn("client_id",[0,$user->client_id])->get();

		foreach ($user_roles as $roles) {
			$rights_data = [];
			$roles_ids = explode(',',$roles->access_rights);
			$roles->access_rights = $roles_ids;

			$r_data = DB::table('access_rights')->whereIn('id',$roles_ids)->get();
			foreach ($r_data as $item) {
				array_push($rights_data, $item->access_rights);
			}
			$roles->access_items = implode(', ', $rights_data);

			if($roles->client_id != 0) $roles->editable = true;
			else $roles->editable = false;
		}

		$user_access_rights = DB::table('access_rights')->select('id','access_rights')->orderBy("priority")->get();

		$data["user_roles"] = $user_roles;
		$data["user_access_rights"] = $user_access_rights;

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function addRoles(Request $request){
		
		$user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("admin_rights",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

		$cre = [
			"title" => $request->title,
			"access_rights" => $request->access_rights,
		];
		$rules = [
			"title" => "required",
			"access_rights" => "required"
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){

			if(sizeof($request->access_rights) == 0){
				$data = ["success" => false, "message"=>"Please send at least one access right"]; return Response::json($data, 200 ,[]);
			}

			if($request->id){
				
				$check = DB::table('roles')->where("id",$request->id)->where("client_id",$user->client_id)->first();
				if($check){
					DB::table('roles')->where("id",$request->id)->update([
						"title" => $request->title,
						"access_rights" => implode(",",$request->access_rights)
					]);
				} else {
					$data = ["success" => false, "message"=>"Role is not found"]; return Response::json($data, 200 ,[]);
				}
			} else {
				DB::table('roles')->insert([
					"title"=>$request->title,
					"access_rights"=> implode(",",$request->access_rights),
					"client_id" => $user->client_id
				]);
			}

			$data["success"] = true;
			$data["message"] = "Role is updated successfully";
		} else {
            $data["success"] = false;
			$data["message"] = $validator->errors()->first();
		}

		return Response::json($data, 200 ,[]);
	}

	public function deleteRoles(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("admin_rights",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $success = true;

		$check = DB::table("roles")->where("id",$request->id)->where("client_id",$user->client_id)->first();
		if ($check){
			
			$check_users = DB::table("users")->where("client_id",$user->client_id)->where("role",$request->id)->where("inactive",0)->where("deleted",0)->count();
			if($check_users == 0){
				DB::table("roles")->where("id",$request->id)->where("client_id",$user->client_id)->delete();
				$message = "Role is successfully deleted";
			} else {
				$success = false;
				$message = "There are ".$check_users." users available with this role. Kindly re-assign them to delete this role";
			}
		} else {
			$success = false;
			$message = "Role does not exist";
		}

		$data["success"] = $success;
		$data["message"] = $message;

		return Response::json($data, 200 ,[]);
	}

	public function getUsers(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
        
        $user_access = User::getAccess("users",$user->id);
		
		$user_list = DB::table('users')->select('users.id','users.name','users.username','users.mobile','users.role','roles.title','city.city_name','users.inactive')->leftJoin('roles','users.role','=','roles.id')->leftJoin('city','city.id','=','users.city_id')->where("users.client_id",$user->client_id);

		if(!$user_access->all_access){
			$user_list = $user_list->whereIn("users.city_id",$user_access->city_ids)->where("users.role","!=",1);
		}

		$user_list = $user_list->orderBy("users.inactive","ASC")->orderBy("users.name")->get();

		$data["users_list"] = $user_list;
		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function editUser(Request $request, $user_id){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$user_row = User::where("id",$user_id)->where("client_id",$user->client_id)->first();

		if($user_row){

			$check_access = User::getAccess("users",$user->id, $user_row->city_id, "city_ids");
	        if(!$check_access) {
	            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
	        }

			$user_row->sports_ids = DB::table('user_sports')->select('sport_id')->where('user_id',$user_id)->pluck("sport_id")->toArray();

			$user_row->doj = Utilities::convertDate($user_row->doj);

			$success = true;

		} else {
			$success = false;
			$message = "User not found";
		}
		
		$data["success"] = $success;
		$data["user"] = $user_row;
		$data["sports"] = DB::table('sports')->get();

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function getRoles(Request $request){

		$client_id = $request->header("clientId");

		$user_roles = DB::table('roles')->select('id','title','access_rights')->whereIn("client_id",[$client_id, 0])->get();

		$data["user_roles"] = $user_roles;
		$data["success"] = true;

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function saveUser(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
		
		$values = [
			"name" => $request->name,
            "username" => $request->username,
            "role" => $request->role,
            "city_id" => $request->city_id
        ];

        $rules = [
            "name" => "required",
            "username" => "required|unique:users,username",
            "role" => "required",
            "city_id" => "required"
        ];

        if($request->id){
        	$rules["username"] = "required|unique:users,username,".$request->id;
        	// $rules["email"] = "unique:users,email,".$request->id;
        }

        $validator = Validator::make($values,$rules);

        if ($validator->passes()) {

        	$check_access = User::getAccess("users",$user->id, $request->city_id, "city_ids");
	        if(!$check_access) {
	            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
	        }

			if($request->id){
	    		
	    		$user_row = User::where("id",$request->id)->where("client_id",$user->client_id)->first();
		        if(!$user_row) {
		            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
		        }

	    		if(!$user_row->api_key)$user_row->api_key = Hash::make($user_row->username);
	    		$user_row->name = $request->name;
	    		$user_row->email = $request->email;
		        $user_row->role = $request->role;
		        $user_row->mobile = $request->mobile;
		        $user_row->city_id = $request->city_id;
		        $user_row->address = $request->address;
		        $user_row->emp_code = $request->emp_code;
		        $user_row->doj = Utilities::convertDateToDB($request->doj);
		        $user_row->gender = $request->gender;
		        $user_row->license = $request->license;
        		$user_row->save();

        		$this->checkAndRemoveRights($user_row->id, $request->role);
	        	
	        	// DB::table('user_sports')->where('user_id',Input::get("id"))->delete();
	        	// $sports = Input::get('sports_ids');
	        	// if(sizeof($sports) > 0){
	        	// 	foreach ($sports as  $value) {
	        	// 		DB::table('user_sports')->insert([
	        	// 			"user_id"=>Input::get("id"),
	        	// 			"sport_id"=>$value
	        	// 		]);
	        	// 	}
	        	// }

	        	$data["id"] = $user_row->id;
	        	$data["new_user"] = false;
				$message = "User is successfully updated";

	    	} else {
	    		$user_row = new User;
	    		$user_row->name = $request->name;
	    		$user_row->username = $request->username;
	    		$user_row->email = $request->email;
	    		$user_row->mobile = $request->mobile;
		        $user_row->role = $request->role;
		        $user_row->city_id = $request->city_id;
	    		
	    		$password = User::getRandPassword();
	    		$user_row->password = Hash::make($password);
	    		$user_row->password_check = $password;
	    		
	    		$user_row->api_key = Hash::make($user_row->username);
	        	$user_row->client_id = $user->client_id;
        		$user_row->save();

	        	$data["id"] = $user_row->id;
	        	$data["new_user"] = true;

	        	if($user_row->email){
	        		User::sendWelcomeEmail($user, $password);
	        	}

	        	$message = "User is successfully added";
        	}

        	$data["success"] = true;
        	$data["message"] = $message;

        } else {
			$data["success"] = false;
	        $data["message"] = $validator->errors()->first(); 
        }

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function deleteUser(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$success = true;

		$user_row = User::where("id",$request->user_id)->where("client_id",$user->client_id)->first();

		if($user_row){

			$check_access = User::getAccess("users",$user->id, $user_row->city_id, "city_ids");
	        
	        if(!$check_access) {
	            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
	        }

			DB::table('users')->where('id',$request->user_id)->update(array(
				"inactive" => $user_row->inactive == 1 ? 0 : 1
			));

			if($user_row->inactive == 0){
				$data["inactive"] = 1;
				$message = "User is successfully marked inactive";
			} else {
				$data["inactive"] = 0;
				$message = "User is successfully marked active";
			}

		} else {
			$success = false;
			$message = "User not found";
		}

		$data['success'] = $success;
		$data['message'] = $message;

		return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
	}

	public function accessRightsLocation(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$user_id = $request->user_id;
		$role_id = $request->role_id;

		$role = DB::table("roles")->whereIn("client_id",[$user->client_id,0])->where("id",$role_id)->first();
		
		if($role){
			
			$access_right_ids = explode(',', $role->access_rights);
			$access_rights = DB::table("access_rights")->whereIn("id",$access_right_ids)->get();

			foreach ($access_rights as $access_right) {
				
				$access_right->locations = DB::table("user_location_rights")
				->select('user_location_rights.id','access_rights.access_rights','groups.group_name',
					'user_location_rights.city_id','city.city_name',
					'center.center_name')
				->where("access_rights_id",$access_right->id)
				->where('user_id',$user_id)
				->leftJoin('access_rights','access_rights.id','=','user_location_rights.access_rights_id')
				->leftJoin('city','city.id','=','user_location_rights.city_id')
				->leftJoin('center','center.id','=','user_location_rights.center_id')
				->leftJoin('groups','groups.id','=','group_id')
				->get();
			}
			$data["access_rights"] = $access_rights;
			$data["success"] = true;
		} else {
			$data["message"] = "You are not allowed to create access";
			$data["success"] = false;
		}

		return Response::json($data,200,array());
	}

	public function addAccessRightsLocation(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$insert_location = DB::table('user_location_rights');
		$user_id = $request->user_id;
		$access_right_id = $request->access_type_id;
		$role_id = $request->role_id;
		$message = "";

		$check_user = DB::table("users")->where("id",$user_id)->where("client_id",$user->client_id)->first();

		if($check_user){

			$user_access = User::getAccess("users",$user->id);
	        $add_all_access = false;

	        if($user_access->all_access){
	        	$add_all_access = true;
	        } else {
	        	if(!in_array($check_user->city_id,$user_access->city_ids)) {
		            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
		        }
	        }

	        if(!$add_all_access && $request->city_id == -1){
	        	$data = ["success" => false, "message"=>"Oops! You are not allowed to add all access. Please contact admin"]; return Response::json($data, 200 ,[]);
	        }

			$this->checkAndRemoveRights($user_id,$role_id);

			$success = true;	
			if ($request->modal_id == 1) {
				$check = DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("level",1)->count();
				if($check == 0){
					DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->delete();
					$insert_location->insert([
						"user_id"=>$user_id,
						"access_rights_id"=>$access_right_id,
						"city_id"=>$request->city_id,
						"level"=>1
					]);
				} else {
					$success = false;
					$message = "All city access is already defined";
				}
			} elseif ($request->modal_id == 2) {

				$check = DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("level",1)->count();
				if($check > 0){
					$success = false;
					$message = "All city access is already defined";
				} else {
					$check = DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("city_id",$request->city_id)->whereIn("level",[1,2])->count();
					if($check > 0){
						$success = false;
						$message = "This city is already added";
					}
				}

				if($success){
					DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("city_id",$request->city_id)->whereIn("level",[3,4])->delete();
					$insert_location->insert([
						"user_id" => $user_id,
						"access_rights_id" => $access_right_id,
						"city_id" => $request->city_id,
						"level" => 2,
					]);
				}
			} elseif ($request->modal_id == 3) {

				$check = DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("level",1)->count();
				if($check > 0){
					$success = false;
					$message = "All city access is already defined";
				} else {
					$check = DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("city_id",$request->city_id)->whereIn("level",[1,2])->count();
					if($check > 0){
						$success = false;
						$message = "This city is already added";
					}
				}

				if($success){
					foreach ($request->centers_ids as  $center_id) {
						DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("center_id",$center_id)->whereIn("level",[3,4])->delete();
						$insert_location->insert([
							"user_id"=>$user_id,
							"access_rights_id"=>$access_right_id,
							"city_id"=>$request->city_id,
							"center_id"=>$center_id,
							"level"=>3,
						]);
					}
				}
			} elseif ($request->modal_id == 4) {
				$check = DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("level",1)->count();
				if($check > 0){
					$success = false;
					$message = "All city access is already defined";
				} else {
					$check = DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("center_id",$request->center_id)->whereIn("level",[1,2,3])->count();
					if($check > 0){
						$success = false;
						$message = "This center is already added";
					}else{
						$check = DB::table("user_location_rights")->where("user_id",$user_id)->where("access_rights_id",$access_right_id)->where("city_id",$request->city_id)->whereIn("level",[1,2,3])->count();
						if($check > 0){
							$success = false;
							$message = "This city is already added";
						}
					}
				}

				if ($success) {
					foreach ($request->groups_ids as  $group_id) {

						$insert_location->insert([
							"user_id"=>$user_id,
							"access_rights_id"=>$access_right_id,
							"city_id"=>$request->city_id,
							"center_id"=>$request->center_id,
							"group_id"=>$group_id,
							"level"=>4,
						]);
					}
				}

			} else{
				$success = false;
				$message = "Invalid entry";
			}
		} else {
			$success = false;
			$message = "User not found";
		}

		$access_right = DB::table("access_rights")->find($access_right_id);
		Cache::forget($access_right->tag."-".$user_id);

		$data["success"] = $success;
		$data["message"] = $message;

		return Response::json($data,200,array());
	}

	public function deleteAccessRightsLocation(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$user_id = $request->user_id;
		$access_location_id = $request->access_location_id;

		$check_user = DB::table("users")->where("id",$user_id)->where("client_id",$user->client_id)->first();
		if ($check_user) {
			
			$check_access = User::getAccess("users",$user->id, $check_user->city_id, "city_ids");
	        if(!$check_access) {
	            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
	        }

			$access_location = DB::table("user_location_rights")->where("id",$access_location_id)->where("user_id",$user_id)->first();

			if($access_location){
				DB::table("user_location_rights")->where("id",$access_location_id)->where("user_id",$user_id)->delete();

				$access_right = DB::table("access_rights")->find($access_location->access_rights_id);
				Cache::forget($access_right->tag."-".$user_id);
			}
			
			$data["success"] = true;

		} else {
			$data["success"] = false;
		}

		return Response::json($data,200,array());
	}

	public function copyAccessRightsLocation(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$user_id = $request->user_id;
		$access_right_id = $request->access_right_id;

		$check_user = DB::table("users")->where("id",$user_id)->where("client_id",$user->client_id)->first();
		if($check_user){
			$success = true;

			$user_access = User::getAccess("users",$user->id);
	        $add_all_access = false;

	        if($user_access->all_access){
	        	$add_all_access = true;
	        } else {
	        	if(!in_array($check_user->city_id,$user_access->city_ids)) {
		            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
		        }
	        }

	        $role = DB::table("roles")->where("id",$user->role)->first();
			if($role){
				$access_right_ids = explode(',',$role->access_rights);
				if(sizeof($access_right_ids) > 0){

					$access_rights = DB::table("access_rights")->whereIn("id",$access_right_ids)->where("id","!=",$access_right_id)->get();

					$base_rights = DB::table("user_location_rights")->where("access_rights_id",$access_right_id)->where("user_id",$user_id)->get();

					foreach($access_rights as $access_right){
						DB::table("user_location_rights")->where("access_rights_id",$access_right->id)->where("user_id",$user_id)->delete();
						foreach($base_rights as $base_right){

							$valid = true;

							if($access_right->geography_limit == 1 && $base_right->city_id != -1){
								$valid = false;
							}

							if($access_right->geography_limit == 2 && $base_right->center_id){
								$valid = false;
							}

							if($access_right->geography_limit == 3 && $base_right->group_id){
								$valid = false;
							}

							if($valid){
								DB::table("user_location_rights")->insert(array(
									"user_id" => $user_id,
									"access_rights_id" => $access_right->id,
									"city_id" => $base_right->city_id,
									"center_id" => $base_right->center_id,
									"group_id" => $base_right->group_id,
									"level" => $base_right->level,
								));
							}

						}

						Cache::forget($access_right->tag."-".$user_id);
					}
				}
			}
			$message = "Successfully copied.";

	    } else {
	    	$success = false;
			$message = "User not found";
	    }

		$data["success"] = $success;
		$data["message"] = $message;
		return Response::json($data,200,array());
	}

	private function checkAndRemoveRights($user_id, $role_id){

		$user = User::find($user_id);
		
		if($user->role != $role_id){
			$user->role = $role_id;
			$user->save();
		}

		if($user->role > 1){
			$role = DB::table("roles")->where("id",$user->role)->first();
			if($role){
				$access_rights = explode(',',$role->access_rights);
				if(sizeof($access_rights) == 0) $access_rights = [0];

				DB::table("user_location_rights")->where("user_id",$user_id)->whereNotIn("access_rights_id",$access_rights)->delete();
			}
		}

	}

}
