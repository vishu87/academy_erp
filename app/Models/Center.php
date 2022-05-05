<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Center extends Model
{

    protected $table = 'center';


    public static function days(){
    	return [
    		["id"=>1, "day"=>"Sunday"],
    		["id"=>2, "day"=>"Monday"],
    		["id"=>3, "day"=>"Tuesday"],
    		["id"=>4, "day"=>"Wednesday"],
    		["id"=>5, "day"=>"Thursday"],
    		["id"=>6, "day"=>"Friday"],
    		["id"=>7, "day"=>"Saturday"],
    	];
    }

    public static function days_list(){
        return [
            0=>"",
            1=>"Sunday",
            2=>"Monday",
            3=>"Tuesday",
            4=>"Wednesday",
            5=>"Thursday",
            6=>"Friday",
            7=>"Saturday",
        ];
    }

    public static function months(){
        return [
            array("id"=>"4" , "value"=>"April"),
            array("id"=>"5" , "value"=>"May"),
            array("id"=>"6" , "value"=>"June"),
            array("id"=>"7" , "value"=>"July"),
            array("id"=>"8" , "value"=>"August"),
            array("id"=>"9" , "value"=>"September"),
            array("id"=>"10" , "value"=>"October"),
            array("id"=>"11" , "value"=>"November"),
            array("id"=>"12" , "value"=>"December"),
            array("id"=>"1" , "value"=>"January"),
            array("id"=>"2" , "value"=>"February"),
            array("id"=>"3" , "value"=>"March"),
        ];
    }

    public static function month_lists(){
        return [
            array("id"=>"1" , "value"=>"January"),
            array("id"=>"2" , "value"=>"February"),
            array("id"=>"3" , "value"=>"March"),
            array("id"=>"4" , "value"=>"April"),
            array("id"=>"5" , "value"=>"May"),
            array("id"=>"6" , "value"=>"June"),
            array("id"=>"7" , "value"=>"July"),
            array("id"=>"8" , "value"=>"August"),
            array("id"=>"9" , "value"=>"September"),
            array("id"=>"10" , "value"=>"October"),
            array("id"=>"11" , "value"=>"November"),
            array("id"=>"12" , "value"=>"December"),
        ];
    }

    public static function quaters(){
        return [
            array("id"=>"1" , "value"=>"Q1(Apr-Jun)"),
            array("id"=>"2" , "value"=>"Q2(Jul-Sep)"),
            array("id"=>"3" , "value"=>"Q3(Oct-Dec)"),
            array("id"=>"4" , "value"=>"Q4(Jan-Mar)")
        ];
    }

    public static function years(){
        return [
            array("id"=>2018 , "value"=>"2018-19"),
            array("id"=>2019 , "value"=>"2019-20"),
            array("id"=>2020 , "value"=>"2020-21"),
            array("id"=>2021 , "value"=>"2021-22")
        ];
    }

    public static function years_list(){
        return [
            array("id"=>2018 , "value"=>"2018"),
            array("id"=>2019 , "value"=>"2019"),
            array("id"=>2020 , "value"=>"2020"),
            array("id"=>2021 , "value"=>"2021")
        ];
    }

    // public static function authenticateUser($user, $center_id){
    //     if($user->priv == 'admin') return true;

    //     $check = DB::table("members_priv")->where("user_id",$user->id)->where("center_id",$center_id)->count();
        
    //     if($check == 0) return false;

    //     return true;

    // }

    // public static function getPRR($start,$end){
        
    //     $cities = DB::table("city")->select("id","city_name")->get();

    //     $today = date("Y-m-d", strtotime ( "today" ) ) ;
    //     $thirty_day_back = date("Y-m-d", strtotime ( '-1 month' , strtotime ( "today" ) )) ;

    //     $start_ymd = date("Y-m-d",$start);
    //     $end_ymd = date("Y-m-d",$end);

    //     $total_active_students = 0;
    //     $total_capacity = 0;
    //     $total_new_reg = 0;
    //     $total_drop_outs = 0;
    //     $total_inac_active = 0;
    //     $total_overdue = 0;
    //     $total_overdue_30day = 0;
    //     $sn_city = 0;
    //     $sn_center = 0;
    //     foreach ($cities as $city) {
    //         $city_capacity = 0;
    //         $city->active_students = 0;
    //         $city->capacity = 0;
    //         $city->new_reg = 0;
    //         $city->drop_outs = 0;
    //         $city->inac_active = 0;
    //         $city->overdue = 0;
    //         $city->overdue_30day = 0;
    //         $sn_city++;
    //         $city->sn = $sn_city;
    //         $centers = Center::select('center.id','center_name','center_capacity')->where("city_id",$city->id)->where('center_status','!=',1)->get();

    //         foreach ($centers as $center) {
    //             $sn_center++;
    //             $center->sn = $sn_center;
    //             $groups = Group::where('center_id',$center->id)->pluck('id')->all();

    //             $center->active_students = Student::whereIn('first_group',$groups)->where('active',0)->count();
    //             $city->active_students += $center->active_students;

    //             $city->capacity += $center->center_capacity;

    //             if($center->center_capacity > 0){
    //                 $center->capacity_utilization = round(($center->active_students / $center->center_capacity)*100,2).'%';
    //             } else {
    //                 $center->capacity_utilization = '0%';
    //             }
    //             $city_capacity += $center->center_capacity; 
    //             $center->new_reg = Student::whereIn('first_group',$groups)->where('active',0)->whereBetween('dos',[$start,$end])->count();
    //             $city->new_reg += $center->new_reg;

    //             $center->drop_outs = DB::table('inactive_history')->join('students','students.id','=','inactive_history.student_id')->whereIn('students.first_group',$groups)->whereBetween('inactive_on',[$start,$end])->count();
    //             $city->drop_outs += $center->drop_outs;

    //             $center->inac_active = DB::table('students')->whereIn('students.first_group',$groups)->whereBetween('doreact',[$start_ymd,$end_ymd])->count();
    //             $city->inac_active += $center->inac_active;

    //             $center->overdue = Student::whereIn('first_group',$groups)->where('active',0)->where('doe','<',strtotime($today))->count();
    //             $city->overdue += $center->overdue;

    //             $center->overdue_30day = Student::whereIn('first_group',$groups)->where('active',0)->where('doe','<=',strtotime($thirty_day_back))->count();
    //             $city->overdue_30day += $center->overdue_30day;

    //             if($center->active_students > 0){
    //                 $center->prr = round(($center->overdue / $center->active_students)*100 , 2) . '%';
    //             } else {
    //                 $center->prr = "0%";
    //             }
    //         }

    //         $city->centers = $centers;
    //         if($city->capacity > 0){
    //             $city->capacity_utilization = round(($city->active_students / $city->capacity)*100,2).'%';
    //         } else {
    //             $city->capacity_utilization = '0%';
    //         }

    //         if($city->active_students > 0){
    //             $city->prr = round(($city->overdue / $city->active_students)*100 , 2) . '%';
    //         } else {
    //             $city->prr = "0%";
    //         }
    //         $city->city_capacity = $city_capacity;
    //         $total_active_students += $city->active_students;
    //         $total_capacity += $city->capacity;
    //         $total_new_reg += $city->new_reg;
    //         $total_drop_outs += $city->drop_outs;
    //         $total_overdue += $city->overdue;
    //         $total_overdue_30day += $city->overdue_30day;

    //     }

    //     $total = array(
    //         "active_students" => $total_active_students,
    //         "capacity" => $total_capacity,
    //         "new_reg" => $total_new_reg,
    //         "drop_outs" => $total_drop_outs,
    //         "overdue" => $total_overdue,
    //         "overdue_30day" => $total_overdue_30day,
    //         "inac_active" => $total_inac_active
    //     );

    //     if($total_capacity > 0){
    //         $total_capacity_utilization = round(($total_active_students / $total_capacity)*100,2).'%';
    //     } else {
    //         $total_capacity_utilization = '0%';
    //     }

    //     if($total_active_students > 0){
    //         $total_prr = round(($total_overdue / $total_active_students)*100 , 2) . '%';
    //     } else {
    //         $total_prr = "0%";
    //     }

    //     $total["capacity_utilization"] = $total_capacity_utilization;
    //     $total["prr"] = $total_prr;

    //     return [
    //         "cities" => $cities,
    //         "total" => $total
    //     ];
    // }

    // public static function getPRRHistorical($date){

    //     $cities = PRR::select("prr_reports.city_id as id","prr_reports.active_students","prr_reports.capacity as city_capacity","prr_reports.capacity_utilization","prr_reports.prr","prr_reports.overdue","prr_reports.overdue_30days as overdue_30day","prr_reports.new_reg","prr_reports.dropouts as drop_outs","city_name")->join("city","city.id","=","prr_reports.city_id")->where("center_id",0)->where("prr_date",$date)->get();
    //     $city_sn = 0;
    //     $center_sn = 0;
    //     foreach ($cities as $city) {
    //         $city->capacity_utilization = $city->capacity_utilization."%";
    //         $city->prr = $city->prr."%";
    //         $city->sn = ++$city_sn;
    //         $centers = PRR::select("prr_reports.center_id as id","prr_reports.active_students","prr_reports.capacity as center_capacity","prr_reports.capacity_utilization","prr_reports.prr","prr_reports.overdue","prr_reports.overdue_30days as overdue_30day","prr_reports.new_reg","prr_reports.dropouts as drop_outs","center_name")->join("center","center.id","=","prr_reports.center_id")->where("prr_reports.city_id",$city->id)->where("prr_date",$date)->get();
    //         foreach ($centers as $center) {
    //             $center->capacity_utilization = $center->capacity_utilization."%";
    //             $center->prr = $center->prr."%";
    //             $center->sn = ++$center_sn;
    //         }
    //         $city->centers = $centers;
    //     }

    //     $total = PRR::select("prr_reports.active_students","prr_reports.capacity","prr_reports.capacity_utilization","prr_reports.prr","prr_reports.overdue","prr_reports.overdue_30days as overdue_30day","prr_reports.new_reg","prr_reports.dropouts as drop_outs")->where("city_id",-1)->where("prr_date",$date)->first();

    //     return [
    //         "cities" => $cities,
    //         "total" => $total
    //     ];

    // }
}

