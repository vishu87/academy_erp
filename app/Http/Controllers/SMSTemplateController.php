<?php

namespace App\Http\Controllers;

use Redirect,App\Student;
use Response,Validator,DB,Input;
use Illuminate\Http\Request;
use App\Models\SMSTemplate;

class SMSTemplateController extends Controller {


	public function index()
	{
		return view('manage.template.index',["sidebar" => "template","menu"=>"communication"]);
	}

	
	public function init()
	{
		$data['templates'] = DB::table("sms_templates")->get();
		$data['success'] = true;
		return Response::json($data,200,[]); 
	}

	public function store(Request $request)
	{
		$cre = [
			"template"=>$request->template,
			"dlt_template_id"=>$request->dlt_template_id,
			"dlt_sender_id"=>$request->dlt_sender_id,
			"dlt_pe_id"=>$request->dlt_pe_id,
		];
		$rules = [
			"template"=>"required",
			"dlt_template_id"=>"required",
			"dlt_sender_id"=>"required",
			"dlt_pe_id"=>"required",
		];
		$validator = Validator::make($cre ,$rules);
		if($validator->passes()){
			$template = SMSTemplate::find($request->id);
			$data['message'] = 'SMS Template is updated successfully';
			if(!$template){
				$template = new SMSTemplate;
				$data['message'] = 'SMS Template is added successfully';
			}
			$template->template = $request->template;
			$template->type = $request->type;
			$template->dlt_template_id = $request->dlt_template_id;
			$template->dlt_sender_id = $request->dlt_sender_id;
			$template->dlt_pe_id = $request->dlt_pe_id;
			$template->save();

			$data['success'] = true;
			$data['template'] = $template;
		}else{
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
		}
		return Response::json($data,200,[]);
	}

	public function delete($id)
	{
		$template = SMSTemplate::find($id);
		if($template){
			$template->delete();
			$data['success'] = true;
			$data['message'] = 'SMS Template is deleted successfully';
		}else{
			$data['message'] = 'SMS Template can not be deleted';
			$data['success'] = false;
		}
		return Response::json($data,200,[]);
	}
}
