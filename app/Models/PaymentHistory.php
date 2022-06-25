<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB, App\Models\Student, App\Models\Utilities, App\Models\PaymentItem;

class PaymentHistory extends Model
{

    protected $table = 'payment_history';

    public static function listing(){
        return PaymentHistory::select("payment_history.*","payment_modes.mode")->join("payment_modes","payment_modes.id","=","payment_history.p_mode");
    }

    public static function getAmount($group_id, $type_id){
        
        $group = DB::table("groups")->select("groups.id as group_id","groups.center_id","center.city_id")->join("center","center.id","=","groups.center_id")->where("groups.id",$group_id)->first();

        $group_price = null;
        $center_price = null;
        $city_price = null;
        $default_price = null;

        if(!$group){
            return $default_price;
        }

        $prices = DB::table("payment_type_prices")->select("id","price","tax as tax_perc","group_id","center_id","city_id","total")->where("pay_type_id",$type_id)->where(function($query) use ($group){
            $query->where("city_id",-1)->orWhere("city_id",$group->city_id)
                ->orWhere(function($query) use ($group){
                    $query->whereNull("center_id")->orWhere("center_id",$group->center_id);
                })->orWhere(function($query) use ($group){
                    $query->whereNull("group_id")->orWhere("group_id",$group->group_id);
                });

        })->get();

        foreach($prices as $price){
            if($price->group_id == $group->group_id){
                $group_price = $price;
            }

            if($price->center_id == $group->center_id && !$price->group_id){
                $center_price = $price;
            }

            if($price->city_id == $group->city_id && !$price->center_id && !$price->group_id){
                $city_price = $price;
            }

            if($price->city_id == -1){
                $default_price = $price;
            }
        }

        if($group_price){
            return $group_price;
        } else if($center_price){
            return $center_price;
        } else if($city_price){
            return $city_price;
        } else {
            if($default_price){
                return $default_price;
            } else {
                $price = new \stdClass;
                $price->price = 0;
                $price->tax_perc = 18;
                $price->total = 0;
                return $price;
            }
        }

    }

    public static function sendReceipt($payment_id, $user = null, $student_emails){

        $payment = DB::table("payment_history")->find($payment_id);

        $student = Student::listing()->where("students.id",$payment->student_id)->first();
        $student = Student::mapDates($student);

        $params = Utilities::getSettingParams([16,4], $student->client_id);

        if(sizeof($student_emails) > 0){
            $mail = new MailQueue;
            $mail->mailto = implode(', ', $student_emails);
            $mail->subject = Utilities::replaceText($params->param_16, $student);
            $mail->content = Utilities::replaceText($params->param_4, $student);
            // $mail->file
            $mail->tb_name = "payment_history";
            $mail->tb_id = $payment_id;
            $mail->student_id = $payment->student_id;
            if($user){
                $mail->user_id = $user->id;
            }
            $mail->client_id = $student->client_id;
            $mail->save();
        }

    }

    public static function createPaymentFromOrder($order, $student, $transaction_id){

        $order_items = DB::table("order_items")->where("order_id",$order->id)->get();

        $amount = 0;
        $tax = 0;
        $total_amount = 0;
        $payment_items = [];
        foreach($order_items as $order_item){
            $payment_type = DB::table("payments_type")->select("payments_type.category_id","payments_type.months","payments_type_categories.is_sub_type")->join("payments_type_categories","payments_type_categories.id","=","payments_type.category_id")->where("payments_type.id",$order_item->type_id)->first();

            $order_item->category_id = $payment_type->category_id;
            $order_item->client_id = $order->client_id;
            $order_item->months = $payment_type->months;

            if($payment_type->is_sub_type == 1){
                if($student->doe){
                    $order_item->start_date = date("Y-m-d",strtotime($student->doe) + 86400);
                } else {
                    $order_item->start_date = date("Y-m-d");
                }
            }
            $amount += $order_item->amount;
            $tax += $order_item->tax;
            $total_amount += $order_item->total_amount;
            $payment_items[] = $order_item;
        }

        $payment = new PaymentHistory;
        $uniqid = Utilities::getUniqueInTable("payment_history", "unique_id");
        $payment->unique_id = $uniqid;
        $payment->student_id = $student->id;
        $payment->group_id = $student->group_id;
        $payment->invoice_date = date("Y-m-d");
        $payment->payment_date = date("Y-m-d");
        $payment->amount = $amount;
        $payment->tax = $tax;
        $payment->total_amount = $total_amount;
        $payment->p_mode = 5;
        $payment->reference_no = $transaction_id;
        $payment->p_remark = "Razorpay payment - ".$transaction_id;
        $payment->client_id = $order->client_id;
        $payment->order_id = $order->id;
        $payment->save();

        foreach ($payment_items as $payment_item) {
                
            $item = new PaymentItem;
            $item->payment_history_id = $payment->id;
            $item->category_id = $payment_item->category_id;
            $item->type_id = $payment_item->type_id;
            $item->client_id = $payment_item->client_id;
            $item->adjustment = 0;

            $item->months = $payment_item->months;
            $item->amount = $payment_item->amount;
            $item->discount = $payment_item->discount;
            $item->discount_code_id = $payment_item->discount_code_id;
            $item->taxable_amount = $payment_item->taxable_amount;
            $item->tax_perc = $payment_item->tax_perc;
            $item->tax = $payment_item->tax;
            $item->total_amount = $payment_item->total_amount;

            if(isset($payment_item->start_date)){
                if($payment_item->start_date){
                    $item->start_date = date('Y-m-d',strtotime($payment_item->start_date));
                    $item->end_date = Utilities::calculateEndDate($item->start_date, $item->months, $item->adjustment);
                }
            }

            $item->save();
        }

        return $payment;

    }
    
}

