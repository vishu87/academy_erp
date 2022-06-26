<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB;
use Illuminate\Http\Request;

use App\Models\PaymentHistory, App\Models\Client, App\Http\Controllers\PaymentController, App\Models\Student;

class SubscriptionController extends Controller
{	

    private function get_key(){
        return 'rzp_test_99Y5RZnNylx6kY';
    }
    private function get_secret(){
        return 'OyIopP4QIeecC1BwqZwQJMDc';
    }

    public function getPaymentOptions(Request $request){

        $group_id = $request->group_id;
        $payment_code = $request->payment_code;

        $codes = explode('|',$payment_code);
        $mandatory = explode(',',$codes[0]);
        $category_ids = array_merge(explode(',',$codes[0]), explode(',',$codes[1]));
        $fixed = explode(',',$codes[2]);

        if(sizeof($fixed) > 0){
            $fix_category_ids = DB::table("payments_type")->whereIn("id",$fixed)->pluck("category_id")->toArray();
            if(sizeof($fix_category_ids) == 0) $fix_category_ids = [0];
        } else {
            $fix_category_ids = [0];
        }

        $payment_categories = [];

        foreach($category_ids as $payment_category_id){
            $category = DB::table("payments_type_categories")->select("id","category_name")->where("id",$payment_category_id)->first();
            
            if($category && !in_array($payment_category_id, $fix_category_ids)){
                $category->label = "Select ".$category->category_name;
                $category->types = DB::table("payments_type")->select("id as value","name as label")->where("category_id",$category->id)->get();
                $category->type_id = "";
                if(in_array($category->id,$mandatory)){
                    $category->required = true;
                } else {
                    $category->required = false;
                }
                $category->group_id = $group_id;
                $payment_categories[] = $category;
            }
        }
        
        $data["success"] = true;
        $data["payment_options"] = $payment_categories;

        return Response::json($data, 200, []);
    }

    public function paymentItems(Request $request){

        $client_code = $request->header("clientId");
        $client = Client::AuthenticateClient($client_code);

        $group_id = $request->group_id;
        $categories = $request->categories;
        $payment_code = $request->payment_code;

        $coupon_code = $request->coupon_code;
        if($coupon_code){
            $coupon = DB::table("coupons")->where("code",$coupon_code)->where("client_id",$client->id)->first();
        } else {
            $coupon = null;
        }

        $codes = explode('|',$payment_code);
        $mandatory = explode(',',$codes[0]);
        $optional = explode(',',$codes[1]);
        $fixed = explode(',',$codes[2]);

        $total_amount = 0;
        $total_discount = 0;

        $payment_items = [];
        foreach($fixed as $fix_type_id){
            if($fix_type_id){
                $category = DB::table("payments_type")->select("category_name")->join("payments_type_categories","payments_type_categories.id","=","payments_type.category_id")->where("payments_type.id",$fix_type_id)->first();
                
                $price = PaymentHistory::getAmount($group_id,$fix_type_id,$coupon);
                $payment_items[] = [
                    "category" => $category->category_name,
                    "type_id" => $fix_type_id,
                    "amount" => $price->amount,
                    "discount" => $price->discount,
                    "discount_code_id" => $price->discount_code_id,
                    "taxable_amount" => $price->taxable_amount,
                    "tax_perc" => $price->tax_perc,
                    "tax" => $price->tax,
                    "total_amount" => $price->total_amount
                ];
                $total_amount += $price->total_amount;
                $total_discount += $price->discount;
            }
            
        }

        foreach($categories as $category){
            if($category["type_id"]){
                $price = PaymentHistory::getAmount($group_id,$category["type_id"],$coupon);
                $payment_items[] = [
                    "category" => $category["category_name"],
                    "type_id" => $category["type_id"],
                    "amount" => $price->amount,
                    "discount" => $price->discount,
                    "discount_code_id" => $price->discount_code_id,
                    "taxable_amount" => $price->taxable_amount,
                    "tax_perc" => $price->tax_perc,
                    "tax" => $price->tax,
                    "total_amount" => $price->total_amount
                ];
                $total_amount += $price->total_amount;
                $total_discount += $price->discount;
                
            }
        }
        
        $data["success"] = true;
        $data["payment_items"] = $payment_items;
        $data["total_amount"] = $total_amount;
        $data["total_discount"] = $total_discount;

        if($data["total_discount"] > 0){
            $data["coupon_code_message"] = "You have recieved a total discount of Rs. ".$total_discount;
        } else {
            $data["coupon_code_message"] = "Sorry! No discount is available for the coupon code in selected items";
        }

        return Response::json($data, 200, []);
    }

