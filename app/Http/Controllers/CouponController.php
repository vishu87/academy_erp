<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\User, App\Models\Coupon, App\Models\Utilities;

class CouponController extends Controller{ 

    public function coupons(){
        User::pageAccess(9);
        return view('payments.coupons.index',["sidebar" => "p_coupons","menu" => "accounts"]);
    }

    public function getCouponList(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $sport_id = $request->sport_id;
        
        $check_access = User::getAccess("pay_structure",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $coupons = DB::table('coupons')->select('coupons.*','payments_type_categories.category_name as catName','payments_type.name as subCatName')
        ->leftJoin('payments_type_categories','payments_type_categories.id','=','coupons.pay_type_cat_id')
        ->leftJoin('payments_type','payments_type.id','=','coupons.pay_type_id')
        ->where('coupons.status',1)
        ->where('coupons.client_id',$user->client_id)
        ->where('coupons.sport_id',$sport_id)
        ->where('coupons.status',1)
        ->get();


        foreach ($coupons as $coupon) {
            $coupon->locations = DB::table("coupon_mapping")
            ->select('coupon_mapping.id','groups.group_name',
                'coupon_mapping.city_id','city.city_name',
                'center.center_name','coupon_mapping.center_id','coupon_mapping.group_id')
            ->where("coupon_id",$coupon->id)
            ->leftJoin('city','city.id','=','coupon_mapping.city_id')
            ->leftJoin('center','center.id','=','coupon_mapping.center_id')
            ->leftJoin('groups','groups.id','=','group_id')
            ->get();

            $coupon->expiry_date = Utilities::convertDate($coupon->expiry_date);
        }
        $data["coupons"] = $coupons;
        $data["success"] = true;
        return Response::json($data, 200, []);
    }

    public function addCoupon(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("pay_structure",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $cre = [
            "code" => $request->code,
            "discount_type" => $request->discount_type,
            "discount" => $request->discount,
            "pay_type_cat_id" => $request->pay_type_cat_id,
            "pay_type_id" => $request->pay_type_id,
        ];

        $rules = [
            "code"=>"required",
            "discount_type"=>"required",
            "discount"=>"required",
            "pay_type_cat_id"=>"required",
            "pay_type_id"=>"required",
        ];

        $validation = Validator::make($cre,$rules);
        if($validation->passes()){

            if(isset($request->id)){
               $coupon = Coupon::find($request->id);   
            } else {
               $coupon = new Coupon;
               $coupon->client_id = $user->client_id;
               $coupon->added_by = $user->id;
            }
           
           $coupon->code = strtoupper($request->code);
           $coupon->discount_type = $request->discount_type;
           $coupon->discount = $request->discount;
           $coupon->sport_id = $request->sport_id;
           $coupon->pay_type_cat_id = $request->pay_type_cat_id;
           $coupon->pay_type_id = $request->pay_type_id;
           $coupon->expiry_date = $request->expiry_date ? date("Y-m-d",strtotime($request->expiry_date)) : null;

           $coupon->save();

           $data['message'] = 'Coupon is successfully saved';
           $data['success'] = true;

        } else {
            $data['success'] = false;
            $data['message'] = $validation->errors()->first();  
        }
        return Response::json($data, 200, []);  
    } 

    public function deleteCoupon(Request $request,$id){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("pay_structure",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        DB::table('coupons')->where('id',$id)->where('client_id',$user->client_id)->update(["status" => 0]);
        $data['success'] = true;
        $data['message'] = "Coupon is successfully marked inactive";

        return Response::json($data, 200, []);   
    }

    public function addAvailibility(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("pay_structure",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }
        
        $availibilityData = isset($request->availibility) ? $request->availibility : '';

        $success = true;
        $message = "Successfully updated";
        
        if($availibilityData['modal_id'] == 1){
            $check = DB::table('coupon_mapping')->where('coupon_id',$request->coupon_id)->where('city_id',-1)->count();
            if($check == 0){
                DB::table('coupon_mapping')->insert([
                    "coupon_id"  => $request->coupon_id,
                    "city_id"    => $availibilityData["city_id"],
                ]);
            }
        } elseif($availibilityData['modal_id'] == 2){

            $check = DB::table('coupon_mapping')->where('coupon_id',$request->coupon_id)->where('city_id',$availibilityData["city_id"])->whereNull("center_id")->whereNull("group_id")->count();

            if($check == 0){
                DB::table('coupon_mapping')->insert([
                    "coupon_id"  => $request->coupon_id,
                    "city_id"    => $availibilityData["city_id"],
                ]);
            }

        } elseif($availibilityData['modal_id'] == 3){
            
            foreach ($availibilityData['centers_ids'] as $center_id) {
                $check = DB::table('coupon_mapping')->where('coupon_id',$request->coupon_id)->where('center_id',$center_id)->whereNull('group_id')->count();
                if($check == 0){
                    DB::table('coupon_mapping')->insert([
                        "coupon_id"  => $request->coupon_id,
                        "center_id"  => $center_id,
                        "city_id"    => $availibilityData['city_id'],
                    ]);
                }
            }

        } elseif($availibilityData['modal_id'] == 4){
            foreach ($availibilityData['groups_ids'] as $group_id) {
                $check = DB::table('coupon_mapping')->where('coupon_id',$request->coupon_id)->where('group_id',$group_id)->count();
                if($check == 0){
                    DB::table('coupon_mapping')->insert([
                        "coupon_id"  => $request->coupon_id,
                        "group_id"   => $group_id,
                        "center_id"  => $availibilityData['center_id'],
                        "city_id"    => $availibilityData['city_id'],
                    ]);
                }
            }
        } else {
            $success = false;
            $message = "Error in updating";
        }

        $data['success'] = $success;
        $data['message'] = $message;
        return Response::json($data, 200, []);
    }

    public function deleteAvailibility(Request $request, $id){
        $user = User::AuthenticateUser($request->header("apiToken"));

        $coupon_mapping = DB::table('coupon_mapping')->select("coupon_mapping.id","coupons.client_id")->join("coupons","coupons.id","=","coupon_mapping.coupon_id")->where("coupon_mapping.id",$id)->first();

        $check_access = User::getAccess("pay_structure",$user->id, -1);
        if(!$check_access || $coupon_mapping->client_id != $user->client_id) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        DB::table('coupon_mapping')->where('id',$id)->delete();
        
        $data['message'] = 'data successfully deleted';
        $data['success'] = true;
        return Response::json($data, 200, []);

    }

}


                 