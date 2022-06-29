<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Utilities;

class Student extends Model
{

    protected $table = 'students';

    public static function listing(){

    	return DB::table("students")->select('students.id','students.school','students.name','students.gender','students.group_id','students.dob','students.pic','students.doe','students.dos','students.tags','groups.group_name','groups.center_id', 'center.center_name', 'center.city_id','city.city_name','city.state_id','students.mobile','students.email','students.inactive','students.address','states.state_name','cities.city_name as address_city','students.state_id as student_state_id','students.client_id')
	        ->leftJoin('groups', 'students.group_id', '=', 'groups.id')
	        ->leftJoin('center', 'groups.center_id', '=', 'center.id')
	        ->leftJoin('city', 'center.city_id', '=', 'city.id')
	        ->leftJoin('states', 'city.state_id', '=', 'states.id')
            ->leftJoin('cities', 'cities.id', '=', 'students.state_city_id');
    }

    // public function mapDates(){
    // 	$this->dob = $this->dob ? date('d-M-Y',strtotime($this->dob)) : "";
    //     $this->dos = $this->dos ? date('d-M-Y',strtotime($this->dos)) : "";
    //     $this->doe = $this->doe ? date('d-M-Y',strtotime($this->doe)) : "";
    // }

    public static function mapDates($student){
        $student->dob = $student->dob ? date('d-M-Y',strtotime($student->dob)) : "";
        $student->dos = $student->dos ? date('d-M-Y',strtotime($student->dos)) : "";
        $student->doe = $student->doe ? date('d-M-Y',strtotime($student->doe)) : "";
        $student->sub_end = $student->doe ? date('d-M-Y',strtotime($student->doe)) : "";
        return $student;
    }

    public static function getParameters($id){

    	$parameters = DB::table('parameters')
        ->select('parameters.id','parameters.parameter','student_parameters.parameter_data',
            'parameters.parameter_type','parameters.parameter_values')
        ->where('student_parameters.student_id',$id)
        ->leftJoin('student_parameters', 'student_parameters.parameter_id', '=','parameters.id') 
        ->get();

        if (count($parameters) == 0) {
            $parameter = DB::table('parameters')
            ->select('parameters.id','parameters.parameter','parameters.parameter_type',
                'parameters.parameter_values')
            ->get(); 
        }

        foreach ($parameters as $par) {
            if($par->parameter_type == 'select'){
                $par->parameter_values = explode(",",$par->parameter_values);
            }
        }

        return $parameters;

    }

    public static function getPayments($id){

        $pay_history =  DB::table('payment_history')->select('payment_history.id',
        'payment_history.payment_date','payment_history.amount','payment_history.tax','payment_history.total_amount','payment_history.invoice_date','payment_history.p_mode','payment_history.unique_id')
        ->where('payment_history.student_id',$id)
        ->orderBy("invoice_date","DESC")
        ->get();

        foreach ($pay_history as $pay) {
            // $pay_items = DB::table('payment_items')->select('payment_items.id',
            //     'payment_items.category_id','payment_items.type_id','payment_items.amount',
            //     'payment_items.tax','payment_items.total_amount','payment_items.start_date',
            //     'payment_items.end_date','payment_items.adjustment','payment_items.a_remarks',
            //     'payments_type_categories.category_name','payments_type.name','payments_type.description'
            // )
            // ->leftJoin('payments_type', 'payment_items.type_id', '=',
            //    'payments_type.id')
            // ->leftJoin('payments_type_categories', 'payment_items.category_id', '=',
            //    'payments_type_categories.id')
            // ->where('payment_items.payment_history_id',$pay->id)
            // ->get();
            // foreach ($pay_items as $item) {
            //     if($item->start_date){
            //         $item->start_date = date('d-m-Y' ,strtotime($item->start_date));
            //         $pay->start_date = $item->start_date;
            //     }

            //     if($item->end_date){
            //         $item->end_date = date('d-m-Y' ,strtotime($item->end_date));
            //         $pay->end_date = $item->end_date;
            //     }
                
            // }
            $pay->history_id = $pay->id;
            $pay->code = "PAY".str_pad($pay->id,6,"0",STR_PAD_LEFT);
            $pay->invoice_date = Utilities::convertDate($pay->invoice_date);
            $pay->payment_date = Utilities::convertDate($pay->payment_date);
            // $pay->items = $pay_items;
        }

    	return $pay_history;
        
    }

    public static function getPendingPauses($student_id){

        $pauses = PaymentItem::getPauses(null, $student_id, 0);

        return $pauses;
        
    }

