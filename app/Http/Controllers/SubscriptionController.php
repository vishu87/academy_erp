<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB;
use Illuminate\Http\Request;

use App\Models\PaymentHistory;

class SubscriptionController extends Controller
{	

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

        $payment_items = [];
        foreach($fixed as $fix_category_id => $fix_type_id){
            
            $category = DB::table("payments_type_categories")->select("id","category_name")->where("id",$fix_category_id)->first();
            
            $price = PaymentHistory::getAmount($group_id,$fix_type_id);

            $payment_items[] = [
                "category" => $category->category_name,
                "amount" => $price->price,
                "tax_perc" => $price->tax_perc,
                "total_amount" => $price->total
            ];
            
        }

        foreach($categories as $category){
            if($category["type_id"]){
                $price = PaymentHistory::getAmount($group_id,$category["type_id"]);
                $payment_items[] = [
                    "category" => $category["category_name"],
                    "amount" => $price->price,
                    "tax_perc" => $price->tax_perc,
                    "total_amount" => $price->total,
                ];
            }
        }

        // foreach($)
        
        $data["success"] = true;
        $data["payment_items"] = $payment_items;

        return Response::json($data, 200, []);
    }



}
