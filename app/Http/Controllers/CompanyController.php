<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, App\User, App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller {

	public function index(){
		return view('manage.company.index',["sidebar" => "companies", "menu"=>"inventory"]);
	}

	public function companiesList(){
		$companies = DB::table('companies')->get();
		$data['success'] = true;
		$data['items'] = $companies;
		return Response::json($data, 200, array());
	}


	public function saveCompany(Request $request){


		$cre = [
			"company_name" => $request->company_name
		];

		$rules = [
			"company_name"=>"required"
		];

		$validator = validator::make($cre,$rules);

		if ($validator->passes()) {

			if($request->id){
				$company = Company::find($request->id);
			}else{
				$company = new Company;
			}

			$company->company_name	= $request->company_name;
			$company->contact_no	= $request->contact_no;
			$company->address       = $request->address;
			$company->save();

			$data['success'] = true;
			$data['message'] = "item successfully inserted";
		}else{
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();

		}
		return Response::json($data, 200, array());

	}

	public function deleteCompanies($id){
		DB::table('companies')->where('id',$id)->delete();
		$data['success'] = true;
		$data['message'] = "Companies deleted successfully"; 
		return Response::json($data, 200, array());
	}

	public function stock(){
		return view('manage.stock.index',["sidebar" => "stock", "menu"=>"inventory"]);
	}

}


                 