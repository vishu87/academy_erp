<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, App\Models\User, App\Models\Invrequest, App\Models\InvStock;
use Illuminate\Http\Request;

class RequestController extends Controller {

	public function index(){
		return view('manage.request.index',["sidebar" => "request", "menu"=>"inventory"]);
	}

	public function companiesList(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));

		$companies = DB::table('companies')->where("client_id",$user->client_id)->get();
		$data['success'] = true;
		$data['companies'] = $companies;
		return Response::json($data, 200, array());
	}


	public function saveRequest(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));

        $cre = [
            "type" => $request->type,
            "date" => $request->date
        ];

        $rules = [
	        "type" => "required",
	        "date" => "required"
     	];

        $validator = Validator::make($cre, $rules);
        
        if ($validator->passes()) {

        	$new_request = false;

			if(isset($request->id)){
				$old_items = DB::table('inv_request_item')->where('inv_request_id',$request->id)->get();
				DB::table('inv_request_item')->where('inv_request_id',$request->id)->delete();
				$invRequest = Invrequest::find($request->id);
			} else {
				$new_request = true;
				$invRequest = new Invrequest;
				$invRequest->type	         = $request->type;
				$invRequest->out_city_id     = $request->out_city_id;
				$invRequest->out_center_id   = $request->dataout_center_id;
				$invRequest->in_city_id      = $request->in_city_id;
				$invRequest->in_center_id    = $request->in_center_id;
				$invRequest->added_by     	 = $user->id;
				$invRequest->client_id     	 = $user->client_id;
			}

			$invRequest->company_id	     = $request->company_id;
			$invRequest->date            = date("Y-m-d",strtotime($request->date));
			$invRequest->invoice_number  = $request->invoice_number;
			$invRequest->document        = $request->file;
			$invRequest->remark          = $request->remark;
			$invRequest->save();

			if(sizeof($request->items) > 0){
				foreach ($request->items as  $item) {
					DB::table('inv_request_item')->insert([
						'item_id'        => isset($item['item_id']) ? $item['item_id'] : 0,
						'quantity'       => isset($item['quantity']) ? $item['quantity'] : NULL,
						'inv_request_id' => $invRequest->id
					]);
				}
			}
			$data['success'] = true;
			$data['message'] = "Inventory request is successfully saved";
		}else{
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

		return Response::json($data, 200, array());

	}

	public function deleteData(Request $request, $id){

    	$user = User::AuthenticateUser($request->header("apiToken"));

		DB::table('inv_request')->where('id',$id)->where("client_id",$user->client_id)->where("status",0)->delete();

		$data['success'] = true;
		$data['message'] = "Inventory Request deleted successfully"; 
		return Response::json($data, 200, array());
	}

    public function addRequest($id = 0){

        return view('manage.request.add_request', ["id"=>$id,'sidebar' => "request", "menu"=>"inventory"]);
    }

    public function requestList(Request $request){

    	$user = User::AuthenticateUser($request->header("apiToken"));

    	$request = DB::table('inv_request')->select('inv_request.*','companies.company_name as companyName')->leftJoin('companies','companies.id','=','inv_request.company_id')->where("inv_request.client_id",$user->client_id)->orderBy('id','DESC')->get();

    	$status = Invrequest::Status();

    	foreach ($request as $requestData) {
    		$requestData->status_name = $status[$requestData->status];
    	}

		$data['success'] = true;
		$data['request'] = $request;
		return Response::json($data, 200, array());

    }

    public function requestData(Request $request, $id = 0){

    	$user = User::AuthenticateUser($request->header("apiToken"));

    	$request = DB::table('inv_request')->where('id',$id)->where("inv_request.client_id",$user->client_id)->where("status",0)->first();
    	if($id != 0){
    		$request->date = date("d-m-Y",strtotime($request->date));
    		$request->items = DB::table("inv_request_item")->where("inv_request_id",$id)->get();
    	} else {

    	}

		$data['success'] = true;
		$data['request'] = $request;
		return Response::json($data, 200, array());    	

    }


    public function viewData(Request $request, $id){

    	$user = User::AuthenticateUser($request->header("apiToken"));

    	$request = DB::table('inv_request')->select('inv_request.*','companies.company_name','toCenter.center_name as toCenterName','toCity.city_name as toCityName','fromCenter.center_name as fromCenterName','fromCity.city_name as fromCityName')
    	->leftJoin('companies','companies.id','=','inv_request.company_id')
    	->leftJoin('center as toCenter','toCenter.id','=','inv_request.in_center_id')
    	->leftJoin('city as toCity','toCity.id','=','inv_request.in_city_id')
    	->leftJoin('center as fromCenter','fromCenter.id','=','inv_request.out_center_id')
    	->leftJoin('city as fromCity','fromCity.id','=','inv_request.out_city_id')
    	->where('inv_request.id',$id)->where("inv_request.client_id",$user->client_id)->first();


    	$request->date = date("d-m-Y",strtotime($request->date));
    	$items = DB::table('inv_request_item')->select('inv_request_item.*','items.item_name')->leftJoin('items','items.id','=','inv_request_item.item_id')->where('inv_request_id',$id)->get(); 

		$data['success'] = true;
		$data['request'] = $request;
		$data['items'] 	 = $items;
		return Response::json($data, 200, array());    	

    }


    public function ItemsList(Request $request){

    	$user = User::AuthenticateUser($request->header("apiToken"));

    	$allItems = DB::table('items')->where("client_id",$user->client_id)->get();
		$data['success']  = true;
		$data['allItems'] = $allItems;
		return Response::json($data, 200, array());

    }

    public function approveOrReject(Request $request){

    	$user = User::AuthenticateUser($request->header("apiToken"));

    	$invRequest = Invrequest::find($request->id)->where("id",$request->id)->where("client_id",$user->client_id)->first();

		$invRequest->approved_by = $user->id;
		$invRequest->status = $request->type;
		$invRequest->remarks = $request->remarks;
		$invRequest->approval_ts = date("Y-m-d H:i:s");
		$invRequest->save();

    	if($request->type == 1){

    		$items   = DB::table('inv_request_item')->select('inv_request_item.*')->where('inv_request_id',$request->id)->get(); 

    		foreach ($items as $item) {
    			if($invRequest->in_center_id){
					InvStock::updateStock($invRequest->in_city_id, $invRequest->in_center_id, $item->item_id, $item->quantity, $user->client_id);
				}
				
				if($invRequest->out_center_id){
					InvStock::updateStock($invRequest->out_city_id, $invRequest->out_center_id, $item->item_id, -1*$item->quantity, $user->client_id);
				}
    		}
    	}

    	$status_name = ($request->type == 1) ? "Approved":"Rejected";

    	$data['success']  = true;
    	$data['message']  = "Request is successfully ".$status_name;
    	$data["new_status"] = $request->type;
		return Response::json($data, 200, array());

    }

}


                 