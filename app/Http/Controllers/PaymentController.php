<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\PaymentHistory, App\Models\PaymentItem, App\Models\User, App\Models\Student, App\Models\Coupon, App\Models\Utilities, App\Models\MailQueue;

class PaymentController extends Controller{ 

    public function payment_list(){
        return view('payments.payments_list',["sidebar" => "payments","menu" => "accounts"]);
    }

    public function coupons(){
        return view('payments.coupons.index',["sidebar" => "p_coupons","menu" => "accounts"]);
    }

    public function paymentList(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $page_no = $request->page_no;
        $max_per_page = $request->max_per_page;

        $payments =  DB::table('payment_history')->select('payment_history.*','students.name')->Join('students', 'students.id', '=', 'payment_history.student_id');

        if($request->student_name) {
            $payments = $payments->where("students.name","LIKE","%".$request->student_name."%");
        }

        if($request->start_date) {
            $start_date = Utilities::convertDateToDB($request->start_date);
            if($start_date){
                $payments = $payments->where("payment_history.invoice_date",">=",$start_date);
            }
        }

        if($request->end_date) {
            $end_date = Utilities::convertDateToDB($request->end_date);
            if($end_date){
                $payments = $payments->where("payment_history.invoice_date","<",$end_date);
            }
        }

        $payments = $payments->where("payment_history.client_id",$user->client_id);

        $total = $payments->count();

        $payments = $payments->skip(($page_no-1)*$max_per_page)->take($max_per_page)->get();

        foreach ($payments as $pay) {
            $pay->code = "PAY".str_pad($pay->id,6,"0",STR_PAD_LEFT);
            $pay->invoice_date = Utilities::convertDate($pay->invoice_date);
            $pay->invoice_date = Utilities::convertDate($pay->invoice_date);
        }

        $data["success"] = true;
        $data["payments"] = $payments;
        
        return Response::json($data, 200, []);
    }