    public function checkCoupon(Request $request){

        $client_code = $request->header("clientId");
        $client = Client::AuthenticateClient($client_code);

        $success = false;
        $message = "Invalid coupon code";

        $group_id = $request->group_id;
        $coupon_code = $request->coupon_code;

        $coupon = DB::table("coupons")->where("code",$coupon_code)->where("client_id",$client->id)->first();
        if($coupon){

            $group = DB::table("groups")->select("groups.id as group_id","groups.center_id","center.city_id")->join("center","center.id","=","groups.center_id")->where("groups.id",$group_id)->first();

            $group_id = $group->group_id;
            $center_id = $group->center_id;
            $city_id = $group->city_id;

            $mapped_coupons = DB::table("coupon_mapping")->where("city_id",-1)->orWhere(function($query) use ($city_id){
                $query->where("city_id",$city_id)->whereNull("center_id");
            })->orWhere(function($query) use ($city_id, $center_id){
                $query->where("city_id",$city_id)->where("center_id",$center_id)->whereNull("group_id");
            })->orWhere(function($query) use ($group_id){
                $query->where("group_id",$group_id);
            })->where("coupon_id",$coupon->id)->first();

            if($mapped_coupons){
                $success = true;
            }
        }
        
        $data["success"] = $success;
        $data["message"] = $message;

        return Response::json($data, 200, []);
    }

    public function createOrder(Request $request){

        $client_code = $request->header("clientId");
        $client = Client::AuthenticateClient($client_code);
        $client_id = $client->id;

        $student_id = $request->student_id ? $request->student_id : 0;
        $registration_id = $request->registration_id ? $request->registration_id : 0;
        $total_amount = $request->total_amount;
        $type = $request->type;
        $payment_gateway = $request->payment_gateway;
        $payment_items = $request->payment_items;

        if($payment_gateway == "razorpay"){

            $url = 'https://api.razorpay.com/v1/orders';
            $payload = [
                "amount" => $total_amount."00",
                "currency" => "INR",
                "payment_capture" => 1
            ];

            $payload = json_encode($payload);

            $result = $this->curlRequest($url, "POST", $payload);

            $order_id = $result->id;

            $table_order_id = DB::table("orders")->insertGetId(array(
                "order_id" => $order_id,
                "student_id" => $student_id,
                "registration_id" => $registration_id,
                "total_amount" => $total_amount,
                "client_id" => $client_id
            ));

            foreach($request->payment_items as $payment_item){
                DB::table("order_items")->insert(array(
                    "order_id" => $table_order_id,
                    "type_id" => $payment_item["type_id"],
                    "amount" => $payment_item["amount"],
                    "discount" => $payment_item["discount"],
                    "discount_code_id" => isset($payment_item["discount_code_id"])?$payment_item["discount_code_id"] : null,
                    "taxable_amount" => $payment_item["taxable_amount"],
                    "tax" => $payment_item["tax"],
                    "tax_perc" => $payment_item["tax_perc"],
                    "total_amount" => $payment_item["total_amount"]
                ));
            }

            $data['success'] = true;
            $data['order_id'] = $order_id;
            $data['key'] = $this->get_key();
        }

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function processWebOrder(Request $request){

        $order_id = $request->order_id;
        $transaction_id = $request->transaction_id;

        $order = DB::table("orders")->where("order_id",$order_id)->where("status",0)->first();

        if($order){

            DB::table("orders")->where("id",$order->id)->update(array(
                "transaction_id" => $transaction_id,
                "status" => 1
            ));

            if($order->registration_id){
                $student = Student::createFromRegistration($order->registration_id);
            } else {
                $student = Student::find($order->student_id);
            }

            $payment = PaymentHistory::createPaymentFromOrder($order, $student, $transaction_id);

            Student::reCalculateDates($student->id);
            $student->inactive = 0;
            $student->save();

            $student_emails = Student::getContactDetails("email",$student->id);
            if(sizeof($student_emails) > 0){
                PaymentController::createPaymentEmail($payment, $student_emails, $user = null);
            }

            $data['success'] = true;
            $data["datetime"] = date("d-m-Y H:i:s");
            $data['message'] = "Your order is successfully processed";
        } else {
            $data['success'] = false;
            $data['message'] = "Your order could not be processed at this time. Kindly contact us in case of any issues";
        }

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    private function curlRequest($url, $method, $payload = null){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, $this->get_key() . ":" . $this->get_secret());
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if($method == 'POST'){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        }
        
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);
        return $result;

    }


}
