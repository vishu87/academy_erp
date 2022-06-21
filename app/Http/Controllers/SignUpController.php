<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB;
use Illuminate\Http\Request;
use App\Models\Utilities;

class SignUpController extends Controller
{	

    public function searchStudent(Request $request){

        $client_code = $request->header("clientId");
        $client = Client::AuthenticateClient($client_code);
        $client_id = $client->id;

        $email = $request->email;

        $user = User::where("username",$email)->first();
        if($user){
            $data["success"] = false;
            $data["message"] = "This email is already registered with us. Kindly try forget password.";
        } else {
            
            $found = false;
            $student_ids = [];
            $student = DB::table("students")->where("email",$email)->where("client_id",$client_id)->first();
            if($student){
                $found = true;
                $student_ids[] = $student->id;
            }

            $guardian = DB::table("student_guardians")->where("email",$email)->where("client_id",$client_id)->get();
            foreach($guardians as $guardian){
                $found = true;
                if(!in_array($guardian->student_id, $student_id)){
                    $student_ids[] = $guardian->student_id;
                }
            }

            if($found){
                $data["success"] = true;
                
                $user = new User;
                $user->email = $email;
                $user->user_type = 2;
                $password = User::getRandPassword();
                $user->password = Hash::make($password);
                $user->password_check = $password;
                
                $user->api_key = Hash::make($user->username);
                $user->client_id = $client_id;
                $user->save();

                User::sendWelcomeEmail($user, $password);

                foreach($student_ids as $student_id){
                    DB::table("user_students")->insert(array(
                        "student_id" => $student_id,
                        "user_id" => $user->id
                    ));
                }

                $data["message"] = "Your account is successfully linked to your kid's profile. We have sent your login details on your email. Please contact us in case of any problem";

            } else {
                $data["success"] = true;
                $data["message"] = "Sorry, we could not found any student linked with any student";
            }
        }

        return Response::json($data,200,array());
    }


}
