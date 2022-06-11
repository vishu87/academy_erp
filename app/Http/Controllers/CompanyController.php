<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, App\Models\User, App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller {

	public function index(){
		return view('manage.company.index',["sidebar" => "companies", "menu"=>"inventory"]);
	}

	public function companiesList(Request $request){
		$user = User::AuthenticateUser($request->header("apiToken"));
		$companies = DB::table('companies')->where("client_id",$user->client_id)->get();
		$data['success'] = true;
		$data['items'] = $companies;
		return Response::json($data, 200, array());
	}


	public function saveCompany(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
		$cre = [
			"company_name" => $request->company_name
		];

		$rules = [
			"company_name"=>"required"
		];

		$validator = validator::make($cre,$rules);

		if ($validator->passes()) {

			if($request->id){
				$company = Company::where("id",$request->id)->where("client_id",$user->client_id)->first();
			}else{
				$company = new Company;
			}

			$company->company_name	= $request->company_name;
			$company->contact_no	= $request->contact_no;
			$company->address       = $request->address;
			$company->client_id    = $user->client_id;
			$company->added_by     = $user->id;
			$company->save();

			$data['success'] = true;
			$data['message'] = "Company is successfully saved";
		}else{
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();

		}
		return Response::json($data, 200, array());

	}

	public function deleteCompanies(Request $request, $id){

		$user = User::AuthenticateUser($request->header("apiToken"));

		DB::table('companies')->where('id',$id)->where("client_id",$user->client_id)->delete();
		$data['success'] = true;
		$data['message'] = "Company is deleted successfully"; 
		return Response::json($data, 200, array());
	}

	public function stock(){
		return view('manage.stock.index',["sidebar" => "stock", "menu"=>"inventory"]);
	}

}


                 