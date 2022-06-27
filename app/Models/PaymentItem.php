<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Utilities, DB;

class PaymentItem extends Model
{

    protected $table = 'payment_items';
    public $timestamps = false;


    public static function getPaymentItems($payment_id){
        $items = PaymentItem::select("payment_items.id","payment_items.category_id","payment_items.type_id","payment_items.amount","payment_items.tax","payment_items.tax_perc","payment_items.total_amount","payment_items.start_date","payment_items.end_date","payments_type_categories.category_name as category","payments_type.name as type","payment_items.discount","payment_items.discount_code_id","coupons.code as discount_code","payment_items.taxable_amount","payment_items.months")->leftJoin("payments_type_categories","payments_type_categories.id","=","payment_items.category_id")->leftJoin("payments_type","payments_type.id","=","payment_items.type_id")->leftJoin("coupons","coupons.id","=","payment_items.discount_code_id")->where('payment_items.payment_history_id',$payment_id)->get();

        foreach ($items as $value) {
            if ($value->start_date) {
                $value->is_sub_type = true;
                $value->start_date = Utilities::convertDate($value->start_date);
            }
            $value->end_date = Utilities::convertDate($value->end_date);
        }

        return $items;
    }

    public static function getPauses($subscription_id, $student_id = null, $status = -1){
        
        $items = DB::table('subscription_pauses')->select('subscription_pauses.*','u1.name as added_by_name','u2.name as approved_by_name')->leftJoin("users as u1","u1.id","=","subscription_pauses.added_by")->leftJoin("users as u2","u2.id","=","subscription_pauses.approved_by");
        
        if($student_id){
            $items = $items->where('subscription_pauses.student_id',$student_id);    
        }

        if($subscription_id){
            $items = $items->where("subscription_id",$subscription_id);
        }

        if($status != -1){
            $items = $items->where("status",0);
        }

        $items = $items->get();

        foreach ($items as $value) {

            switch($value->status){
                case 0:
                    $value->status_name = "Pending";
                    break;
                case 1:
                    $value->status_name = "Approved";
                    break;
                case 2:
                    $value->status_name = "Rejected";
                    break;
                default:
                    $value->status_name = "Pending";
                    break;
            }

            $value->requestor = ($value->requested_by == 1) ? 'Academy' : 'Parent';
            $value->start_date = Utilities::convertDate($value->start_date);
            $value->end_date = Utilities::convertDate($value->end_date);
            $value->created_at = Utilities::convertDate($value->created_at);
        }

        return $items;
    }
    
}

