<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB;
use Illuminate\Http\Request;
use App\Models\Utilities;

class RenewalWebController extends Controller
{	

    public function searchStudent(Request $request){

        $mobile_number = $request->mobile_number;

        $student_ids = [];
        $student_ids = DB::table("students")->where("mobile",$mobile_number)->pluck("student_id")->toArray();
        $guardians = DB::table("student_guardians")->where("mobile",$mobile_number)->pluck("student_id")->toArray();
        $student_ids = array_merge($student_ids,$guardians);

        if(sizeof($student_ids) > 0){
            $students = DB::table("students")->select("students.id", "students.name", "students.dob", "students.doe", "students.father", "groups.group_name", "center.center_name", "students.code","students.email","students.group_id")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->whereIn("students.id",$student_ids)->orderBy("students.name")->limit(5)->get();

            foreach($students as $student){

                $student->code = 'BU'.str_pad($student->id, 6, '0', STR_PAD_LEFT);

                $student->dob = $student->dob ? Utilities::convertDateShow($student->dob) : "NA";
                $student->doe = $student->doe ? Utilities::convertDateShow($student->doe) : "NA";
                
                $email = "";
                if($student->email && !$email) $email = $student->email;

                $student->email = $email;
            }
        } else {
            $students = [];
        }

        $data["success"] = true;
        $data["students"] = $students;

        return Response::json($data,200,array());
    }


}
