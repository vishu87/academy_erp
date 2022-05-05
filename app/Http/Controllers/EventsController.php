<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, Request, App\User, App\ParentAppEvent;

class EventsController extends Controller{ 

    public function IndexPage(){
       return view('manage.events.index');
    }

    public function addPage($id){
        return view('manage.events.add_events',['id'=>$id]);
    }

    public function getNameFromNumber($num) {
        $numeric = ($num ) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num ) / 26);
        if ($num2 > 0) {
            return getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }

    public function getList(){
        $user = User::AuthenticateUser(Request::header("apiToken"));

        $events = ParentAppEvent::select('parent_app_events.*','city.city_name')->leftJoin('city','city.id','=','parent_app_events.location_id');

        if(Input::has('name')){
            $events = $events->where('name','LIKE','%'.Input::get('name').'%');
        }

        if(Input::has('start_date') && Input::has('end_date')){
            $start_date = date("Y-m-d",strtotime(Input::get('start_date')));
            $end_date = date("Y-m-d",strtotime(Input::get('end_date')));
            
            $events = $events->whereBetween('start_date',[$start_date,$end_date]);
        }

        $events = $events->get();
        foreach ($events as $event) {
            if($event->start_date){
                $event->start_date = date("d-m-Y",strtotime($event->start_date));
            }
            if($event->end_date){
                $event->end_date = date("d-m-Y",strtotime($event->end_date));
            }

            if($event->min_dob){
                $event->min_dob = date("d-m-Y",strtotime($event->min_dob));
            }

            if($event->max_dob){
                $event->max_dob = date("d-m-Y",strtotime($event->max_dob));
            }
        }
        $data['events'] = $events;
        return Response::json($data,200,[]);
    }

    public function init(){
        $user = User::AuthenticateUser(Request::header("apiToken"));
        $event = ParentAppEvent::find(Input::get('id'));
        if($event){
            if($event->allowed_genders){
                $allowed_genders = explode(',',$event->allowed_genders);
                $g_arr = [];
                foreach ($allowed_genders as $gender) {
                    $g_arr[$gender] = true; 
                }
                $event->allowed_genders = $g_arr;
            } else {
                $event->allowed_genders = [];
            }
            if($event->allowed_offline_payments){
                $allowed_offline_payments = explode(',',$event->allowed_offline_payments);
                $p_arr = [];
                foreach ($allowed_offline_payments as $payment) {
                    $p_arr[$payment] = true;    
                }
                $event->allowed_offline_payments = $p_arr;
            } else {
                $event->allowed_offline_payments = [];
            }

            if($event->payment_category){
                $payment_category = explode(',',$event->payment_category);
                $pc_arr = [];
                foreach ($payment_category as $payment) {
                    $pc_arr[$payment] = true;   
                }
                $event->payment_category = $pc_arr;
            } else {
                $event->payment_category = [];
            }

            $event->gallery = DB::table('parent_app_event_gallery')->where('event_id',$event->id)->get();
            
            $data['formData'] = $event;
        }
        $data['cities'] = DB::table('city')->select('id','city_name')->orderBy('city_name')->get();
        return Response::json($data,200,[]);
    }

    public function add(){
        $cre = [
            "name"=>Input::get("name"),
            "start_date"=>Input::get("start_date"),
            "end_date"=>Input::get("end_date"),
            "latitude"=>Input::get("latitude"),
            "longitude"=>Input::get("longitude"),
            "location_id"=>Input::get("location_id"),
            "position"=>Input::get("position"),
            "description"=>Input::get("description"),
            "address"=>Input::get("address"),
            "min_dob"=>Input::get("min_dob"),
            "max_dob"=>Input::get("max_dob"),
        ];

        $rules = [
            "name"=>"required",
            "start_date"=>"required|date",
            "end_date"=>"required|date|after:start_date",
            "latitude"=>"required",
            "longitude"=>"required",
            "location_id"=>"required",
            "position"=>"required|numeric",
            "description"=>"required",
            "address"=>"required",
        ];

        if(Input::has('min_dob') && Input::has('max_dob')){
            $rules['min_dob'] = "date";
            $rules['max_dob'] = "date";
        }

        $messages = ["location_id.required"=>"Location is required"];
        $validation = Validator::make($cre,$rules,$messages);
        if($validation->passes()){
            $allowed_offline_payments = '';
            if(Input::has('allowed_offline_payments')){
                $payment_methods = Input::get('allowed_offline_payments');
                $p_arr = [];
                foreach ($payment_methods as $key => $value) {
                    if($value){
                        array_push($p_arr,$key);
                    }
                }
                if(sizeof($p_arr) > 0){
                    $allowed_offline_payments = implode(',',$p_arr);
                }
            }

            $allowed_genders = '';
            if(Input::has('allowed_genders')){
                $g_types = Input::get('allowed_genders');
                $g_arr = [];
                foreach ($g_types as $key => $value) {
                    if($value){
                        array_push($g_arr,$key);
                    }
                }
                if(sizeof($g_arr) > 0){
                    $allowed_genders = implode(',',$g_arr);
                }
            } else {
                $allowed_genders = "";
            }

            $payment_category = '';
            if(Input::has('payment_category')){
                $p_cat = Input::get('payment_category');
                $p_arr = [];
                foreach ($p_cat as $key => $value) {
                    if($value){
                        array_push($p_arr,$key);
                    }
                }
                if(sizeof($p_arr) > 0){
                    $payment_category = implode(',',$p_arr);
                }else{
                    $p_arr = [0];
                }
            } else {
                $p_arr = [0];
            }


            $event = ParentAppEvent::find(Input::get('id'));
            $data['message'] = "Event details are updated successfully";
            if(!$event){
                $code =  bin2hex(openssl_random_pseudo_bytes(11));
                $event = new ParentAppEvent;
                $event->code = $code;
                $data['message'] = "New Event is created successfully";
            }

            $event->name = Input::get('name');
            $event->event_type = Input::get('event_type');
            $event->start_date = date("Y-m-d",strtotime(Input::get('start_date')));
            $event->end_date = date("Y-m-d",strtotime(Input::get('end_date')));
            $event->latitude = Input::get('latitude');
            $event->longitude = Input::get('longitude');
            $event->location_id = Input::get('location_id');
            $event->position = Input::get('position');
            $event->description = Input::get('description');
            $event->address = Input::get('address');
            $event->additional_remarks = Input::get('additional_remarks');
            $event->hidden = (Input::has('hidden'))?Input::get('hidden'):0;
            $event->registration_closed = (Input::has('registration_closed'))?Input::get('registration_closed'):0;
            $event->pay_later = (Input::has('pay_later'))?Input::get('pay_later'):0;

            $event->kit_size = (Input::has('kit_size'))?Input::get('kit_size'):0;
            $event->school_name = (Input::has('school_name'))?Input::get('school_name'):0;

            $event->web_banner = (Input::has('web_banner'))?Input::get('web_banner'):0;

            $event->coupon_code = (Input::has('coupon_code'))?Input::get('coupon_code'):"";
            $event->coupon_perc = (Input::has('coupon_perc'))?Input::get('coupon_perc'):"";

            $event->min_dob = (Input::has('min_dob'))?date("Y-m-d",strtotime(Input::get('min_dob'))):NULL;
            $event->max_dob = (Input::has('max_dob'))?date("Y-m-d",strtotime(Input::get('max_dob'))):NULL;

            $event->allowed_genders = $allowed_genders;
            $event->allowed_offline_payments = $allowed_offline_payments;
            $event->payment_category = $payment_category;

            $event->meta_title = Input::get('meta_title');
            $event->meta_description = Input::get('meta_description');
            $event->meta_keywords = Input::get('meta_keywords');
            $event->slug = Input::get('slug');
            $event->video_link = Input::get('video_link');


            $find_str = url('/admin-api');
            
            if(Input::has('image')){
                $event->image = Input::get('image');
            } else {
                $event->image = "";
            }

            if(Input::has('web_image')){
                $event->web_image = Input::get('web_image');
            } else {
                $event->web_image = "";
            }

            if(Input::has('logo')){
                $event->logo = Input::get('logo');;
            } else {
                $event->logo = "";
            }

            if(isset($p_arr)){
                if(in_array(1,$p_arr)){
                    $event->amount_bbfs = Input::get('amount_bbfs');
                    $event->tax_bbfs = Input::get('tax_bbfs');
                }else{
                    $event->amount_bbfs = 0;
                    $event->tax_bbfs = 0;
                }

                if(in_array(2,$p_arr)){
                    $event->amount_inactive = Input::get('amount_inactive');
                    $event->tax_inactive = Input::get('tax_inactive');
                }else{
                    $event->amount_inactive = 0;
                    $event->tax_inactive = 0;
                }


                if(in_array(3,$p_arr)){
                    $event->amount_pbbfs = Input::get('amount_pbbfs');
                    $event->tax_pbbfs = Input::get('tax_pbbfs');
                }else{
                    $event->amount_pbbfs = 0;
                    $event->tax_pbbfs = 0;
                }

                if(in_array(4,$p_arr)){
                    $event->amount = Input::get('amount');
                    $event->tax = Input::get('tax');
                }else{
                    $event->amount = 0;
                    $event->tax = 0;
                }
            }

            $event->save();

            DB::table('parent_app_event_gallery')->where('event_id',$event->id)->delete();
            $gallery = Input::get('gallery');
            if(sizeof($gallery) > 0){
                foreach ($gallery as $gallery) {
                    DB::table('parent_app_event_gallery')->insert([
                        "event_id"=>$event->id,
                        "media"=>(isset($gallery['media']))?$gallery['media']:NULL,
                        "media_thumb"=>(isset($gallery['media_thumb']))?$gallery['media_thumb']:NULL,
                    ]);
                }
            }

            $data['success'] = true;

        }else{
            $data['success'] = false;
            $data['message'] = $validation->errors()->first();
        }
        return Response::json($data,200,[]);
    }

    public function uploadFile(){
        $destination = 'uploads/';
        include(app_path().'/libraries/resize_img.inc.php');
        if(Input::hasFile('media')){
            $file = Input::file('media');
            $extension = Input::file('media')->getClientOriginalExtension();

            if(in_array($extension, User::fileExtensions())){
                $name = 'EventImage_'.rand(0,1000).strtotime("now").'.'.strtolower($extension);
                $file = $file->move($destination, $name);

                if(Input::get("name") == "image"){
                    $resizer=new SimpleImage();
                    $resizer->load(base_path().'/public/'.$destination.$name);
                    $resizer->cropImage(375,200,true);
                    $resizer->save(base_path().'/public/'.$destination.'tn_'.$name);

                    $data["media"] = url($destination.'tn_'.$name);
                    unlink($destination.$name);

                } else if(Input::get("name") == "web_image"){
                    
                    $resizer=new SimpleImage();
                    $resizer->load(base_path().'/public/'.$destination.$name);
                    $resizer->cropImage(1215, 391, true);
                    $resizer->save(base_path().'/public/'.$destination.'tn_'.$name);

                    $data["media"] = url($destination.'tn_'.$name);
                    unlink($destination.$name);
                } else {
                }

                $data["success"] = true;
            }else{
                $data['success'] = false;
                $data['message'] = 'Invalid file format';
            }
        }else{
            $data['success'] = false;
            $data['message'] ='file not found';
        }

        return Response::json($data, 200, array());
    }

    public function uploadGalaryImage(){
        $destination = 'uploads/';
        include(app_path().'/libraries/resize_img.inc.php');
        if(Input::hasFile('media')){
            $file = Input::file('media');
            $extension = Input::file('media')->getClientOriginalExtension();
            if(in_array($extension, User::fileExtensions())){
                $name = 'EventImage_'.rand(0,1000).strtotime("now").'.'.strtolower($extension);
                $file = $file->move($destination, $name);


                $resizer=new SimpleImage();
                $resizer->load(base_path().'/public/'.$destination.$name);
                $resizer->resizeToWidth(800);
                $resizer->save(base_path().'/public/'.$destination.'Big'.$name);
                $data["media"] = url($destination.'Big'.$name);

                $resizer=new SimpleImage();
                $resizer->load(base_path().'/public/'.$destination.$name);
                $resizer->cropImage(256,408,true);
                $resizer->save(base_path().'/public/'.$destination.'tn_'.$name);
                $data["media_thumb"] = url($destination.'tn_'.$name);
               
                unlink($destination.$name);

                $data["success"] = true;
            }else{
                $data['success'] = false;
                $data['message'] = 'Invalid file format';
            }
        }else{
            $data['success'] = false;
            $data['message'] ='file not found';
        }

        return Response::json($data, 200, array());
    }

    public function getEvents($event_type){
        header('Access-Control-Allow-Origin: *');
        if($event_type > 0){

            $upcoming_events = DB::table('parent_app_events')->select('id','logo','name','web_image as image','description','code','slug','meta_title','meta_description','meta_keywords','web_banner','video_link')->where('event_type',$event_type)->where('start_date','>=',date("Y-m-d"))->get();
            foreach ($upcoming_events as $event) {
                $event->slug = $event->code;
                $event->link = "https://bbfootballschools.com/tournament?type=".$event->code;
                $event->images = DB::table('parent_app_event_gallery')->select('media','media_thumb')->where('event_id',$event->id)->get();
            }

            $past_events = DB::table('parent_app_events')->select('id','logo','name','web_image as image','description','code','slug','meta_title','meta_description','meta_keywords','web_banner','video_link')->where('event_type',$event_type)->where('start_date','<',date("Y-m-d"))->get();
            foreach ($past_events as $event) {
                $event->images = DB::table('parent_app_event_gallery')->select('media','media_thumb')->where('event_id',$event->id)->get();
            }
            $data['success'] = true;
            $data['upcoming_events'] = $upcoming_events;
            $data['past_events'] = $past_events;

        }else{
            $data['success'] = false;
            $data['message'] = "Not event found for this type";
        }
        return Response::json($data,200,[]);
    }

    public function cancelReasons(){
        $reasons = DB::table("cancellation_reasons")->get();
        $data['success'] = true;
        $data['reasons'] = $reasons;
        return Response::json($data,200,[]);
    }

    public function addReason(){
        if(Input::has("id")){
            DB::table("cancellation_reasons")->where("id",Input::get("id"))->update(array(
                "name" => Input::get("name")
            ));
        } else {
            DB::table("cancellation_reasons")->insert(array(
                "name" => Input::get("name")
            ));
        }
        $data['success'] = true;
        $data['message'] = "Reason successfully inserted/updated";
        return Response::json($data,200,[]);
    }

    public function deleteReason(){
        DB::table("cancellation_reasons")->where("id",Input::get("id"))->delete();
        $data['success'] = true;
        $data['message'] = "Reason is deleted";
        return Response::json($data,200,[]);
    }
    
}


                 