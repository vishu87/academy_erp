<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB;
use Illuminate\Http\Request;

use App\Models\PaymentHistory, App\Models\Client;

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

        $category_ids = [ 2, 3];
        $mandatory = [ 2 ];

        $payment_categories = [];

        foreach($category_ids as $payment_category_id){
            $category = DB::table("payments_type_categories")->select("id","category_name")->where("id",$payment_category_id)->first();
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
        
        $data["success"] = true;
        $data["payment_options"] = $payment_categories;

        return Response::json($data, 200, []);
    }

    public function paymentItems(Request $request){

        $group_id = $request->group_id;
        $categories = $request->categories;

        $mandatory = [ 1, 2];
        $optional = [ 3 ];
        $fixed = [ 1 => 21 ];
        
        $total_amount = 0;

        $payment_items = [];
        foreach($fixed as $fix_category_id => $fix_type_id){
            
            $category = DB::table("payments_type_categories")->select("id","category_name")->where("id",$fix_category_id)->first();
            
            $price = PaymentHistory::getAmount($group_id,$fix_type_id);

            $payment_items[] = [
                "category" => $category->category_name,
                "amount" => $price->price,
                "tax_perc" => $price->tax_perc,
                "total_amount" => round($price->total)
            ];
            $total_amount += round($price->total);
            
        }

        foreach($categories as $category){
            if($category["type_id"]){
                $price = PaymentHistory::getAmount($group_id,$category["type_id"]);
                $payment_items[] = [
                    "category" => $category["category_name"],
                    "amount" => $price->price,
                    "tax_perc" => $price->tax_perc,
                    "total_amount" => round($price->total),
                ];
                $total_amount += round($price->total);
            }
        }

        // foreach($)
        
        $data["success"] = true;
        $data["payment_items"] = $payment_items;
        $data["total_amount"] = $total_amount;

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

            // foreach($payment_items as $payment_item){
            //     DB::table("order_items")->insert(array(
            //         "order_id" => $table_order_id,
            //         "type_id" => $payment_item["type_id"],
            //         "amount" => $payment_item["amount"],
            //         "tax_perc" => $payment_item["tax_perc"],
            //         "tax" => $payment_item["tax"],
            //         "total_amount" => $payment_item["total_amount"],

            //     ));
            // }

            $data['success'] = true;
            $data['order_id'] = $order_id;
            $data['key'] = $this->get_key();
        }

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function processWebOrder(){

        $order_id = Input::get("order_id");
        $transaction_id = Input::get("transaction_id");

        $order = DB::table("orders")->where("order_id",$order_id)->where("status",0)->first();

        if($order){

            DB::table("orders")->where("id",$order->id)->update(array(
                "transaction_id" => $transaction_id,
                "status" => 1
            ));

            $student = Student::find($order->student_id);

            $kit_fee = $order->kit_fee;
            $sub_fee = $order->amount - $kit_fee;

            $sub_fee_tax = round($sub_fee*0.18);
            $kit_fee_tax = $order->amount_tax - $sub_fee_tax;

            $payment = new PaymentHistory;
            $payment->student_id = $order->student_id;
            $payment->group_id = $student->first_group;
            $payment->payment_category_id = 0;
            $payment->product_type = 1;
            $payment->package_type = 3;
            $payment->dor = strtotime("today");
            $payment->reg_fee = 0;
            $payment->sub_fee = $sub_fee;
            $payment->kit_fee = $kit_fee;
            $payment->reg_fee_tax = 0;
            $payment->sub_fee_tax = $sub_fee_tax;
            $payment->kit_fee_tax = $kit_fee_tax;
            $payment->payment_mode = 8;
            $payment->in_favor_of = 'TISMPL';
            $payment->amount = $order->amount;
            $payment->total_amount = $order->amount + $order->amount_tax;
            $payment->months = $order->month_plan;
            $payment->p_remark = "Razorpay payment - ".$transaction_id;
            $payment->razorpay_id = $transaction_id;
            $payment->user_subscription_id = 0;
            $payment->save();

            if($student->doe){
                $payment->dos = $student->doe + 86400;
                $student->doe = $payment->doe = $payment->dos + $order->month_plan*30*86400;
            } else {
                $payment->dos = $payment->dor;
                $payment->doe = $payment->dos + $order->month_plan*30*86400;
            }

            $student->active = 0;

            $student->save();
            $payment->save();

            PaymentHistory::sendReceipt($payment->id, true);

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
