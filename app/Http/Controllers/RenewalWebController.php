<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB;
use Illuminate\Http\Request;
use App\Models\Utilities;

class RenewalWebController extends Controller
{	

    public function searchStudent(Request $request){

        $mobile_number = $request->mobile_number;
        $students = DB::table("students")->select("students.id", "students.name", "students.dob", "students.doe", "students.father", "groups.group_name", "center.center_name", "students.code","students.email")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->where(function($query) use ($mobile_number){
            $query->where("mobile","LIKE","%".$mobile_number."%");
        })->orderBy("students.name")->where("inactive",[0])->limit(5)->get();

        foreach($students as $student){

            $student->code = 'ACADEMY'.str_pad($student->id, 6, '0', STR_PAD_LEFT);

            $student->dob = $student->dob ? Utilities::convertDateShow($student->dob) : "NA";
            $student->doe = $student->doe ? Utilities::convertDateShow($student->doe) : "Paused";

            $student->mobile = "9634628573";
            
            $email = "";
            if($student->email && !$email) $email = $student->email;

            $student->email = $email;
        }

        $data["success"] = true;
        $data["students"] = $students;

        return Response::json($data,200,array());
    }


}
