<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\PaymentHistory, App\Models\PaymentItem, App\Models\User, App\Models\Student, App\Models\Utilities;

class ParentController extends Controller{ 

    public function dashboard(){
        return view('parents.index',["sidebar" => "","menu" => "parents"]);
    }

    public function initStudent(Request $request){
        
        $user = User::AuthenticateParent($request->header("apiToken"));

        $id = $request->student_id;

        $check_access = User::getAccessParent($user->id, $id);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $student = Student::listing()->where('students.id', '=',$id)->first();

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

        $student->student_tags = [];

        $tags = [];

        $data = [
            "success" => true,
            "student" => $student,
            "tags" => $tags,
        ];

        return Response::json($data, 200, array());
    }
}