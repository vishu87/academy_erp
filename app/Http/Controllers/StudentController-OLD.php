<?php

namespace App\Http\Controllers;

use  Redirect, Validator, Hash, Response, Session, DB;
use Illuminate\Http\Request;

use App\PaymentHistory, App\PaymentItem, App\User, App\Student, App\Utilities;

class StudentController extends Controller
{	

    public function students(){
        return view('students.students',[
            'menu' => "academy",
            'sidebar' => "students"
        ]);
    }

    public function add_student(){
        
        return view('students.add_student', [
            "id"=>0,
            'menu' => "academy",
            'sidebar' => "students"
        ]);
    }

    public function edit_student($id){
        return view('students.add_student',[
            "id"=>$id,
            'menu' => "academy",
            'sidebar' => "students"
        ]);
    }

    public function student_personal_detail($id){
        return view('students.student_full_details',[
            'id'=>$id,
            'menu' => "academy",
            'sidebar' => "students"
        ]);
    }

    public function student_list(Request $request){

        header('Access-Control-Allow-Headers: *');

        $user = User::AuthenticateUser($request->header("apiToken"));
        $user_access = User::getAccess("st-profile",$user->id);

        $max_per_page = $request->max_per_page;
        $page_no = $request->page_no;

        $students = DB::table('students')->select('students.id','students.name','students.dob','students.gender','students.doe','students.group_id','groups.group_name','groups.center_id','center.id as center_id','center.center_name','center.city_id','city.id as city','city.city_name','center.center_name','students.inactive','students.pic');

        if($user_access->all_access) {

        } else {
            $students = $students->whereIn("students.group_id",$user_access->group_ids);
        }

        if($request->first_group){
            if($request->first_group != 0){
                $students = $students->where("group_id",$request->first_group);
            }
        }

        if($request->center_id){
            $groups = DB::table('groups')->where('center_id',$request->center_id)->pluck('id');
            $students = $students->whereIn("group_id",$groups);
        }

        if($request->city_id){
            if($request->city_id != 0){
                $center = DB::table('center')->where('city_id',$request->city_id)->pluck('id');
                $groups = DB::table('groups')->whereIn('center_id',$center)->pluck('id')->toArray();
                $students = $students->whereIn("group_id",$groups);
            }
        }

        if($request->status){
            if($request->status){
                $status_arr = [];
                foreach ($request->status as $key => $value) {
                    if ($value) {
                        array_push($status_arr, $key);
                    }
                }
                if (sizeof($status_arr) > 0) {
                    $students = $students->whereIn("students.inactive",$status_arr);
                }
            }
        }

        if($request->pending_renewal){
            $date_ref = date("Y-m-d");
            $students = $students->where("students.doe","<=",$date_ref);
        }

        if($request->student_name){
            if($request->student_name != ""){
                $students = $students->where("students.name","LIKE","%".$request->student_name."%");
            }
        }

        if($request->mobile){
            if($request->mobile != ""){
                $students = $students->where("mobile","LIKE","%".$request->mobile."%");
            }
        }

        if($request->father_name){
            if($request->father_name != ""){
                $students = $students->where("father","LIKE","%".$request->father_name."%");
            }
        }

        if($request->gender){
            if(sizeof($request->gender) > 0){
                $students = $students->whereIn("gender",$request->gender);
            }
        }

        if( $request->subscription_expeired_from || $request->subscription_expeired_to) {
            $students = $students->whereBetween('students.doe', [date('Y-m-d',strtotime($request->subscription_expeired_from)), date('Y-m-d',strtotime($request->subscription_expeired_to)) ]);
        }

        $students = $students->where("students.client_id",$user->client_id);

        $total_students = $students->count();
        $data['total'] = $total_students;

        $students = $students->leftJoin('groups','students.group_id','=','groups.id')->leftJoin('center','groups.center_id','=','center.id')->leftJoin('city','center.city_id','=','city.id');

        if($request->export == 'export' ){
            $students = $students->get();
            if( sizeof($students) > 0 ){
                 include(app_path().'/ExcelExport/export_student.php');
            } else {
                return Redirect::back()->with('failure','No data found to export');
            }
        }

        $students = $students->limit($max_per_page)->skip(($page_no - 1)*$max_per_page)->get();

        foreach ($students as $student) {
            
            $student->dob = Utilities::convertDate($student->dob);
            $student->doe = Utilities::convertDate($student->doe);

            $student->color = Utilities::getColor($student->doe, $student->inactive);
            $student->pic = Utilities::getPicture($student->pic,'student');
        }

        $data["success"] = true;
        $data["students"] = $students;

        return json_encode($data);

    }

