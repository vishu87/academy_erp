<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Lead, App\Models\Client, App\Models\Registration;

class WebController extends Controller
{	

    public function registrations(){
        $heading = "Academy Registration Form";
        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam";
        return view('web.registrations',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => "BU_CODE",
            "payment_gateway" => "razorpay"
        ]);
    }

    public function renewals(){

        $heading = "Renew Subscription";
        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam";

        return view('web.renewals',[
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => "BU_CODE"
        ]);
    }

    public function demoShedule(){

        $heading = "Demo Registration Form";
        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam";

        return view('web.demo_schedule',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => "BU_CODE"
        ]);
    }

    public function lead($type){

        $heading = "Apply for Scholarship";
        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam";

        return view('web.lead',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => url('assets/images/Group-60782.png'),
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => "BU_CODE"
        ]);
    }
}
