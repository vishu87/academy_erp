<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\User;

class GeneralController extends Controller
{	
    public function uploadPhoto(Request $request){
        
        include(app_path().'/libraries/resize_img.inc.php');
        
        $name_final = '';
        $destination = 'uploads/';

        if($request->file('photo')){
            $file = $request->file('photo');

            $resize = $request->resize ? $request->resize : 0;
            $crop = $request->crop ? $request->crop : 0;
            $width = $request->width ? $request->width : 0;
            $height = $request->height ? $request->height : 0;

            $name = $file->getClientOriginalName();
            $name = preg_replace('/[^A-Za-z0-9_\.\-]/', '', $name);

            $name_final = strtotime("now")."_".$name.".png";
            $file->move($destination, $name_final);

            if($resize == 1){
                $resizer = new SimpleImage();
                $resizer->load($destination.$name_final);
                
                if($crop == 1){
                    $resizer->cropImage($width,$height,true);
                } else {
                    if($width){
                        $resizer->resizeToWidth($width);
                    } else {
                        $resizer->resizeToHeight($height);
                    }
                }
                $resizer->save($destination.'tn_'.$name_final);

                unlink($destination.$name_final);
                $name_final = 'tn_'.$name_final;
                
            }

            $data["url"] = url($destination.$name_final);
            $data["path"] = $destination.$name_final;
        }

        $data["success"] = true;
        return Response::json($data, 200, array());
    }

    public function uploadFile(Request $request){
        
        $destination = 'uploads/';

        if($request->file('file')){
            $file = $request->file('file');
            $name = $file->getClientOriginalName();
            $name = preg_replace('/[^A-Za-z0-9_\.\-]/', '', $name);
            $name_final = strtotime("now")."_".$name;
            $file->move($destination, $name_final);
            $data["url"] = url($destination.$name_final);
            $data["path"] = $destination.$name_final;
        }

        $data["success"] = true;
        return Response::json($data, 200, array());
    }

    public function getCityListData(){
        $list = DB::table('city')->select('id','city_name')->get();
        $data['success'] = true;
        $data['data'] = $list;
        return Response::json($data, 200, array());
    }

    public function getStates(){
        $states = DB::table('states')->select('id as value','state_name as label')->get();
        $data['success'] = true;
        $data['states'] = $states;
        return Response::json($data, 200, array());
    }

    public function getCities($state_id){
        $list = DB::table('cities')->select('id as value','city_name as label')->where("state_id",$state_id)->orderBy("city_name","ASC")->get();
        $data['success'] = true;
        $data['cities'] = $list;
        return Response::json($data, 200, array());
    }

    public function get_state_city_center_data(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));
        $tag = $request->Tag;

        $user_access = User::getAccess($tag,$user->id);

        if ($user_access->all_access) {
             
            $group = DB::table('groups')->select('id as value','group_name as label','center_id')
            ->get();

            $center = DB::table('center')->select('id as value','center_name as label','city_id')
            ->get();

            $city = DB::table('city')->select('id as value','city_name as label','state_id')
            ->get(); 

        } else {
            $city = DB::table('city')->select('id as value','city_name as label','state_id')
            ->whereIn('id',$user_access->city_ids)->get(); 

            $center = DB::table('center')->select('id as value','center_name as label','city_id')
            ->whereIn('id',$user_access->center_ids)->get();

            $group = DB::table('groups')->select('id as value','group_name as label','center_id')
            ->whereIn('id',$user_access->group_ids)->get();
        }

        $states = DB::table('states')->select('id as value','state_name as label')->get();
        // $state = [];
        // foreach ($states as $st) {
        //     $check = 0;
        //     foreach ($city as $ct) {
        //         if ($st->id == $ct->state_id && $check == 0) {
        //             $state[] = $st;
        //             $check = 1;
        //         }
        //     }
        // }

        $data['success'] = true;
        $data['state'] = $states;
        $data['city'] = $city;
        $data['center'] = $center;
        $data['group'] = $group;
        $data['all_access'] = $user_access->all_access;

        return Response::json($data, 200, []);
    }
}
