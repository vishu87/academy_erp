<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, App\User, App\Invrequest, App\InvStock;
use Illuminate\Http\Request;

class RequestController extends Controller {

	public function index(){
		return view('manage.request.index',["sidebar" => "request", "menu"=>"inventory"]);
	}

	public function companiesList(){
		$companies = DB::table('companies')->get();
		$data['success'] = true;
		$data['companies'] = $companies;
		return Response::json($data, 200, array());
	}


	public function saveRequest(Request $request){

        $cre = [

            "type" => isset($request->data['type']) ? $request->data['type'] : '',
            "date" => isset($request->data['date']) ? $request->data['date'] : ''
        ];

        $rules = [

	        "type" => "required",
	        "date" => "required"
     	];

        $validator = Validator::make($cre, $rules);
        
        if ($validator->passes()) {

        	$new_request = false;

			if(isset($request->data['id'])){
				$old_items = DB::table('inv_request_item')->where('inv_request_id',$request->data['id'])->get();
				DB::table('inv_request_item')->where('inv_request_id',$request->data['id'])->delete();
				$invRequest = Invrequest::find($request->data['id']);
			} else {
				$new_request = true;
				$invRequest = new Invrequest;
				$invRequest->type	         = $request->data['type'];
				$invRequest->out_city_id     = isset($request->data['out_city_id']) ? $request->data['out_city_id'] : NULL;
				$invRequest->out_center_id   = isset($request->data['out_center_id']) ? $request->data['out_center_id'] : NULL;
				$invRequest->in_city_id      = isset($request->data['in_city_id']) ? $request->data['in_city_id'] : NULL;
				$invRequest->in_center_id    = isset($request->data['in_center_id']) ? $request->data['in_center_id'] : NULL;
			}

			$invRequest->company_id	     = isset($request->data['company_id']) ? $request->data['company_id'] : NULL;
			$invRequest->date            = date("Y-m-d",strtotime($request->data['date']));
			$invRequest->invoice_number  = isset($request->data['invoice_number']) ? $request->data['invoice_number'] : NULL;
			$invRequest->document        = $request->file;
			$invRequest->remark          = isset($request->data['remark']) ? $request->data['remark'] : NULL;
			$invRequest->save();

			if(!$new_request){
				foreach ($old_items as $old_item) {
					if($invRequest->in_center_id){
						InvStock::updateStock($invRequest->in_city_id, $invRequest->in_center_id, $old_item->item_id, -1*$old_item->quantity);
					}
					if($invRequest->out_center_id){
						InvStock::updateStock($invRequest->out_city_id, $invRequest->out_center_id, $old_item->item_id, $old_item->quantity);
					}
				}
			}

			if(sizeof($request->items) > 0){
				foreach ($request->items as  $item) {
					if($item){
						DB::table('inv_request_item')->insert([
							'item_id'        => isset($item['item_id']) ? $item['item_id'] : 0,
							'quantity'       => isset($item['quantity']) ? $item['quantity'] : NULL,
							'inv_request_id' => $invRequest->id
						]);

						if($invRequest->in_center_id){
							InvStock::updateStock($invRequest->in_city_id, $invRequest->in_center_id, $item['item_id'], $item['quantity']);
						}
						
						if($invRequest->out_center_id){
							InvStock::updateStock($invRequest->out_city_id, $invRequest->out_center_id, $item['item_id'], -1*$item['quantity']);
						}
					}
				}
			}
			$data['success'] = true;
			$data['message'] = "Inventory Request successfully inserted....";
		}else{
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

		return Response::json($data, 200, array());

	}

	public function deleteData($id){

		$inv_item = DB::table('inv_request')->select('inv_request.*','inv_request_item.item_id','inv_request_item.quantity')
		->leftJoin('inv_request_item','inv_request_item.inv_request_id','=','inv_request.id')
		->where('inv_request_id',$id)->get();

		if(sizeof($inv_item) > 0){
			foreach ($inv_item as  $item) {

				if($item->in_center_id){
					InvStock::updateStock($item->in_city_id, $item->in_center_id, $item->item_id, -1*$item->quantity);
				}
				
				if($item->out_center_id){
					InvStock::updateStock($item->out_city_id, $item->out_center_id, $item->item_id, $item->quantity);
				}	
			}
		}

		DB::table('inv_request')->where('id',$id)->delete();
		DB::table('inv_request_item')->where('inv_request_id',$id)->delete();

		$data['success'] = true;
		$data['message'] = "Inventory Request deleted successfully"; 
		return Response::json($data, 200, array());
	}


    public function uploadDocument(){

        $destination = 'uploads/';
        if(Input::hasFile('media')){
            $file = Input::file('media');
            $extension = Input::file('media')->getClientOriginalExtension();
            $name = 'file_'.strtotime("now").'.'.strtolower($extension);
            $file = $file->move($destination, $name);
            $data["success"] = true;
            $data["media"] = $destination.$name;
            $data['media_link'] = url($destination.$name);
        }
        return Response::json($data, 200, array());
    }

    public function addRequest($id){

        return view('manage.request.add_request', ["id"=>$id,'sidebar' => "request", "menu"=>"inventory"]);
    }

    public function requestList(){

    	$request = DB::table('inv_request')->select('inv_request.*','companies.company_name as companyName')->leftJoin('companies','companies.id','=','inv_request.company_id')->orderBy('id','DESC')->get();
		$data['success'] = true;
		$data['request'] = $request;
		return Response::json($data, 200, array());

    }

    public function requestData($id){

    	$request = DB::table('inv_request')->where('id',$id)->first();
    	if($id != 0){
    		$request->date = date("d-m-Y",strtotime($request->date));
    	}
    	$items   = DB::table('inv_request_item')->where('inv_request_id',$id)->get(); 

		$data['success'] = true;
		$data['request'] = $request;
		$data['items'] 	 = $items;
		return Response::json($data, 200, array());    	

    }


    public function viewData($id){

    	$request = DB::table('inv_request')->select('inv_request.*','companies.company_name','toCenter.center_name as toCenterName','toCity.city_name as toCityName','fromCenter.center_name as fromCenterName','fromCity.city_name as fromCityName')
    	->leftJoin('companies','companies.id','=','inv_request.company_id')
    	->leftJoin('center as toCenter','toCenter.id','=','inv_request.in_center_id')
    	->leftJoin('city as toCity','toCity.id','=','inv_request.in_city_id')
    	->leftJoin('center as fromCenter','fromCenter.id','=','inv_request.out_center_id')
    	->leftJoin('city as fromCity','fromCity.id','=','inv_request.out_city_id')
    	->where('inv_request.id',$id)->first();


    	$request->date = date("d-m-Y",strtotime($request->date));
    	$items   = DB::table('inv_request_item')->select('inv_request_item.*','items.item_name')
    	->leftJoin('items','items.id','=','inv_request_item.item_id')
    	->where('inv_request_id',$id)->get(); 

		$data['success'] = true;
		$data['request'] = $request;
		$data['items'] 	 = $items;
		return Response::json($data, 200, array());    	

    }


    public function ItemsList(){

    	$allItems = DB::table('items')->get();
		$data['success']  = true;
		$data['allItems'] = $allItems;
		return Response::json($data, 200, array());

    }

}


                 