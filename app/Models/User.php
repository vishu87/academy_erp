<?php

namespace App\Models;

use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\MailQueue;

class User extends Authenticatable {

    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    //protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function AuthenticateUser($api_key){
        if(!$api_key || $api_key == NULL){
            die("user not found");
        } else {
            $user = User::where('api_key',$api_key)->first();
            if($user){
                return $user;
            } else {
                die("user not found");
            }
        }
    }

    public static function checkAuth($types){
        $flag = false;

        foreach ($types as $type) {
            if(in_array($type, Session::get("access_rights"))) $flag = true;
        }

        if(!$flag) die("You are not allowed to view this section");
        return $flag;

    }

    public static function hasAuth($type){
        
        if(in_array($type, Session::get("access_rights"))) {
            return true;
        } else {
            return false;
        }

    }

    public static function getMemberName($member_id){
        $member = DB::table('members')->find($member_id);
        if($member){
            return $member->name;
        }else{
            return '';
        }
    }

    public static function validExtentions(){
        // return array("pdf","PDF","jpg","JPG","jpeg","jpeg","png","PNG","docx","DOCX",'doc','DOC');
        return array("pdf","PDF","jpg","JPG","jpeg","jpeg","png","PNG");
    }

    public static function onlyImages(){
        return array("jpg","JPG","jpeg","jpeg","png","PNG");
    }

    public static function fileExtensions(){
        // return array("pdf","PDF","jpg","JPG","jpeg","jpeg","png","PNG","docx","DOCX",'doc','DOC');
        return array("pdf","PDF","jpg","JPG","jpeg","JPEG","png","PNG");
    }


    public static function getSetting($key){

        $notif_off = DB::table("settings")->where("meta_key",$key)->first();
        if($notif_off){
            return $notif_off->meta_value;
        } else {
            return 0;
        }

    }

    public static function getPicture($pic){

        $url = "http://192.168.1.39:8888/academy_erp";
        if($pic){
            $pic = $url."/images/".$pic;
        } else {
            $pic = $url."/images/admin.png";
        }
        return $pic;
    }

    public static function getAccess($tag, $user_id, $entity_id = null, $check_in = "group_ids"){

        Cache::forget($tag."-".$user_id);

        $user_access = Cache::remember($tag."-".$user_id, 60, function() use ($tag, $user_id ){

            $user_access = new UserAccess;

            $user = User::find($user_id);
            
            if($user->role == 1){
                $user_access->all_access = true;
                return $user_access;
            }

            $access_right = DB::table("access_rights")->where("tag",$tag)->first();
            
            if($access_right){
                $list = DB::table("user_location_rights")->where("access_rights_id",$access_right->id)->where("user_id",$user_id)->get();

                foreach ($list as $item) {
                    if($item->city_id == -1){
                        $user_access->all_access = true;
                    } else {

                        if($item->level == 2){
                            $user_access->city_ids[] = $item->city_id;
                            
                            //add centers
                            $center_ids = DB::table("center")->where("city_id",$item->city_id)->pluck("id")->toArray();
                            $user_access->center_ids = array_merge($user_access->center_ids, $center_ids);

                            //add groups
                            $group_ids = DB::table("groups")->join("center","center.id","=","groups.center_id")->where("center.city_id",$item->city_id)->pluck("groups.id")->toArray();
                            $user_access->group_ids = array_merge($user_access->group_ids, $group_ids);
                        }

                        if($item->level == 3){
                            $user_access->city_ids[] = $item->city_id;
                            $user_access->center_ids[] = $item->center_id;
                            //add groups
                            $group_ids = DB::table("groups")->where("groups.center_id",$item->center_id)->pluck("groups.id")->toArray();
                            $user_access->group_ids = array_merge($user_access->group_ids, $group_ids);
                        }

                        if($item->level == 4){
                            $user_access->city_ids[] = $item->city_id;
                            $user_access->center_ids[] = $item->center_id;
                            $user_access->group_ids[] = $item->group_id;
                        }

                    }
                }

                if(sizeof($list) == 0){
                    $user_access->all_access = false;
                    $user_access->city_ids = [];
                    $user_access->center_ids = [];
                    $user_access->group_ids = [];
                }
            } else {
                $user_access->all_access = true;
            }

            return $user_access;

        });

        if(!$entity_id){

            if(isset($user_access->city_ids)){
                if(sizeof($user_access->city_ids) == 0 ) $user_access->city_ids = [0];
            }

            if(isset($user_access->center_ids)){
                if(sizeof($user_access->center_ids) == 0 ) $user_access->center_ids = [0];
            }

            if(isset($user_access->group_ids)){
                if(sizeof($user_access->group_ids) == 0 ) $user_access->group_ids = [0];
            }

            return $user_access;
        } else {
            if($entity_id == -1){
                if($user_access->all_access || sizeof($user_access->{$check_in}) > 0){
                    return true;
                } else {
                    return false;
                }
            } else {
                if( in_array($entity_id, $user_access->{$check_in}) || $user_access->all_access ){
                    return true;
                } else {
                    return false;
                }
            }
        }

    }

    public static function getAccessTabs($user_id){

        $user = User::find($user_id);
        if($user->role == 1){
            $access_rights = DB::table("access_rights")->select("id","type")->orderBy("priority")->get();
        } else {
            $access_right_ids = DB::table("user_location_rights")->distinct("access_rights_id")->where("user_id",$user_id)->pluck("access_rights_id")->toArray();
            if(sizeof($access_right_ids) > 0){
                $access_rights = DB::table("access_rights")->select("id","type")->whereIn("id",$access_right_ids)->orderBy("priority")->get();
            } else {
                $access_rights = [];
            }
        }

        $final_rights = [];
        foreach($access_rights as $access_right){
            if(!isset($final_rights[$access_right->type])){
                $final_rights[$access_right->type] = [];
            }

            $final_rights[$access_right->type][] = $access_right->id;
        }

        return $final_rights;

    }

    public static function getRandPassword(){
        $string1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $string2 = "abcdefghijklmnopqrstuvwxyz";
        $string3 = "0123456789";
        $string4 = "$#@*^%";
        $string5 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789$#@*^%";

        $n = rand(0, strlen($string1) - 1);
        $rand_pwd =  $string1[$n];

        for ($i=0; $i < 2; $i++) { 
            $n = rand(0, strlen($string2) - 1);
            $rand_pwd .=  $string2[$n];
        }

        $n = rand(0, strlen($string3) - 1);
        $rand_pwd .=  $string3[$n];

        $n = rand(0, strlen($string4) - 1);
        $rand_pwd .=  $string4[$n];

        for ($i=0; $i < 3; $i++) { 
            $n = rand(0, strlen($string5) - 1);
            $rand_pwd .=  $string5[$n];
        }

        return $rand_pwd;
    }

    public static function sendWelcomeEmail($user, $password){

        $subject = "Login Details for the academy";
        $content = view("mails",[
            "type" => 'register',
            "user" => $user,
            "password" => $password
        ]);

        MailQueue::createMail($user->email, "", "", $subject, $content);
    }

    public static function parentWelcomeEmail($user, $password){

        $subject = "Login details for the academy";
        $content = view("mails.parent.sign_up",[
            "type" => 'register',
            "user" => $user,
            "password" => $password
        ]);

        MailQueue::createMail($user->email, "", "", $subject, $content);
    }

    
}

class UserAccess {
    public $all_access;
    public $city_ids;
    public $center_ids;
    public $group_ids;

    public function __construct(){
        $this->all_access = false;
        $this->city_ids = [];
        $this->center_ids = [];
        $this->group_ids = [];
    }
}