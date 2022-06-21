<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class InvStock extends Model
{

    protected $table = 'inv_stocks';

    public static function updateStock($city_id, $center_id, $item_id, $quantity, $client_id){

    	$stocks = DB::table('inv_stocks')->where('city_id',$city_id)->where('center_id',$center_id)->where('item_id',$item_id)->first();

    	if($stocks == null){
	    	$stocks = DB::table('inv_stocks')->insert([
	    		'city_id'   => $city_id,
	    		'center_id' => $center_id,
	    		'item_id'   => $item_id,
	    		'quantity'  => $quantity,
	    		'client_id' => $client_id
	    	]);    		
    	}else{
    		$quantity = $quantity+$stocks->quantity;
    		$stocks = DB::table('inv_stocks')->where('id',$stocks->id)->update([
	    		'city_id'   => $city_id,
	    		'center_id' => $center_id,
	    		'item_id'   => $item_id,
	    		'quantity'  => $quantity,
	    		'client_id' => $client_id
	    	]); 
    	}
    	return $stocks;
    }



}