    public static function getSubscriptions($id){

        $pay_items = DB::table('payment_items')->select('payment_items.*')
            ->join('payment_history', 'payment_items.payment_history_id', '=',
               'payment_history.id')
            ->where('payment_items.category_id',2)
            ->where('payment_history.student_id',$id)
            ->orderBy('payment_items.end_date','DESC')
            ->get();

        foreach ($pay_items as $item) {
            $item->code = 'SUB_'.str_pad($item->id, 6, '0', STR_PAD_LEFT);
            $item->start_date = date('d-m-Y' ,strtotime($item->start_date));
            $item->end_date = date('d-m-Y' ,strtotime($item->end_date));
        }

        return $pay_items;
        
    }

    public static function getInjuries($id){

    	$injury = DB::table('injury')->select('students.name','injury.id','injury.student_id',
            'injury.injured_on','injury.remark','injury.last_class')
        ->where('injury.student_id',$id)
        ->leftJoin('students','students.id','=','injury.student_id')->get();
        foreach ($injury as $item) {
            $item->injured_on = date('d-m-Y',strtotime($item->injured_on));
            $item->last_class = date('d-m-Y',strtotime($item->last_class));
        }

        return $injury;
        
    }

    public static function inactiveHistory($id){

    	$inactive = DB::table('inactive')->select('reasons.reason','students.name','inactive.id','inactive.student_id','inactive.last_class','inactive.other_reason','inactive.inactive_from','inactive.reason_id','inactive.other_reason')
        ->where('student_id',$id)
        ->leftJoin('students','students.id','=','inactive.student_id')
        ->leftJoin('reasons','reasons.id','=','inactive.reason_id')
        ->get();

        foreach ($inactive as $item) {
            $item->inactive_from = date('d-m-Y',strtotime($item->inactive_from));
            $item->last_class = date('d-m-Y',strtotime($item->last_class));
        }

        return $inactive;
        
    }

    public static function groupShiftData($id){

    	$groupShiftData = DB::table('group_shifting as gs')->select('gs.id','gs.effective_date','c1.city_name','cn1.center_name','g1.group_name','c2.city_name as old_city_name','cn2.center_name as old_center_name','g2.group_name as old_group_name','users.name as user_name')
        ->leftJoin('groups as g1','g1.id','=','gs.group_id')
        ->leftJoin('center as cn1','cn1.id','=','g1.center_id')
        ->leftJoin('city as c1','c1.id','=','cn1.city_id')
        ->leftJoin('groups as g2','g2.id','=','gs.old_group_id')
        ->leftJoin('center as cn2','cn2.id','=','g2.center_id')
        ->leftJoin('city as c2','c2.id','=','cn2.city_id')
        ->leftJoin('users','users.id','=','gs.added_by')
        ->where('student_id',$id)
        ->orderBy("gs.effective_date","DESC")
        ->get();

        foreach($groupShiftData as $item){
            $item->effective_date = Utilities::convertDate($item->effective_date);
        }

        return $groupShiftData;
        
    }

    public static function documents($id){

    	$document_data = DB::table('documents as doc')->where('student_id',$id)
            ->select('doc.id','doc.document_no','doc.name','doc_type.type','doc.document_url')
            ->leftJoin('documents_type as doc_type', 'doc_type.id', '=', 'doc.type_id')
            ->get();

        return $document_data;
        
    }

    public static function getGuardians($id){

        $guardians = DB::table('student_guardians')->select("name","mobile","email","relation_type")->where('student_id',$id)->get();
        foreach($guardians as $guardian){
            if($guardian->relation_type == 1){
                $guardian->relation = "Father";
            } else if($guardian->relation_type == 2){
                $guardian->relation = "Mother";
            } else {
                $guardian->relation = "Other";
            }
        }

        return $guardians;
        
    }

    public static function reCalculateDates($student_id){

        $pay_items = DB::table('payment_items')->select('payment_items.*')
            ->join('payments_type_categories', 'payments_type_categories.id', '=',
               'payment_items.category_id')
            ->join('payment_history', 'payment_items.payment_history_id', '=',
               'payment_history.id')
            ->where('payments_type_categories.is_sub_type',1)
            ->where('payment_history.student_id',$student_id)
            ->whereNotNull("start_date")->whereNotNull("end_date")
            ->orderBy('payment_items.end_date','DESC')
            ->get();

        foreach ($pay_items as $index => $item) {
            if($index == 0){
                DB::table("students")->where("id",$student_id)->update(array(
                    "doe" => $item->end_date
                ));
            }

            if($index == sizeof($pay_items) - 1){
                DB::table("students")->where("id",$student_id)->update(array(
                    "dos" => $item->start_date
                ));
            }
        }

    }

