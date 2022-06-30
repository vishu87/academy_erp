<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, App\Models\User, App\Models\Item;
use Illuminate\Http\Request;

class InventoryController extends Controller {

	public function index(){
        User::pageAccess(18);
		return view('manage.inventory.index',["sidebar" => "item", "menu"=>"inventory"]);
	}

	public function getUnits(){
		$units = DB::table('units')->get();
		$data['units'] = $units;
		$data['success'] = true;
		return Response::json($data, 200, array());
	}

	public function itemsList (Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$items = DB::table('items')->select('items.id','items.item_name','items.unit_id','items.min_quantity','items.added_by','units.unit')
		->leftJoin('units', 'items.unit_id', '=', 'units.id')->where("client_id",$user->client_id)->get();

		$units = DB::table('units')->get();
		$data['success'] = true;
		$data['items'] = $items;
		$data['units'] = $units;
		return Response::json($data, 200, array());
	}


	public function saveItem(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

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
			$item->client_id    = $user->client_id;
			$item->added_by     = $user->id;
			$item->save();

			$data['success'] = true;
			$data['message'] = "Item is successfully saved";
		} else {

			$data['success'] = false;
			$data['message'] = $validator->errors()->first();

		}
		return Response::json($data, 200, array());

	}

	public function deleteItems(Request $request, $id){
		
		$user = User::AuthenticateUser($request->header("apiToken"));

		DB::table('items')->where('id',$id)->where("client_id",$user->client_id)->delete();
		$data['success'] = true;
		$data['message'] = "Item is deleted successfully"; 
		return Response::json($data, 200, array());
	}

}


                 