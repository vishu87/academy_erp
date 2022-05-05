<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, App\User, App\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller {

	public function index(){
		return view('manage.inventory.index',["sidebar" => "item", "menu"=>"inventory"]);
	}

	public function getUnits(){
		$units = DB::table('units')->get();
		$data['units'] = $units;
		$data['success'] = true;
		return Response::json($data, 200, array());
	}

	public function itemsList (){
		$items = DB::table('items')->select('items.id','items.item_name','items.unit_id','items.min_quantity','items.added_by','units.unit')
		->leftJoin('units', 'items.unit_id', '=', 'units.id')->get();
		$units = DB::table('units')->get();
		$data['success'] = true;
		$data['items'] = $items;
		$data['units'] = $units;
		return Response::json($data, 200, array());
	}


	public function saveItem(Request $request){


		$cre = [
			"item_name" => $request->item_name,
			"unit_id" => $request->unit_id,
			"min_quantity" => $request->min_quantity,
		];

		$rules = [
			"item_name" => "required",
			"unit_id" => "required",
			"min_quantity" => "required",	
		];

		$message = [
			'unit_id.required'=>"Please enter the unit"
		];

		$validator = Validator::make($cre,$rules,$message);

		if($validator->passes()){

			if($request->id){
				$item = Item::find($request->id);
			}else{
				$item = new Item;
			}

			$item->item_name	= $request->item_name;
			$item->unit_id		= $request->unit_id;
			$item->min_quantity = $request->min_quantity;
			$item->save();

			$data['success'] = true;
			$data['message'] = "item successfully inserted";
		}else{

			$data['success'] = false;
			$data['message'] = $validator->errors()->first();

		}
		return Response::json($data, 200, array());

	}

	public function deleteItems($id){
		
		DB::table('items')->where('id',$id)->delete();
		$data['success'] = true;
		$data['message'] = "Item deleted successfully"; 
		return Response::json($data, 200, array());
	}

}


                 