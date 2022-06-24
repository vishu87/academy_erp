<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redirect;

class QueryController extends Controller {

    public function index(){
        return view('query.index',['menu' => "academy"]);
    }

    public function saveQuery(Request $request){
        $query = $request->db_query;
        return redirect::back()->with('message', 'Query successfully inserted'); 
    }

}


                 