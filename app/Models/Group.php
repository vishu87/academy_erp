<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB, Cache;

class Group extends Model {

    protected $table = 'groups';
    public $timestamps = false;

    public static function getCenters($groups){
    	$center_ids = [];
    	foreach ($groups as $group) {
    		$center_ids[] = $group->center_id;
    	}

        $all_centers = Cache::remember('centers', 15, function(){
            return DB::table("center")->select("id as value","center_name as label","city_id","cordinator_id")->where("center.center_status",0)->orderBy("center_name")->get();
        });

        $centers = [];
        foreach ($all_centers as $center) {
            if(in_array($center->value, $center_ids)) $centers[] = $center;
        }

    	return $centers;

    }

    public static function getCities($centers){
        $city_ids = [];
        foreach ($centers as $center) {
            $city_ids[] = $center->city_id;
        }

        $all_cities = Cache::remember('cities', 15, function(){
            return DB::table("city")->select("id as value","city_name as label")->where("city.city_status",0)->orderBy("city_name")->get();
        });

        $cities = [];
        foreach ($all_cities as $city) {
            if(in_array($city->value, $city_ids)) $cities[] = $city;
        }

        return $cities;

    }

    public static function checkGroupName($name,$center_id,$group_id){
        $check = Group::where('group_name',$name)->where('center_id',$center_id)->where('id','!=',$group_id)->first();
        if($check){
            return true;
        }else{
            return false;
        }
    }

    public static function getPlans($group_id, $center_id){
        $plans = [];
        $category = DB::table("student_categories")->where("category","Regular")->where("center_id",$center_id)->first();

        if($category){
            $plans = DB::table("payment_table")->select("reg_fee","kit_fee","sub_fee","months")->where("group_id",$group_id)->where("category_id",$category->id)->where("online_payment",0)->orderBy("months")->get();
            foreach ($plans as $plan) {
                $plan->reg_fee_tax = round($plan->reg_fee*18/100);
                $plan->sub_fee_tax = round($plan->sub_fee*18/100);
                $plan->kit_fee_tax = round($plan->kit_fee*12/100);
                $plan->total_tax_amount = $plan->reg_fee_tax + $plan->sub_fee_tax + $plan->kit_fee_tax;
                $plan->total_amount = $plan->sub_fee + $plan->reg_fee + $plan->sub_fee + $plan->total_tax_amount;
            }
        }

        return $plans;
    }

}

