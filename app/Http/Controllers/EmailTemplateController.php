<?php

namespace App\Http\Controllers;

use Redirect,App\Student;
use Response,Validator,DB,Input;
use Illuminate\Http\Request;
use App\SMSTemplate;

class EmailTemplateController extends Controller {


	public function index()
	{
		return view('manage.emailTemplate.index',["sidebar" => "email-template","menu"=>"communication"]);
	}

	
	public function init()
	{
		$data['templates'] = DB::table("email_templates")->get();
		$data['success'] = true;
		return Response::json($data,200,[]); 
	}

	public function store(Request $request)
	{
		$cre = [
			"template_name"=>$request->template_name,
			"content"=>$request->content
		];
		$rules = [
			"template_name"=>"required",
			"content"=>"required"
		];
		$validator = Validator::make($cre ,$rules);

		if($validator->passes()){
			$data = [
				"template_name" => $request->template_name,
				"content" => $request->content
			];
			if($request->id){
				DB::table('email_templates')->where('id',$request->id)->update($data);
				$data['message'] = "Data successfully updated";
			} else {
				DB::table('email_templates')->insert($data);
				$data['message'] = "Data successfully inserted";
			}
			$data['success'] = true;

		} else {
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
		}
		return Response::json($data,200,[]);
	}

	public function delete($id)
	{
		DB::table('email_templates')->where('id',$id)->delete();
		$data['success'] = true;
		$data['message'] = "Data successfully deleted";
		return Response::json($data,200,[]);
	}
}
