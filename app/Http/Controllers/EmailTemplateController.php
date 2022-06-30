<?php

namespace App\Http\Controllers;

use Redirect,App\Models\Student, App\Models\User;
use Response,Validator,DB,Input;
use Illuminate\Http\Request;
use App\Models\EMAILTemplate;

class EmailTemplateController extends Controller {


	public function index(){
        User::pageAccess(21);
		return view('manage.emailTemplate.index',["sidebar" => "email-template","menu"=>"communication"]);
	}

	
	public function init(Request $request)
	{
		$user = User::AuthenticateUser($request->header("apiToken"));
		$data['templates'] = DB::table("email_templates")->where("client_id",$user->client_id)->get();
		$data['success'] = true;
		return Response::json($data,200,[]); 
	}

	public function store(Request $request)
	{
		$user = User::AuthenticateUser($request->header("apiToken"));

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
				$email_template->client_id    = $user->client_id;
				$email_template->added_by     = $user->id;
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

	public function delete(Request $request, $id)
	{
		$user = User::AuthenticateUser($request->header("apiToken"));
		DB::table('email_templates')->where('id',$id)->where("client_id",$user->client_id)->delete();
		$data['success'] = true;
		$data['message'] = "Data successfully deleted";
		return Response::json($data,200,[]);
	}
}
