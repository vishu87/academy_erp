<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, App\User, App\Company;
use Illuminate\Http\Request;

class InventoryReportController extends Controller {

	public function index(){
		return view('manage.report.index',["sidebar" => "report", "menu"=>"inventory"]);
	}

}


                 