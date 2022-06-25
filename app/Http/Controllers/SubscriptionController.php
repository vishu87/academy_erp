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

            if($price){
                $payment_items[] = [
                    "category" => $category->category_name,
                    "type_id" => $fix_type_id,
                    "amount" => $price->price,
                    "discount" => 0,
                    "taxable_amount" => $price->price,
                    "tax_perc" => $price->tax_perc,
                    "tax" => round($price->price*$price->tax_perc/100),
                    "total_amount" => round($price->total)
                ];
                $total_amount += round($price->total);
            } else {
                $payment_items[] = [
                    "category" => $category->category_name,
                    "type_id" => $fix_type_id,
                    "amount" => 0,
                    "discount" => 0,
                    "taxable_amount" => 0,
                    "tax_perc" => 0,
                    "tax" => 0,
                    "total_amount" => 0
                ];
            }
            
            
        }

        foreach($categories as $category){
            if($category["type_id"]){
                $price = PaymentHistory::getAmount($group_id,$category["type_id"]);
                if($price){
                    $payment_items[] = [
                        "category" => $category["category_name"],
                        "type_id" => $category["type_id"],
                        "amount" => $price->price,
                        "taxable_amount" => $price->price,
                        "discount" => 0,
                        "tax_perc" => $price->tax_perc,
                        "tax" => round($price->price*$price->tax_perc/100),
                        "total_amount" => round($price->total),
                    ];
                    $total_amount += round($price->total);
                } else {
                    $payment_items[] = [
                        "category" => $category["category_name"],
                        "type_id" => $category["type_id"],
                        "amount" => 0,
                        "taxable_amount" => 0,
                        "discount" => 0,
                        "tax_perc" => 0,
                        "tax" => 0,
                        "total_amount" => 0,
                    ];
                }
                
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
