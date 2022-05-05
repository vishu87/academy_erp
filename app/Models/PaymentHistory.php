<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

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
    
}

