<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Lead, App\Models\Client, App\Models\Registration, App\Models\Utilities;

class WebController extends Controller
{	

    public function registrations(){
        $heading = "Academy Registration Form";
        $description = "";

        $payment_code = "1,2,3||21";

        return view('web.registrations',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => env('APP_CLIENT_CODE'),
            "payment_gateway" => "razorpay",
            "payment_code" => $payment_code
        ]);
    }

    public function renewals(){

        $payment_code = "2|3|";

        $heading = "Renew Subscription";
        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam";

        return view('web.renewals',[
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => env('APP_CLIENT_CODE'),
            "payment_code" => $payment_code,
            "payment_gateway" => "razorpay"
        ]);
    }

    public function payments($payment_code){

        $heading = "";
        $description = "";

        return view('web.payments',[
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => env('APP_CLIENT_CODE'),
            "payment_code" => $payment_code,
            "payment_gateway" => "razorpay"
        ]);
    }

    public function demoShedule(){

        $heading = "Schedule a demo";
        $description = "Please fill the form below to schedule a demo with FCBU";

        return view('web.demo_schedule',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => env('APP_CLIENT_CODE')
        ]);
    }

    public function lead($type){

        $lead_for = DB::table("lead_for")->where("slug",$type)->first();
        if(!$lead_for){
            return "Invalid form";
        }

        $heading = $lead_for->page_title;
        $description = $lead_for->page_description;

        return view('web.lead',[
            "lead_for" => $lead_for,
            "heading" => $heading,
            "description" => $description,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => env('APP_CLIENT_CODE')
        ]);
    }

    public function signUp(){

        $heading = "Parent Sign Up";
        $description = "Kindly enter your email linked with your kid's profile";

        return view('web.sign_up',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => env('APP_CLIENT_CODE')
        ]);
    }

    public function forgetPassword(){
        $heading = "Forget Password";
        $description = "Please enter your email";

        return view('web.forget_password',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => env('APP_CLIENT_CODE')
        ]);
    }

    public function webPages($term){
        
        $client_code = env('APP_CLIENT_CODE');
        $client = DB::table("clients")->where("code",$client_code)->first();

        $params = Utilities::getSettingParams([27,28,29],$client->id);

        if($term == "terms-conditions"){
            $content = $params->param_27;
        } else if($term == "privacy-policy"){
            $content = $params->param_28;
        } else if($term == "refund-policy"){
            $content = $params->param_29;
        }

        return view('web.pages',[
            "content" => $content,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => $client_code
        ]);
    }
}
