<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CampaignFilter extends Model
{

    protected $table = 'campaign_filters';

    public static function products(){
    	return [
    		["id"=>1,"value"=>"Football Schools"],
    		["id"=>2,"value"=>"Residential Academy"],
    		["id"=>3,"value"=>"Camps"],
    		["id"=>4,"value"=>"Tours"],
    		["id"=>5,"value"=>"Tournaments"],
            ["id"=>6,"value"=>"Coaches"],
    		["id"=>7,"value"=>"Live Online Coaching"],
    	];
    }

    public static function product_list(){
        return [
            1=>"Football Schools",
            2=>"Residential Academy",
            3=>"Camps",
            4=>"Tours",
            5=>"Tournaments",
            6=>"Coaches",
            7=>"Live Online Coaching"
        ];
    }
}