    public function paymentInit(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $student_id = $request->student_id;
        $payment_id = $request->payment_id;
        $type = $request->type;

        $student = DB::table("students")->find($student_id);

        $check_access = User::getAccess("pt-edit", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        if($payment_id != 0){

        } else {
            $payment = new \stdClass;
            $payment->invoice_date = "";
            $payment->payment_date = "";
            $payment->student_id = $student_id;
            $payment->group_id = $student->group_id;
            $items = [];
            $payment->items = $items;
        }

        $data["success"] = true;
        $data["payment"] = $payment;

        return Response::json($data, 200, []);
    }

    public function getPayType(Request $request, $sport_id){

        $client_id = $request->header("clientId");
        
        $payTypeCat = DB::table("payments_type_categories")->select("id","category_name","is_sub_type")->where("inactive",0)->whereIn("client_id",[$client_id,0])->where("sport_id",$sport_id)->get();
        $pay_type_cat_ids = [];
        foreach($payTypeCat as $cat){
            $pay_type_cat_ids[] = $cat->id;
        }

        $payType = DB::table("payments_type")->select("id","name","category_id")->where("inactive",0)->where("client_id",$client_id)->whereIn("payments_type.category_id",$pay_type_cat_ids)->get();
        
        $payModes = DB::table("payment_modes")->get();

        $data["success"] = true;
        $data["payTypeCat"] = $payTypeCat;
        $data["payType"] = $payType;
        $data["payModes"] = $payModes;

        return Response::json($data, 200, []);
    }

    public function getAmount(Request $request){

        $client_id = $request->header("clientId");
        $category_id = $request->category_id;
        $type_id = $request->type_id;
        $group_id = $request->group_id;

        $category = DB::table("payments_type_categories")->whereIn("client_id",[$client_id,0])->find($category_id);
        $type = DB::table("payments_type")->where("client_id",$client_id)->find($type_id);

        $price = PaymentHistory::getAmount($group_id,$type_id);

        $data["price"] = $price;
        $data["category_name"] = $category->category_name;
        $data["type_name"] = $type->name;
        $data["months"] = $type->months;
        $data["success"] = true;

        return Response::json($data, 200, []);
    }

    public function savePayment(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        $student = Student::find($request->student_id);

        $check_access = User::getAccess("pt-edit", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $cre = [
            "invoice_date" => $request->invoice_date,
            "mode" => $request->p_mode,
            "payment_date" => $request->payment_date
        ];
        $validator = Validator::make($cre, [
            "invoice_date" => "required",
            "mode" => "required",
            "payment_date" => "required",
        ]);

        if ($validator->passes()) {

            $new_payment = false;
            if($request->id) {
                $data["message"] = "Payment is successfully updated";
                $pay_history = PaymentHistory::find($request->id);
            } else {
                
                $uniqid = Utilities::getUniqueInTable("payment_history", "unique_id");

                $new_payment = true;
                $pay_history  = new PaymentHistory;
                $pay_history->unique_id = $uniqid;
                $pay_history->student_id = $student->id;
                $pay_history->group_id = $student->group_id;
                $pay_history->client_id = $user->client_id;
                $pay_history->added_by = $user->id;
                $data["message"] = "New payment record is successfully created";
            }

            $pay_history->invoice_date = Utilities::convertDateToDB($request->invoice_date);
            $pay_history->payment_date = Utilities::convertDateToDB($request->payment_date);

            $pay_history->amount = $request->amount;
            $pay_history->tax = $request->tax;
            $pay_history->total_amount = $request->total_amount;
            $pay_history->p_mode = $request->p_mode;
            $pay_history->reference_no = $request->reference_no;
            $pay_history->p_remark = $request->p_remark;
            $pay_history->save();

            $init_items = DB::table("payment_items")->where("payment_history_id",$pay_history->id)->pluck("id")->toArray();
            $final_items = [];

            foreach ($request->items as $value) {
                
                if (isset($value["id"])) {
                    $item = PaymentItem::find($value["id"]);
                } else {
                    $item = new PaymentItem;
                    $item->payment_history_id = $pay_history->id;
                    $item->category_id = $value["category_id"];
                    $item->type_id = $value["type_id"];
                    $item->client_id = $user->client_id;
                    $item->adjustment = 0;
                }

                $item->months = isset($value["months"]) ? $value["months"] : 0;
                $item->amount = $value["amount"];
                $item->discount = isset($value["discount"]) ? $value["discount"] : 0;
                $item->discount_code_id = isset($value["discount_code_id"]) ? $value["discount_code_id"] : null;
                $item->taxable_amount = $value["taxable_amount"];
                $item->tax = $value["tax"];
                $item->tax_perc = $value["tax_perc"];
                $item->total_amount = $value["total_amount"];
                
                if(isset($value["start_date"])){
                    if($value["start_date"]){
                        $item->start_date = date('Y-m-d',strtotime($value["start_date"]));
                        $item->end_date = Utilities::calculateEndDate($item->start_date, $item->months, $item->adjustment);
                    }
                }

                $item->save();
                $final_items[] = $item->id;
            }

            foreach($init_items as $item_id){
                if(!in_array($item_id, $final_items)){
                    DB::table("payment_items")->where("id",$item_id)->delete();
                }
            }

            Student::reCalculateDates($student->id);

            $data["success"] = true;

        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return Response::json($data, 200 ,[]);
    }

    public function editPayment(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $payment_id =  $request->payment_id;
        $payment = PaymentHistory::where("id",$payment_id)->where("client_id",$user->client_id)->first();
        
        if ($payment) {
            
            $student = Student::find($payment->student_id);
            $check_access = User::getAccess("pt-edit", $user->id, $student->group_id);
            if(!$check_access) {
                $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
            }

            $payment->invoice_date = Utilities::convertDate($payment->invoice_date);
            $payment->payment_date = Utilities::convertDate($payment->payment_date);

            $payment->items = PaymentItem::getPaymentItems($payment_id);

            $data["success"] = true;
            $data["payment"] = $payment;
        } else {
            $data["success"] = false;
            $data["message"] = "Payment not found";
        }

        return Response::json($data, 200, []);
    }

    public function viewPayment(Request $request, $payment_id){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $payment = PaymentHistory::listing()->where("payment_history.client_id",$user->client_id)->where("payment_history.id",$payment_id)->first();

        $check_access = User::getAccess("pt-view", $user->id, $payment->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        if ($payment) {
            
            $payment->invoice_date = Utilities::convertDateShow($payment->invoice_date);
            $payment->payment_date = Utilities::convertDateShow($payment->payment_date);

            $payment->items = PaymentItem::getPaymentItems($payment_id);

            $data["success"] = true;
            $data["payment"] = $payment;
        } else {
            $data["success"] = false;
            $data["message"] = "payment does not exist";
        }

        return Response::json($data, 200, []);
    }

    public function getCoupons(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $student_id = $request->student_id;
        $student = Student::listing()->where("students.id",$student_id)->first();

        $group_id = $student->group_id;
        $center_id = $student->center_id;
        $city_id = $student->city_id;

        $mapped_coupons = DB::table("coupon_mapping")->where("city_id",-1)->orWhere(function($query) use ($city_id){
            $query->where("city_id",$city_id)->whereNull("center_id");
        })->orWhere(function($query) use ($city_id, $center_id){
            $query->where("city_id",$city_id)->where("center_id",$center_id)->whereNull("group_id");
        })->orWhere(function($query) use ($group_id){
            $query->where("group_id",$group_id);
        })->pluck("coupon_id")->toArray();

        if(sizeof($mapped_coupons) > 0){
            $coupons = DB::table('coupons')->select('coupons.id','coupons.code')->whereIn("coupons.id",$mapped_coupons)->where("coupons.status",1)->where("coupons.client_id",$user->client_id)->get();
        } else {
            $coupons = [];
        }

        $data['success'] = true;
        $data['coupons'] = $coupons;

        return Response::json($data, 200, []);
    }

    public function applyCoupon(Request $request){

        // $user = User::AuthenticateUser($request->header("apiToken"));
        // no validation as may be required in front end

        $coupon_id = $request->coupon_id;
        $items = $request->items;

        $coupon = DB::table("coupons")->find($coupon_id);

        $final_items = [];
        foreach($items as $item){
            if($coupon){
                if($coupon->pay_type_id == $item["type_id"]){
                    if($coupon->discount_type == 1){
                        $item["discount"] = round($item["amount"]*$coupon->discount/100);
                    } else {
                        $item["discount"] = $coupon->discount;
                    }
                    $item["discount_code_id"] = $coupon->id;
                    $item["discount_code"] = $coupon->code;
                } else {
                    $item["discount"] = 0;
                    $item["discount_code_id"] = null;
                    $item["discount_code"] = "";
                }
            } else {
                $item["discount"] = 0;
                $item["discount_code_id"] = null;
                $item["discount_code"] = "";
            }

            $item["taxable_amount"] = $item["amount"] - $item["discount"];
            $item["tax"] = round($item["taxable_amount"]*$item["tax_perc"]/100);
            $item["total_amount"] = $item["taxable_amount"] + $item["tax"];

            $final_items[] = $item;
        }

        $data['success'] = true;
        $data['items'] = $final_items;

        return Response::json($data, 200, []);
    }

    public function subscriptionDetails(Request $request, $id){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $item = DB::table('payment_items')->select('payment_items.*','payment_history.student_id')->join('payment_history', 'payment_items.payment_history_id', '=', 'payment_history.id')->where('payment_items.id',$id)->where("payment_items.client_id",$user->client_id)->first();

        if($item){
            $item->start_date = date('d-m-Y' ,strtotime($item->start_date));
            $item->end_date = date('d-m-Y' ,strtotime($item->end_date));

            $item->pauses = PaymentItem::getPauses($id);

            $data["success"] = true;
            $data["subscription"] = $item;
        } else {
            $data["success"] = false;
            $data["message"] = "Subscription not found";
        }

        return Response::json($data, 200, []);
    }

    public function subscriptionAdd(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $student = Student::find($request->student_id);

        $check_access = User::getAccess("add-pause", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $cre = [
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "remarks" => $request->remarks,
            "requested_by" => $request->requested_by,
        ];
        $validator = Validator::make($cre, [
            "start_date" => "required|date",
            "end_date" => "required|date",
            "remarks" => "required",
            "requested_by" => "required",
        ]);

        if ($validator->passes()) {
            
            DB::table("subscription_pauses")->insert(array(
                "subscription_id" => $request->subscription_id,
                "student_id" => $request->student_id,
                "requested_by" => $request->requested_by,
                "start_date" => Utilities::convertDateToDB($request->start_date),
                "end_date" => Utilities::convertDateToDB($request->end_date),
                "days" => Utilities::daysDiff($request->start_date, $request->end_date),
                "remarks" => $request->remarks,
                "added_by" => $user->id,
                "created_at" => date("Y-m-d H:i:s")
            ));

            $data["success"] = true;
            $data["message"] = "Request successfully added and waiting for approval";

        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return Response::json($data, 200 ,[]);
    }

    public function approvePauseRequest(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $student = Student::find($request->student_id);

        $check_access = User::getAccess("approve-pause", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $cre = [
            "approval_remarks" => $request->approval_remarks,
            "status" => $request->status,
        ];
        $validator = Validator::make($cre, [
            "approval_remarks" => "required",
            "status" => "required",
        ]);

        if ($validator->passes()) {

            $pause = DB::table("subscription_pauses")->select("subscription_pauses.id","subscription_pauses.subscription_id")->join("payment_items","payment_items.id","=","subscription_pauses.subscription_id")->where("subscription_pauses.id",$request->id)->where("payment_items.client_id",$user->client_id)->first();
            
            if($pause){
                DB::table("subscription_pauses")->where("id",$request->id)->update(array(
                    "status" => $request->status,
                    "approval_remarks" => $request->approval_remarks,
                    "approved_by" => $user->id,
                    "approved_at" => date("Y-m-d H:i:s")
                ));

                Student::reCalculateSubscription($pause->subscription_id);
                Student::reCalculateDates($student->id);

                $data["success"] = true;
                $data["message"] = "Request successfully added and waiting for approval";
            } else {
                $data["success"] = false;
                $data["message"] = "Request not found";
            }

        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return Response::json($data, 200 ,[]);
    }
    
    public function deletePauseRequest(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $pause = DB::table("subscription_pauses")->select("subscription_pauses.id","subscription_pauses.subscription_id","payment_history.student_id","students.group_id")->join("payment_items","payment_items.id","=","subscription_pauses.subscription_id")->join("payment_history","payment_history.id","=","payment_items.payment_history_id")->join("students","students.id","=","payment_history.student_id")->where("subscription_pauses.id",$request->pause_id)->where("payment_items.client_id",$user->client_id)->first();

        if ($pause) {

            $check_access = User::getAccess("add-pause", $user->id, $pause->group_id);
            if(!$check_access) {
                $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
            }
            
            DB::table("subscription_pauses")->where("id",$request->pause_id)->delete();

            $data["success"] = true;
            $data["message"] = "Request is successfully deleted";

        } else {
            $data["success"] = false;
            $data["message"] = "Request not found";
        }

        return Response::json($data, 200 ,[]);
    }


    public function sendEmail(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $payment_id = $request->payment_id;

        $payment = PaymentHistory::where("id",$payment_id)->first();
        $student = Student::listing()->where("students.id",$payment->student_id)->first();

        $student_emails = Student::getContactDetails("email",$payment->student_id);
        if(sizeof($student_emails) > 0){
            PaymentController::createPaymentEmail($payment, $student_emails, $user);
            $data["success"] = true;
            $data["message"] = "Email is successfully sent to ".implode(', ',$student_emails);
        } else {
            $data["success"] = false;
            $data["message"] = "No email id is found for the student";
        }

        return Response::json($data, 200, []);
    }

    public static function createPaymentEmail($payment, $student_emails, $user = null){

        $response = PaymentController::PDFCreate($payment);

        if(sizeof($student_emails) > 0 && $response["success"]){

            $params = Utilities::getSettingParams([16,4], $payment->client_id);

            $student = Student::listing()->where("students.id",$payment->student_id)->first();
            $student = Student::mapDates($student);

            $destination = "uploads/";
            $filename = $destination.Utilities::cleanName($student->name."_Receipt_".date("YmdHis")).".pdf";
            $pdf = $response["pdf"];
            $pdf->save($filename);

            $mail = new MailQueue;
            $mail->mailto = implode(', ', $student_emails);
            $mail->subject = Utilities::replaceText($params->param_16, $student);
            $mail->content = Utilities::replaceText($params->param_4, $student);
            $mail->at_file = $filename; 
            $mail->tb_name = "payment_history";
            $mail->tb_id = $payment->id;
            $mail->student_id = $payment->student_id;
            if($user){
                $mail->user_id = $user->id;
            }
            $mail->client_id = $payment->client_id;
            $mail->save();
        }
    }

    public function paymentReceipt($payment_code){

        $payment = PaymentHistory::where("unique_id",$payment_code)->first();
        if(!$payment){
            return "No payment found";
        }

        $response = PaymentController::PDFCreate($payment);

        if(!$response["success"]){
            return $response["message"];
        }

        return $response["pdf"]->stream();
        die();

    }

    public static function PDFCreate($payment){

        $student = Student::listing()->where("students.id",$payment->student_id)->first();
        
        $payment->payment_date = date('d-m-Y',strtotime($payment->payment_date));

        $items = PaymentItem::select("payment_items.id","payment_items.category_id","payment_items.type_id","payment_items.amount","payment_items.tax_perc","payment_items.tax","payment_items.total_amount","payment_items.start_date","payment_items.end_date","payments_type_categories.category_name as category","payments_type.name as type","payment_items.discount","payment_items.discount_code_id","coupons.code as discount_code")->leftJoin("payments_type_categories","payments_type_categories.id","=","payment_items.category_id")->leftJoin("payments_type","payments_type.id","=","payment_items.type_id")->leftJoin("coupons","coupons.id","=","payment_items.discount_code_id")->where('payment_items.payment_history_id',$payment->id)->get();

        foreach ($items as $value) {
            if ($value->start_date) {
                $value->is_sub_type = true;
                $value->start_date = date('d-m-Y',strtotime($value->start_date));
            }
            if ($value->end_date) {
                $value->end_date = date('d-m-Y',strtotime($value->end_date));
            }
        }

        $gst = DB::table('gst')->where('state_id',$student->student_state_id)->where("client_id",$payment->client_id)->first();

        if(!$gst){
            $gst = DB::table('gst')->where('defaults',1)->where("client_id",$payment->client_id)->first();
        }

        if(!$gst){
            return [
                "success" => false,
                "message" => "GST information is not found"
            ];
        }

        if($student->student_state_id != $gst->state_id){
            $igst = false;
        } else {
            $igst = true;
        }

        foreach($items as $item){
            $item->igst_perc = "-";
            $item->igst = "-";
            $item->cgst_perc = "-";
            $item->cgst = "-";
            $item->sgst_perc = "-";
            $item->sgst = "-";

            if($igst){
                $item->igst_perc = $item->tax_perc;
                $item->igst = $item->tax;
            } else {
                $item->cgst_perc = $item->tax_perc/2;
                $item->cgst = $item->tax/2;
                $item->sgst_perc = $item->tax_perc/2;
                $item->sgst = $item->tax/2;
            }
        }

        $payment->items = $items;

        $pdf = PDF::loadView('students.payment_receipt',['student' => $student, 'payment' => $payment, 'gst' => $gst, "igst" => $igst]);
        return [
            "success" => true,
            "pdf" => $pdf
        ];

    }
}