<?php

namespace App\Models;

use Illuminate\Support\Str;
use DB;

class Utilities {

    public static function convertDateShow($date){
        if(!$date){
            return "";
        } else {
            return date("d-M-Y",strtotime($date));
        }
    }

    public static function convertDate($date){
        if(!$date){
            return "";
        } else {
            return date("d-m-Y",strtotime($date));
        }
    }

    public static function convertDateToDB($date){
        if(!$date){
            return null;
        } else {
            return date("Y-m-d",strtotime($date));
        }
    }

    public static function getColor($doe, $inactive){
        if($inactive == 1){
            return "#ff5b5b";
        } else {
            if( date("Y-m-d",strtotime($doe)) < date("Y-m-d")){
                return "#f6e957";
            } else {
                return "#57f65c";
            }
        }
    }

    public static function getPicture($pic, $type){
        if($pic){
            return url($pic);
        } else {
            return url("assets/images/student.png");
        }
    }

    public static function getStatus($inactive){
        switch ($inactive){
            case 0:
                return "Active";
            case 1:
                return "Inactive";
            case 2:
                return "Paused";
            default:
                return "";
        }
    }

    public static function calculateEndDate($start_date, $months, $adjustments = 0){

        $dos = strtotime($start_date);
        
        $doe = strtotime('+'.$months.' month',$dos) - 86400;

        if($adjustments > 0){
            $doe = strtotime('+'.$adjustments.' days',$doe);
        }

        return date( "Y-m-d", $doe );

    }

    public static function daysDiff($start_date, $end_date){

        return (strtotime($end_date) - strtotime($start_date))/86400 + 1;

    }

    public static function getUniqueInTable($table_name, $column_name){
        $flag = true;
        while($flag){
            $uniqid = Str::random(10);
            $check = DB::table($table_name)->where($column_name,$uniqid)->first();
            if(!$check) $flag = false;
        }
        return $uniqid;
    }

}

