<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, App\User, App\Company;
use Illuminate\Http\Request;

class StockController extends Controller {

	public function index(){
		return view('manage.stock.index',["sidebar" => "stock", "menu"=>"inventory"]);
	}

	public function total_stock(Request $request){
		$stocks = DB::table('inv_stocks')->select('inv_stocks.*','city.city_name','center.center_name','items.item_name')
    	->leftJoin('city','City.id','=','inv_stocks.city_id')
    	->leftJoin('center','Center.id','=','inv_stocks.center_id')
    	->leftJoin('items','items.id','=','inv_stocks.item_id');

    	if($request->city_id){
    		$stocks = $stocks->where('inv_stocks.city_id',$request->city_id);
    	}

    	if($request->center_id){
    		$stocks = $stocks->where('inv_stocks.center_id',$request->center_id);
    	}
    	$stocks = $stocks->limit($request->max_per_page)->skip(($request->page_no - 1)*$request->max_per_page)->get();

    	$total_stocks = $stocks->count();
        $data['total'] = $total_stocks;
    	$data['success'] = true;
		$data['stocks'] = $stocks;
		return Response::json($data, 200, array());

		return view('manage.stock.index',["sidebar" => "stock", "menu"=>"inventory"]);
	}

}


                 