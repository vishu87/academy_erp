<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DateTime;

use Input, Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\Center, App\Models\Group, App\Models\Student;

class SettingsController extends Controller{
    
    public function index(){
        return view('manage.settings.index',["sidebar"=>"settings","menu" => "admin"]);
    }

}