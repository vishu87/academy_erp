<?php

namespace App\Http\Controllers;

use Redirect,App\Models\Student;
use Response,Validator,DB;
use Illuminate\Http\Request;
use App\Models\SMSTemplate, App\Models\User;

class SMSTemplateController extends Controller {


	public function index(){
        User::pageAccess(20);
		return view('manage.template.index',["sidebar" => "template","menu"=>"communication"]);
	}

	
	public function init(Request $request)
	{
		$user = User::AuthenticateUser($request->header("apiToken"));
		$data['templates'] = DB::table("sms_templates")->where("client_id",$user->client_id)->get();
		$data['success'] = true;
		return Response::json($data,200,[]); 
	}

	public function store(Request $request)
	{
		$user = User::AuthenticateUser($request->header("apiToken"));

		$cre = [
			"template"=>$request->template,
			"dlt_template_id"=>$request->dlt_template_id,
			"dlt_sender_id"=>$request->dlt_sender_id,
			"dlt_pe_id"=>$request->dlt_pe_id,
			"name"=>$request->name,

		];
		$rules = [
			"template"=>"required",
			"dlt_template_id"=>"required",
			"dlt_sender_id"=>"required",
			"dlt_pe_id"=>"required",
			"name"=>"required",
		];
		$validator = Validator::make($cre ,$rules);
		if($validator->passes()){
			$template = SMSTemplate::where("id",$request->id)->where("client_id",$user->client_id)->first();
			$data['message'] = 'SMS Template is updated successfully';
			if(!$template){
				$template = new SMSTemplate;
				$template->client_id    = $user->client_id;
				$template->added_by     = $user->id;
				$data['message'] = 'SMS Template is added successfully';
			}
			$template->template = $request->template;
			$template->type = $request->type;
			$template->dlt_template_id = $request->dlt_template_id;
			$template->dlt_sender_id = $request->dlt_sender_id;
			$template->dlt_pe_id = $request->dlt_pe_id;
			$template->name = $request->name;
			$template->save();

			$data['success'] = true;
			$data['template'] = $template;
		}else{
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
		}
		return Response::json($data,200,[]);
	}

	public function delete(Request $request, $id)
	{
		$user = User::AuthenticateUser($request->header("apiToken"));
		DB::table('sms_templates')->where('id',$id)->where("client_id",$user->client_id)->delete();
		$data['success'] = true;
		$data['message'] = 'SMS Template is deleted successfully';
		return Response::json($data,200,[]);
	}
}