    public function getStudentInfo(Request $request, $studentId){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $student = Student::listing()->where('students.id', '=',$studentId)->first();

        $check_access = User::getAccess("st-profile", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $student->dob = Utilities::convertDate($student->dob);
        $student->doe = Utilities::convertDate($student->doe);
        $student->color = Utilities::getColor($student->doe, $student->inactive);
        $student->pic = Utilities::getPicture($student->pic,'student');
        $student->status = Utilities::getStatus($student->inactive);

        $student->guardians = Student::getGuardians($student->id);

        $data["success"] = true;
        $data["student"] = $student;
        return Response::json($data, 200 ,[]);
    }

    public function studentDetails(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $id = $request->student_id;
        $student = Student::listing()->where('students.id', '=',$id)->first();

        $check_access = User::getAccess("st-profile", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $student->dob = Utilities::convertDate($student->dob);
        $student->doe = Utilities::convertDate($student->doe);
        $student->color = Utilities::getColor($student->doe, $student->inactive);
        $student->pic = Utilities::getPicture($student->pic,'student');
        $student->status = Utilities::getStatus($student->inactive);

        $student->parameters = Student::getParameters($id);

        $student->edit_access = User::getAccess("st-edit", $user->id, $student->group_id);
        $student->payment_access = User::getAccess("pt-view", $user->id, $student->group_id);
        $student->payment_edit_access = User::getAccess("pt-edit", $user->id, $student->group_id);
        $student->pauses_add_access = User::getAccess("pause-add", $user->id, $student->group_id);
        $student->pauses_approve_access = User::getAccess("pause-approve", $user->id, $student->group_id);

        $student->guardians = Student::getGuardians($id);
        $student->payments = Student::getPayments($id);
        
        $student->subscriptions = Student::getSubscriptions($id);
        $student->pauses = Student::getPendingPauses($id);

        $student->injuries = Student::getInjuries($id);
        $student->inactive_history = Student::inactiveHistory($id);
        $student->group_shifts = Student::groupShiftData($id);
        $student->documents = Student::documents($id);

        $data = [
            "student" => $student,
        ];

        return Response::json($data, 200, array());
    }

    public function add_student_data1(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $student_data = Input::get('student_data');

        $rules = [
            "name"=>"required",
            "gender"=>"required",
            "dob"=>"required",
            "group_id"=>"required",
            "address"=>"required",
            "state_id"=>"required"
        ];

        $validator = validator($student_data,$rules);
        
        if ($validator->passes()){
            
            $name = $student_data["name"];
            $gender = $student_data["gender"];
            
            if(!User::getAccess("st-edit", $user->id, $student_data["group_id"])){
                $data["success"] = false;
                $data["message"] = "You are not allowed to create/edit students in this group";
                return Response::json($data, 200 ,[]);
            }

            $dob = date('Y-m-d',strtotime($student_data["dob"]));
            $email = isset($student_data["email"]) ? $student_data["email"] : "";
            $school = isset($student_data["school"]) ? $student_data["school"] : "";
            $mobile = isset($student_data["mobile"]) ? $student_data["mobile"] : "";
            $state_id = $student_data["state_id"];
            $state_city_id = $student_data["state_city_id"];
            $center_id = $student_data["center_id"];
            $group_id = $student_data["group_id"];
            $address = isset($student_data["address"]) ? $student_data["address"] : "";

            $pic = isset($student_data["pic"]) ? $student_data["pic"] : "";

            if (isset($student_data["id"])) {
                if ($student_data["id"] > 0) {
                    $student =  Student::find($student_data["id"]);
                    $data["message"] = "Student profile is update successfully";
                }
            } else {
                $student =  new Student;
                $student->group_id = $group_id;
                $student->client_id = $user->client_id;

                $data["message"] = "New student profile is created successfully";
            }

            $student->name = $name;
            $student->gender = $gender;
            $student->dob = $dob;
            $student->email = $email;
            $student->school = $school;
            $student->mobile = $mobile;
            $student->state_id = $state_id;
            $student->state_city_id = $state_city_id;
            $student->address = $address;
            $student->added_by = $user->id;
            $student->save();
            
            if (isset($student_data["guardians"])) {
                DB::table("student_guardians")->where("student_id",$student->id)->delete();
                foreach ($student_data["guardians"] as $value) {
                    DB::table("student_guardians")->insert([
                        "student_id" => $student->id,
                        "relation_type" => $value["relation_type"],
                        "name" => $value["name"],
                        "mobile" => $value["mobile"],
                        "email" => $value["email"],
                    ]);
                    if ($value['relation_type'] == 1) $student->father = $value["name"];
                    if ($value['relation_type'] == 2) $student->mother = $value["name"];
                }
            }
            $student->save();
            $data['studentId'] = $student->id;
            $data["success"] = true;
        }
        else{
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return json_encode(['student_data'=>$student_data,'status'=>$data]);
    }

    public function get_update_student1(){

        $id = Input::get("student_id");

        $parameter = DB::table('parameters')
        ->select('parameters.id','parameters.parameter','student_parameters.parameter_data',
            'parameters.parameter_type','parameters.parameter_values')
        ->where('student_parameters.student_id',$id)
        ->leftJoin('student_parameters', 'student_parameters.parameter_id', '=','parameters.id') 
        ->get();  

        if (count($parameter) == 0) {
            $parameter = DB::table('parameters')
            ->select('parameters.id','parameters.parameter','parameters.parameter_type',
                'parameters.parameter_values')
            ->get(); 
        }

        foreach ($parameter as $par) {
                if($par->parameter_type == 'select'){
                    $par->parameter_values = explode(",",$par->parameter_values);
                }
            }    

        $details = DB::table('students')->select('students.id','students.name','students.code',
            'students.gender','students.dob','students.email','students.school','students.mobile',
            'students.father','students.father_mob','students.father_email','students.mother',
            'students.mother_mob','students.mother_email','students.address','students.added_by',
            'students.group_id','students.pic','groups.group_name','groups.center_id','center.center_name',
            'center.city_id','city.city_name','city.state_id','states.state_name')
        ->where('students.id', '=', $id)
        ->leftJoin('groups', 'students.group_id', '=', 'groups.id')
        ->leftJoin('center', 'groups.center_id', '=', 'center.id')
        ->leftJoin('city', 'center.city_id', '=', 'city.id')
        ->leftJoin('states', 'city.state_id', '=', 'states.id')
        ->first();

        $details->dob = date('d-m-Y',strtotime($details->dob));
        
        if(isset($details->pic)){
            $details->pic_url = url($details->pic);
        }

        $details->parameter = $parameter; 

        $data["success"] = true;
        $data["student"] = $details;

        return Response::josn($data, 200 ,[]);
    }

    public function editStudent(){
        $id = Input::get("student_id");
        if(isset($id)){
            $student = DB::table('students')->select('students.id','students.name','students.state_city_id','students.pic',
            'students.gender','students.address','students.dob','students.email','students.school','students.state_id', 'students.mobile','students.group_id','groups.center_id','center.city_id',
            'city.city_name', 'states.state_name')
            ->leftJoin('groups', 'students.group_id', '=', 'groups.id')
            ->leftJoin('center', 'groups.center_id', '=', 'center.id')
            ->leftJoin('city', 'center.city_id', '=', 'city.id')
            ->leftJoin('states', 'city.state_id', '=', 'states.id')
            ->where('students.id','=',$id)
            ->first();

            $student->dob = date('d-m-Y',strtotime($student->dob));
            $student->pic_url = url($student->pic);

            $guardians = DB::table('student_guardians')->where("student_id",$id)->get();

            $student->guardians = $guardians;

            $data["success"] = true;
            $data["student"] = $student;
        }else{
            $data["success"] = false;
        }
        return Response::json($data ,200, []);
    }

    public function changeProfilePicture(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $id = $request->stduent_id;
        $pic = $request->picture;

        $student = DB::table('students')->find($id);

        $check_access = User::getAccess("st-edit", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }
        
        if ($student) {
            DB::table('students')->where('id',$id)->update([
                 'pic'=>$pic   
            ]);
            $data["success"] = true;
            $data["message"] = "Profile picture is successfully updated";
        } else {
            $data["success"] = false;
            $data["message"] = "Student does not exist";
        }

        return Response::json($data, 200, []);
    }
    
    public function saveInactive(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $student = Student::find($request->student_id);

        $check_access = User::getAccess("st-edit", $user->id, $student->group_id);
        if( !$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $cre = [
            "reason_id" => $request->reason_id,
            "last_class" => $request->last_class,
            "inactive_from" => $request->inactive_from,
        ];

        $validator =  Validator::make( $cre, ["reason_id" => "required","last_class" => "required",
            "inactive_from"=>"required"]);

        if ($validator->passes()) {
            
            $data['success'] = true;

            $other_reason = null;
            if($request->reason_id == -1){
                $other_reason = $request->other_reason;
            }

            $request->last_class = date("Y-m-d",strtotime($request->last_class));
            $request->inactive_from = date("Y-m-d",strtotime($request->inactive_from));

            $update_data = [
                "student_id" => $request->student_id,
                "last_class" => $request->last_class,
                "inactive_from" => $request->inactive_from,
                "reason_id" => $request->reason_id,
                "other_reason" =>  $other_reason
            ];

            if ($request->id) {
                DB::table('inactive')->where('id',$request->id)->update($update_data);
                $data['message'] = "Inactive details are successfully updated";
            } else {
                $data['message'] = "Student is marked inactive";

                $update_data["added_by"] = $user->id;
                $update_data["created_at"] = date("Y-m-d H:i:s");
                DB::table('inactive')->insert($update_data);

                $student->inactive = 1;
                $student->save();
            }

        } else {
            $data['success'] = false;
            $data['message'] = $validator->errors->first();
        }   

        return Response::json($data, 200 ,[]);
    }

    public function deleteInactive(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $id = $request->id;
        $check = DB::table('inactive')->find($id);

        if ($check) {

            $student = Student::find($check->student_id);
            $check_access = User::getAccess("st-edit", $user->id, $student->group_id);
            if( !$check_access) {
                $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
            }
        
            DB::table('inactive')->where('id',$id)->delete();
            $data['success'] = true;
            $data['message'] = "Item Deleted successfully";
        } else {
            $data['success'] = true;
            $data['message'] = "Item does not exist";
        }
        return Response::json($data, 200 ,[]);            
    }

    public function saveInjury(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));
        $student_id = $request->student_id;
        $student = Student::find($student_id);

        $check_access = User::getAccess("st-edit", $user->id, $student->group_id);
        if( !$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }
    
        $cre = [
            "injured_on" => $request->injured_on,
            "last_class" => $request->last_class,
            "remark" => $request->remark,
        ];

        $validator =  Validator::make( $cre, ["injured_on" => "required", "remark" => "required",
            "last_class"=>"required"]);

        if ($validator->passes()) {
            
            $injured_on = date('Y-m-d',strtotime($request->injured_on));
            $last_class = date('Y-m-d',strtotime($request->last_class));

            $insert_data = [
                "student_id" => $request->student_id,
                "injured_on" => $injured_on,
                "remark" => $request->remark,
                "last_class" => $last_class
            ];

            if ($request->id) {
                DB::table('injury')->where('id',$request->id)->update($insert_data);
                $data['message'] = "Injury details are successfully updated";
            } else {

                $insert_data["created_at"] = date("Y-m-d H:i:s");
                $insert_data["added_by"] = $user->id;
                DB::table('injury')->insert($insert_data);

                $data['message'] = "New injury record is successfully created";
            }

            $data['success'] = true;

        } else {
            $data['success'] = false;
            $data['message'] = $validator->errors->first();
        }

        return Response::json($data, 200 ,[]);
    }

    public function deleteInjury(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        $id = $request->id;

        $check = DB::table('injury')->find($id);
        
        if ($check) {
            
            $student = Student::find($check->student_id);
            $check_access = User::getAccess("st-edit", $user->id, $student->group_id);
            if( !$check_access) {
                $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
            }

            DB::table('injury')->where('id',$id)->delete();

            $data['success'] = true;
            $data['message'] = "Injury record is successfully deleted";
        }else{
            $data['success'] = true;
            $data['message'] = "Item does not exist";
        }
        return Response::json($data, 200 ,[]); 
    }

    public function groupChange(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $student = Student::find($request->student_id);
        $check_access = User::getAccess("st-edit", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }
        
        $validator = Validator::make(
            [
            "group_id" => $request->group_id,
            "effective_date" => $request->effective_date
            ],[
            "group_id" => "required",
            "effective_date" => "required"
            ]
        );

        if ($validator->fails()) {
            $data['success'] = false;   
            $data['message'] = $validator->errors()->first();   
        } else {

            $effect_date = date('Y-m-d',strtotime($request->effective_date)); 

            DB::table('group_shifting')->insert([
                "effective_date" => $effect_date,
                "student_id" => $request->student_id,
                "group_id" => $request->group_id,
                "old_group_id" => $student->group_id,
                "added_by" => $user->id
            ]);

            DB::table('students')->where('id',$request->student_id)->update([
                "group_id" => $request->group_id
            ]);

            $data['success'] = true;   
            $data['message'] = "Group has changed successfully";  
        }
        return Response::json($data, 200, array());
        
    }

    // public function deleteGroupShift1(){
    //     $id = Input::get('id');
    //     $check = DB::table('group_shifting')->find($id);
    //     if ($check) {
    //         DB::table('group_shifting')->where('id',$id)->delete();
    //         $data['success'] = true;
    //         $data['message'] = "Item Deleted successfully";
    //     }else{
    //         $data['success'] = true;
    //         $data['message'] = "Item does not exist";
    //     }
    //     return Response::json($data, 200 ,[]);
    // }

    public function saveDocuments(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $student = Student::find($request->student_id);
        $check_access = User::getAccess("st-edit", $user->id, $student->group_id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $cre = [
            "document_type" => $request->type_id,
            "document" => $request->document_url
        ];

        $validator = Validator::make( $cre, ["document_type" => "required",
         "document" => "required"]);
        
        if ($validator->passes()) {

            DB::table('documents')->insert([
                "student_id" => $request->student_id,
                "type_id" => $request->type_id,
                "document_url" => $request->document_url,
                "document_no" => $request->document_no,
                "name" => $request->name,
                "added_by" => $user->id
            ]);

            $data["success"] = true;
            $data["message"] = "Document is added successfully";
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }

        return Response::json($data, 200, []);
    }

    public function deleteDocument(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $id = $request->id;
        $check = DB::table("documents")->find($id);
        
        if ($check) {

            $student = Student::find($check->student_id);
            $check_access = User::getAccess("st-edit", $user->id, $student->group_id);
            if(!$check_access) {
                $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
            }

            DB::table("documents")->where("id",$id)->delete();
            $data["success"] = true;
            $data["message"] = "Document is successfully deleted";
        } else {
            $data["success"] = false;
            $data["message"] = "document not found";
        }

        return Response::json($data, 200 , []);
    }

    public function getDocType(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $docType = DB::table('documents_type')->get();

        $data["success"] = true;
        $data["docType"] = $docType;
        return Response::json($data, 200, []);
    }

    public function getInactiveReason(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $inactiveReasons = DB::table('reasons')->get();
        $data["success"] = true;
        $data["inactiveReasons"] = $inactiveReasons;
        return Response::json($data, 200, []);
    }

    // public function save_payment_history(){
    //     $payment_data = Input::get('payment_history');
    //     if ($payment_data) {

    //         $payment_data["dor"] = date('Y-m-d',strtotime($payment_data["dor"]));

    //         $payment_data["created_at"] = date('Y-m-d H:i:s',strtotime($payment_data["created_at"]));

    //         DB::table('payment_history')->where('id',$payment_data["history_id"])
    //         ->update([
    //             'dor'=>$payment_data["dor"],
    //             'created_at'=>$payment_data["created_at"]
    //         ]);

    //         foreach ($payment_data["items"] as $pay) {

    //             $pay["start_date"] = date('Y-m-d',strtotime($pay["start_date"]));
    //             $pay["end_date"] = date('Y-m-d',strtotime($pay["end_date"]));

    //             DB::table('payment_items')->where('id',$pay['id'])
    //             ->update([
    //                 "category_id"=>$pay['category_id'],
    //                 "type_id"=>$pay['type_id'],
    //                 "start_date"=>$pay['start_date'],
    //                 "end_date"=>$pay['end_date'],
    //                 "amount"=>$pay['amount'],
    //                 "tax"=>$pay['tax'],
    //                 "total_amount"=>$pay['total_amount'],
    //                 "adjustment"=>$pay['adjustment'],
    //                 "a_remarks"=>$pay['a_remarks']
    //             ]);
    //         }

    //         $data["success"] = true;
    //         $data["message"] = "Data is successfully updated";
    //     }else{
    //         $data["success"] = false;
    //         $data["message"] = "Unexpected Error!!";
    //     }

        
    //     return Response::json($data, 200 , []);
    // }

    public function deleteStudent1(){
        $id = Input::get("id");
        $check = DB::table('students')->find($id);
        if ($check) {
            
            $payids = DB::table('payment_history')->where("student_id",$id)->pluck("id");
            DB::table('payment_items')->whereIn("payment_history_id",$payids)->delete();
            DB::table('payment_history')->whereIn("id",$payids)->delete();
            DB::table('students')->where("id",$id)->delete();

            $data["success"] = true;
            $data["message"] = "Student deleted successfully";
        }else{
            $data["success"] = false;
            $data["message"] = "Student not found";
        }

        return Response::json($data, 200 ,[]);
    }

    function getNameFromNumber($num) {
        $numeric = ($num ) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num ) / 26) - 1;
        if ($num2 >= 0) {
            return $this->getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }

    public function paymentReceipt1($history_id, $student_id){

        $student = DB::table('students')->select('students.id','students.name','students.code',
            'students.gender','students.dob','students.email','students.school','students.mobile',
            'students.father','students.mother',
            'students.address','students.added_by',
            'students.group_id','students.pic','groups.group_name','groups.center_id','center.center_name',
            'center.city_id','city.city_name','city.state_id','states.state_name')
        ->where('students.id', '=', $student_id)
        ->leftJoin('groups', 'students.group_id', '=', 'groups.id')
        ->leftJoin('center', 'groups.center_id', '=', 'center.id')
        ->leftJoin('city', 'center.city_id', '=', 'city.id')
        ->leftJoin('states', 'city.state_id', '=', 'states.id')
        ->first();



        $payment_id =  $history_id;
        $payment = PaymentHistory::find($payment_id);
        if ($payment) {
            $payment->payment_date = date('d-m-Y',strtotime($payment->payment_date));

            $items = PaymentItem::select("payment_items.id","payment_items.category_id","payment_items.type_id","payment_items.amount","payment_items.tax","payment_items.total_amount","payment_items.start_date","payment_items.end_date","payments_type_categories.category_name as category","payments_type.name as type","payment_items.discount","payment_items.discount_code_id","coupons.code as discount_code")->leftJoin("payments_type_categories","payments_type_categories.id","=","payment_items.category_id")->leftJoin("payments_type","payments_type.id","=","payment_items.type_id")->leftJoin("coupons","coupons.id","=","payment_items.discount_code_id")->where('payment_items.payment_history_id',$payment->id)->get();

            foreach ($items as $value) {
                if ($value->start_date) {
                    $value->is_sub_type = true;
                    $value->start_date = date('d-m-Y',strtotime($value->start_date));
                }
                if ($value->end_date) {
                    $value->end_date = date('d-m-Y',strtotime($value->end_date));
                }

                $price = DB::table("payment_type_prices")
                ->where("pay_type_cat_id",$value->category_id)
                ->where("pay_type_id",$value->type_id)
                ->first();
                if ($price) {
                    $value->tax_perc = $price->tax;
                }
            }
            $payment->items = $items;
        }

        $gst = DB::table('gst')->where('defaults',1)->first();



       // $html =  view('students.payment_receipt');

        return view('students.payment_receipt',['student' => $student, 'payment' => $payment, 'gst' => $gst]);
        
        // if(env('APP_ENV') == "local"){
        //     include(app_path().'/libraries/dompdf/dompdf_config.inc.php');
        //     $dompdf = new \DOMPDF();
        // } else {
        //     include(app_path().'/libraries/dompdf/autoload.inc.php');
        //     $dompdf = new \Dompdf\Dompdf();
        // }
        
        // $dompdf = new \DOMPDF();
        // $dompdf->load_html($html);
        // $dompdf->render();
        // $filename = "Invoice-".$invoice->id.".pdf";
        // $dompdf->stream($filename,array('Attachment'=>0));

    }

}
