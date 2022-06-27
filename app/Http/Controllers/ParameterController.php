<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\PaymentHistory, App\Models\PaymentItem, App\Models\User, App\Models\Student, App\Models\Parameter, App\Models\Attribute;

class ParameterController extends Controller
{	
    public function index(){  
        return view('parameters.index', ["id"=>0,'sidebar' => "parameter","menu" => "admin"]);
    }

    public function parameters(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $skill_categories = DB::table('skill_categories')->where("client_id",$user->client_id)->where("sport_id",$request->sport_id)->where('inactive',0)->get();

        foreach ($skill_categories as $category) {
            $category->attributes = DB::table('skill_attributes')->where("category_id",$category->id)->where('inactive',0)->get();
        }

        $data['success'] = true;
        $data['skill_categories'] = $skill_categories;
        return Response::json($data,200,array());

    }

    public function getGroupTypes(){
        $group_types = DB::table('group_types')->select('id','name')->get();
        $data['success'] = true;
        $data['group_types'] = $group_types;
        return Response::json($data,200,array());
    }

    public function saveCategory(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("performance-params",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $cre = [
            "category_name" => $request->category_name
        ];
        $validator = Validator::make($cre, [
            "category_name" => "required"
        ]);

        if ($validator->passes()) {

            if($request->id){
                $parameter = Parameter::where("id",$request->id)->where("client_id",$user->client_id)->first();
            } else {
                $parameter = new Parameter;
                $parameter->client_id = $user->client_id;
                $parameter->sport_id = $request->sport_id;
            }
            
            if($parameter){
                $parameter->category_name = $request->category_name;
                $parameter->save();

                $data['success'] = true;
                $data['message'] = "Category is successfully updated";
            } else {
                $data['success'] = false;
                $data['message'] = "Category does not exists";
            }
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }
        
        return Response::json($data,200,array());  
    }

    public function deleteCategory(Request $request, $id){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("performance-params",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $check = DB::table('skill_categories')->where('id',$id)->where('client_id',$user->client_id)->first();
        if($check){
            DB::table('skill_categories')->where('id',$id)->where('client_id',$user->client_id)->update(array(
                "inactive" => 1
            ));

            $data['success'] = true;
            $data['message'] = "Category is successfully deleted";
        } else {
            $data['success'] = false;
            $data['message'] = "Category does not exists";
        }
        
        return Response::json($data,200,array()); 
    }

    public function saveAttribute(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("performance-params",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $cre = [
            "attribute_name" => $request->attribute_name
        ];
        $validator = Validator::make($cre, [
            "attribute_name" => "required"
        ]);

        if ($validator->passes()) {

            if($request->id){
                $attribute = Attribute::find($request->id);  
            } else {
                $attribute = new Attribute;
            }

            if($attribute){
                $attribute->attribute_name = $request->attribute_name;
                if($request->category_id){
                    $attribute->category_id    = $request->category_id;
                }
                $attribute->save();

                $data['success'] = true;
                $data['message'] = "Attribute is successfully update";
            } else {
                $data['success'] = false;
                $data['message'] = "Attribute does not exists";
            }
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }
        
        return Response::json($data,200,array());  
    }

    public function deleteAttribute(Request $request, $id){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("performance-params",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $check = DB::table('skill_attributes')->select("skill_attributes.id")->join("skill_categories","skill_categories.id","=","skill_attributes.category_id")->where('skill_attributes.id',$id)->where('skill_categories.client_id',$user->client_id)->first();
        if($check){
            DB::table('skill_attributes')->where('id',$id)->update(array(
                "inactive" => 1
            ));

            $data['success'] = true;
            $data['message'] = "Attribute is successfully deleted";
        } else {
            $data['success'] = false;
            $data['message'] = "Attribute does not exists";
        }

        return Response::json($data,200,array()); 
    }

    public function saveGroupSkillAttribute(Request $request){

        $skill_attribute_id = $request->skill_attribute_id;
        $group_type_id = $request->group_type_id;

        $check = DB::table('group_skill_attributes')->where('skill_attribute_id',$skill_attribute_id)->where('group_type_id',$group_type_id)->first();

        if($check){
            DB::table('group_skill_attributes')->where('id',$check->id)->delete();
            $value = 0;
        } else {
            DB::table('group_skill_attributes')->insert([
                "skill_attribute_id" => $skill_attribute_id,
                "group_type_id" => $group_type_id
            ]);
            $value = 1;
        }
        $data['success'] = true;
        $data['value'] = $value;
        $data['message'] = "Data successfully changed";
        return Response::json($data,200,array());
    }

    public function getGroupSkillAttribute($group_type_id){
        $skillAttributeIds = DB::table('group_skill_attributes')->select('skill_attribute_id')->where('group_type_id',$group_type_id)->pluck('skill_attribute_id')->toArray();
        $data['skillAttributeIds'] = $skillAttributeIds;
        $data['success'] = true;
        return Response::json($data,200,array());
    }
}
