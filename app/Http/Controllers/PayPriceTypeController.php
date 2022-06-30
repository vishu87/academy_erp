<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\PaymentHistory, App\Models\PaymentItem, App\Models\User;

class PayPriceTypeController extends Controller{ 

    public function type_price_list(){
        User::pageAccess(8);
        return view('payments.type_price.payment_type_price',["sidebar" => "p_structure","menu" => "accounts"]);
    }

    public function getPayType(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("pay_structure",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $payTypeCat = DB::table('payments_type_categories')->where("sport_id",$request->sport_id)->whereIn("client_id",[$user->client_id,0])->where("inactive","0")->get();
        $pay_type_ids = [];
        foreach($payTypeCat as $cat){
            $pay_type_ids[] = $cat->id;
        }
        
        $payType = DB::table('payments_type')->where("client_id",$user->client_id)->whereIn("category_id",$pay_type_ids)->where('inactive',0)->get();

        $data['pay_type_cat'] = $payTypeCat;
        $data['pay_type'] = $payType;
        $data['success'] = true;
        return Response::json($data, 200, array());
    }

    public function getList(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $user_access = User::getAccess("pay_structure",$user->id);

        $type_id = $request->type_id;
        
        $list = DB::table('payment_type_prices as ptp')->select('ptp.id','ptp.city_id','ptp.center_id','ptp.group_id','ptp.pay_type_cat_id','ptp.pay_type_id','ptp.price','pt.tax','ptp.total','city.city_name','center.center_name','groups.group_name','ptc.category_name','pt.name as pay_type_name')
        ->where('ptp.pay_type_id',$type_id)
        ->leftJoin('city','city.id','=','ptp.city_id')
        ->leftJoin('center','center.id','=','ptp.center_id')
        ->leftJoin('groups','groups.id','=','ptp.group_id')
        ->leftJoin('payments_type_categories as ptc','ptc.id','=','ptp.pay_type_cat_id')
        ->leftJoin('payments_type as pt','pt.id','=','ptp.pay_type_id');

        if(!$user_access->all_access){
            $city_ids = sizeof($user_access->city_ids) > 0 ? $user_access->city_ids : [0];
            $list = $list->whereIn("ptp.city_id",$user_access->city_ids);
        }

        $list = $list->orderBy("ptp.city_id")->orderBy("ptp.center_id")->get();

        $texPercentage = DB::table('payments_type')->select('tax')->where('payments_type.id',$type_id)->first();
        
        $data['success'] = true;
        $data['list'] = $list;
        $data['texPercentage'] = $texPercentage;
        return Response::json($data, 200, array());
    }

    public function add(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $user_access = User::getAccess("pay_structure",$user->id);
        
        $data_check = [
            "modal_id" => $request->modal_id
        ];
        $rules = [
            "modal_id"=>"required"
        ];

        $validator = Validator::make($data_check,$rules);
        
        if ($validator->passes()) {

            if(!$user_access->all_access){
                if(!in_array($request->city_id,$user_access->city_ids)){
                    $data['success'] = false;
                    $data['message'] = "You are not allowed to add access to this location";
                    return Response::json($data, 200, array());
                }
            }

            if ($request->modal_id == 1) {

                $checker = DB::table('payment_type_prices')->where('pay_type_id','=',$request->pay_type_id)->where('city_id','=',$request->city_id)->whereNull('center_id')->whereNull('group_id')->where("client_id",$user->client_id)->first();

                if($checker){
                    DB::table('payment_type_prices')->where('id',$checker->id)->update([
                        "price"=>$request->price,
                        "tax"=>$request->tax,
                        "total"=>$request->total_amt,
                    ]);
                } else {
                    DB::table('payment_type_prices')->insert([
                        "city_id"=>$request->city_id,
                        "pay_type_cat_id"=>$request->pay_type_cat_id,
                        "pay_type_id"=>$request->pay_type_id,
                        "price"=>$request->price,
                        "tax"=>$request->tax,
                        "total"=>$request->total_amt,
                        "client_id" => $user->client_id
                    ]);
                }

            } else if ($request->modal_id == 2) {
                if(isset($request->city_ids)){
                   foreach ($request->city_ids as $city_id) {

                        $checker = DB::table('payment_type_prices')
                        ->where('pay_type_id','=',$request->pay_type_id)
                        ->where("city_id",'=',$city_id)->whereNull('center_id')
                        ->whereNull('group_id')->where("client_id",$user->client_id)->first();

                        if($checker){
                            DB::table('payment_type_prices')->where('id',$checker->id)->update([
                                "price" => $request->price,
                                "tax" => $request->tax,
                                "total" => $request->total_amt,
                            ]);  
                        } else {
                            DB::table('payment_type_prices')->insert([
                                "city_id" => $city_id,
                                "pay_type_cat_id" => $request->pay_type_cat_id,
                                "pay_type_id" => $request->pay_type_id,
                                "price" => $request->price,
                                "tax" => $request->tax,
                                "total" => $request->total_amt,
                                "client_id" => $user->client_id
                            ]);  
                        }
                    }
                }
            } else if ($request->modal_id == 3) {
                if(isset($request->centers_ids)){
                    foreach ($request->centers_ids as $center_id) {
                        $checker = DB::table('payment_type_prices')
                        ->where('pay_type_id',$request->pay_type_id)
                        ->where("city_id",$request->city_id)
                        ->where('center_id',$center_id)
                        ->whereNull('group_id')->where("client_id",$user->client_id)->first();

                        if($checker) {
                            DB::table('payment_type_prices')->where('id',$checker->id)->update([
                                "price" => $request->price,
                                "tax" => $request->tax,
                                "total" => $request->total_amt,
                            ]);
                        } else {
                            DB::table('payment_type_prices')->insert([
                                "city_id" => $request->city_id,
                                "center_id" => $center_id,
                                "pay_type_cat_id" => $request->pay_type_cat_id,
                                "pay_type_id" => $request->pay_type_id,
                                "price" => $request->price,
                                "tax" => $request->tax,
                                "total" => $request->total_amt,
                                "client_id" => $user->client_id
                            ]);  
                        }
                    }
                }
            } else if($request->modal_id == 4) {
                if(isset($request->groups_ids)){
                    foreach ($request->groups_ids as $group_id) {

                        $checker = DB::table('payment_type_prices')
                        ->where('pay_type_id','=',$request->pay_type_id)
                        ->where("city_id",'=',$request->city_id)
                        ->where('center_id','=',$request->center_id)
                        ->where('group_id','=',$group_id)->where("client_id",$user->client_id)->first();

                        if ($checker) {
                            DB::table('payment_type_prices')->where('id',$checker->id)->update([
                                "price"=>$request->price,
                                "tax"=>$request->tax,
                                "total"=>$request->total_amt,
                            ]); 
                        } else {
                            DB::table('payment_type_prices')->insert([
                                "city_id"=>$request->city_id,
                                "center_id"=>$request->center_id,
                                "group_id"=>$group_id,
                                "pay_type_cat_id"=>$request->pay_type_cat_id,
                                "pay_type_id"=>$request->pay_type_id,
                                "price"=>$request->price,
                                "tax"=>$request->tax,
                                "total"=>$request->total_amt,
                                "client_id" => $user->client_id
                            ]);
                        }
                    }
                }
            }

            $data['success'] = true;
            $data['message'] = "Data is submitted successfully";

            return Response::json($data, 200, array());

        } else {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }

        return Response::json($data, 200, array());
    }

    public function update(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $user_access = User::getAccess("pay_structure",$user->id);

        $entry = DB::table('payment_type_prices')->where("id",$request->id)->where("client_id",$user->client_id)->first();

        if ($entry) {
             DB::table('payment_type_prices')->where('id',$request->id)
             ->update([
                "price" => $request->price,
                "tax" => $request->tax,
                "total" => $request->total_amt,
            ]);

            $data['success'] = true;
            $data['message'] =" Item is updated successfully";
        } else {
            $data['success'] = false;
            $data['message'] =" Item does not exists";
        }

        $data['entry'] = $entry;
        return Response::json($data, 200, array());
    }

    public function delete(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("pay_structure",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $id = $request->id;

        $target = DB::table('payment_type_prices')->where("id",$id)->where("client_id",$user->client_id)->first();
        if ($target) {
            DB::table('payment_type_prices')->where('id',$id)->delete();
            $data['success'] = true;
            $data['message'] = "Entry is successfully deleted";
        } else {
            $data['success'] = false;
            $data['message'] = "Error found";
        }

        $data['target'] = $target;
        return Response::json($data, 200, array());
    }
   
}


                 