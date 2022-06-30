<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\PaymentHistory, App\Models\PaymentItem, App\Models\User;

class PayTypeCategoryController extends Controller{ 

    public function categoryList(){
        return view('payments.pay_type_category.category_list',["sidebar" => "p_categories","menu" => "accounts"]);
    }

    public function getList(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("pay_categories",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $pay_type_cat = DB::table('payments_type_categories')->where("sport_id",$request->sport_id)->whereIn("client_id",[$user->client_id,0])->orderBy("inactive","ASC")->get();
        $pay_type_ids = [];
        foreach($pay_type_cat as $cat){
            $pay_type_ids[] = $cat->id;
        }
        $pay_type = DB::table('payments_type')->where("client_id",$user->client_id)->whereIn("category_id",$pay_type_ids)->where('inactive',0)->get();

        $data['pay_type_cat'] = $pay_type_cat;
        $data['pay_type'] = $pay_type;
        $data['success'] = true;

        return Response::json($data, 200, array());
    }

    public function addCategory(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("pay_categories",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $validator = Validator::make([
            "category_name" => $request->category_name
            ],[
            "category_name" => "required"
        ]);

        if ($validator->passes()) {
            $data['success'] = true;
            if($request->id){
                DB::table('payments_type_categories')->where("id",$request->id)->where("client_id",$user->client_id)->update([
                    "category_name"=>$request->category_name
                ]);
                $data['message'] = "Category updated successfuly";
            } else {
                DB::table('payments_type_categories')->insert([
                    "category_name"=>$request->category_name,
                    "client_id"=> $user->client_id,
                    "sport_id"=> $request->sport_id,
                ]);
                $data['message'] = "Category Addded successfuly";
            }
        } else {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }  
        return Response::json($data, 200, array());
    }

    public function disableCategory(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));
        $check_access = User::getAccess("pay_categories",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $checkExist = DB::table('payments_type_categories')->where("client_id",$user->client_id)->where("id",$request->id)->first();

        if ($checkExist) {
            DB::table('payments_type_categories')->where("id",$request->id)->update([
                "inactive" => $checkExist->inactive == 0 ? 1 : 0
            ]);
            $data['success'] = true;
            $data['message'] = "Category is disabled successfully";
        } else {
            $data['success'] = false;
            $data['message'] = "Category does not exist";
        }
        return Response::json($data, 200, array());
    }

    public function deleteCategory(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("pay_categories",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $checkExist = DB::table('payments_type_categories')->where("client_id",$user->client_id)->where("id",$request->id)->first();
        if ($checkExist) {
            $check = DB::table('payment_items')->where("client_id",$user->client_id)->where('category_id',$request->id)->count();
            if ($check == 0) {
                DB::table('payments_type_categories')->where("id",$request->id)->delete();
                $data['success'] = true;
                $data['message'] = "Category is deleted successfully";
            } else {
                $data['success'] = false;
                $data['message'] = "Category has payments in past, so can not be deleted";
            }
        } else {
            $data['success'] = false;
            $data['message'] = "Category does not exist";
        }
        return Response::json($data, 200, array());
    }

    public function add(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("pay_categories",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $data_check = [
            "name" => $request->name,
            "category_id" => $request->category_id,
            "tax" => $request->tax,
        ];
        $rules = [
            "name"=>"required",
            "category_id"=>"required",
            "tax"=>"required",
        ];

        $category = DB::table("payments_type_categories")->find($request->category_id);
        if($category){
            if($category->is_sub_type == 1){
                $data_check["months"] = $request->months;
                $rules["months"] = "required";
            }
        }

        $validator = Validator::make($data_check,$rules);
        if ($validator->passes()) {

            $data_check = [
                "name" => $request->name,
                "category_id" => $request->category_id,
                "hsn_code" => $request->hsn_code,
                "months" => $request->months,
                "tax" => $request->tax,
                "no_pause" => $request->no_pause
            ];
            
            if($request->id){
                DB::table('payments_type')->where("id",$request->id)->where("client_id",$user->client_id)->update($data_check);
            } else {
                $data_check["client_id"] = $user->client_id;
                DB::table('payments_type')->insert($data_check);
            }

            $data['success'] = true;
            $data['message'] = "Item is added successfuly";
        }else{
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }

        return Response::json($data, 200, array());
    }

    public function delete(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        $check_access = User::getAccess("pay_categories",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $checkExist = DB::table('payments_type')->select('payments_type.*')->join("payments_type_categories","payments_type_categories.id","=","payments_type.category_id")->where("payments_type_categories.client_id",$user->client_id)->where("payments_type.id",$request->id)->first();

        if ($checkExist) {
            $check = DB::table('payment_items')->where("client_id",$user->client_id)->where('type_id',$request->id)->count();
            if ($check == 0) {
                DB::table('payments_type')->where("id",$request->id)->delete();
                $data['success'] = true;
                $data['message'] = "Item is deleted successfully";
            } else {
                $data['success'] = false;
                $data['message'] = "Item has payments in past, so can not be deleted";
            }
        } else {
            $data['success'] = false;
            $data['message'] = "Item does not exist";
        }

        return Response::json($data, 200, array());
    }

   
}