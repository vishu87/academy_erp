<?php

namespace App\Http\Controllers;

use Redirect,App\Student;
use Response,Validator,DB,Input;
use Illuminate\Http\Request;
use App\Models\EMAILTemplate;

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

			if($request->id){
				$email_template = EMAILTemplate::find($request->id);
				$data['message'] = "Data successfully updated";
			} else {
				$email_template = new EMAILTemplate;
				$data['message'] = "Data successfully inserted";
			}

			$email_template->template_name = $request->template_name;
			$email_template->content = $request->content;
			$email_template->save();
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