    public static function reCalculateSubscription($payment_item_id){

        $payment_item = DB::table("payment_items")->find($payment_item_id);
        
        $pauses = DB::table("subscription_pauses")->where("subscription_id",$payment_item_id)->where("status",1)->get();
        $adjustment_days = 0;
        foreach($pauses as $pause){
            $adjustment_days += $pause->days;
        }
        
        $end_date = Utilities::calculateEndDate($payment_item->start_date,$payment_item->months,$adjustment_days);

        DB::table('payment_items')->where("id",$payment_item_id)->update(array(
            "adjustment" => $adjustment_days,
            "end_date" => $end_date
        ));

    }

    public static function getContactDetails($type, $student_id){

        $data = [];

        $student = Student::find($student_id);
        if($student){
            if($type == "email"){
                if($student->email){
                    $data[] = $student->email;
                }
            }

            if($type == "mobile"){
                if($student->mobile){
                    $data[] = $student->mobile;
                }
            }
        }

        $guardians = Student::getGuardians($student_id);
        foreach($guardians as $guardian){
            if($type == "email"){
                if($guardian->email){
                    $data[] = $guardian->email;
                }
            }

            if($type == "mobile"){
                if($guardian->mobile){
                    $data[] = $guardian->mobile;
                }
            }

        }
        return $data;

    }

    public static function sendWelcomeEmail($student_id, $user, $student_emails){

        $student = Student::listing()->where("students.id",$student_id)->first();
        $student = Student::mapDates($student);

        $params = Utilities::getSettingParams([17,15], $student->client_id);

        if(sizeof($student_emails) > 0){
            $mail = new MailQueue;
            $mail->mailto = implode(', ', $student_emails);
            $mail->subject = Utilities::replaceText($params->param_17, $student);
            $mail->content = Utilities::replaceText($params->param_15, $student);
            $mail->tb_name = "students";
            $mail->tb_id = $student_id;
            $mail->student_id = $student_id;
            if($user){
                $mail->user_id = $user->id;
            }
            $mail->client_id = $student->client_id;
            $mail->save();
        }

    }

    public static function getPhoto($pic){
        
        $url = "http://192.168.1.38:8888/academy_erp";

        if($pic){
            $pic = $url."/public/assets/images/".$pic;    
        } else {
            $pic = url('/assets/images/student.png');
            // $pic = url('/assets/images/user-academy.png');
            // $pic = url('/assets/images/user-red.png');
            // $pic = url('/assets/images/user-default.png');

        }
        return $pic;
    }

    public static function createFromRegistration($reg_form_id){

        $registration = DB::table("registrations")->find($reg_form_id);

        $student = new Student;
        $student->name = $registration->name;
        $student->dob = $registration->dob;
        $student->gender = $registration->gender;
        $student->group_id = $registration->group_id;
        $student->address = $registration->address;
        $student->state_id = $registration->address_state_id;
        $student->state_city_id = $registration->address_city_id;

        $student->father = $registration->father;
        $student->mother = $registration->mother;
        // $student->kit_size = $registration->kit_size;
        // $student->pin_code = $registration->pin_code;

        $student->client_id = $registration->client_id;

        $student->save();

        $father_reg = false;
        $mother_reg = false;
        //reg parent 1
        if($registration->prim_relation_to_student != 3){
            if($registration->prim_relation_to_student == 1){
                $name = $student->father;
                $father_reg = true;
            } else {
                $name = $student->mother;
                $mother_reg = true;
            }
            DB::table("student_guardians")->insert(array(
                "student_id" => $student->id,
                "relation_type" => $registration->prim_relation_to_student,
                "name" => $name,
                "email" => $registration->prim_email,
                "mobile" => $registration->prim_mobile,
            ));
        } else {
            $student->mobile = $registration->prim_mobile;
            $student->email = $registration->prim_email;
        }

        //reg parent 2

        if($registration->sec_relation_to_student != 3){
            if($registration->sec_relation_to_student == 1){
                $name = $student->father;
                $father_reg = true;
            } else {
                $name = $student->mother;
                $mother_reg = true;
            }
            DB::table("student_guardians")->insert(array(
                "student_id" => $student->id,
                "relation_type" => $registration->sec_relation_to_student,
                "name" => $name,
                "email" => $registration->sec_email,
                "mobile" => $registration->sec_mobile,
            ));
        } else {
            $student->mobile = $registration->prim_mobile;
            $student->email = $registration->prim_email;
        }

        if(!$father_reg){
            DB::table("student_guardians")->insert(array(
                "student_id" => $student->id,
                "relation_type" => 1,
                "name" => $student->father
            ));
        }

        if(!$mother_reg){
            DB::table("student_guardians")->insert(array(
                "student_id" => $student->id,
                "relation_type" => 2,
                "name" => $student->mother
            ));
        }

        $student->save();
        return $student;

    }

}