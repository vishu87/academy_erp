<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB, App\Lead;
class BBFSEvent extends Model
{

    protected $table = 'bbfs_events';

    public static function listing(){
        return BBFSEvent::select("bbfs_events.*","g1.group_name as group1_name","g2.group_name as group2_name","c1.center_name as center1_name","c2.center_name as center2_name","mScorer.name as scorer1_name","mScorer2.name as scorer2_name","co1.name as coordinator","concept_levels.level_name","city.city_name")
            ->leftJoin("groups as g1","g1.id","=","bbfs_events.group1_id")
            ->leftJoin("groups as g2","g2.id","=","bbfs_events.group2_id")
            ->leftJoin("center as c1","c1.id","=","bbfs_events.center1_id")
            ->leftJoin("center as c2","c2.id","=","bbfs_events.center2_id")
            ->leftJoin("members as mScorer","mScorer.id","=","bbfs_events.scorer1_id")
            ->leftJoin("members as mScorer2","mScorer2.id","=","bbfs_events.scorer2_id")
            ->leftJoin("city","city.id","=","bbfs_events.city_id")
            // ->leftJoin("members as t1","t1.id","=","bbfs_events.team1_coach_id")
            // ->leftJoin("members as t2","t2.id","=","bbfs_events.team2_coach_id")
            ->leftJoin("members as co1","co1.id","=","bbfs_events.coordinator_id")
            ->leftJoin("concept_levels","concept_levels.id","=","bbfs_events.level_id");
    }

    public function getMoreDetails($user_id){
        $this->date_show = date("d",strtotime($this->start_date));
        $this->month_show = date("M",strtotime($this->start_date));
        $this->event_type = $this->getEventType();
        $this->start_time = date("h:i A",strtotime($this->start_time));
        $this->end_time = date("h:i A",strtotime($this->end_time));

        if($this->approver_id == $user_id){
            $this->approver = true;
        }

        $event_status = BBFSEvent::status();

        $this->status_name = (isset($event_status[$this->status]))?$event_status[$this->status]:'';
    }

    public static function status(){
        return [
          0=>"Pending",
          1=>"Approved",
          2=>"Cancelled",
          5=>"Completed"
        ];
    }

    public static function types($type){
    	$types =  array(
    		1 => "Training Session",
    		2 => "Match",
    		3 => "Workshop",
            4 => "Meeting",
            5 => "Online Class",
            10 => "Other"
     	);

    	if($type == "array") return $types;

    	$json_array = [];
    	foreach ($types as $key => $value) {
    		$json_array[] = [ "label" => $value, "value" => $key ];
    	}
    	return $json_array;
    }

    public static function typesCoach($type){
        $types =  array(
            2 => "Match",
            5 => "Online Class"
        );

        if($type == "array") return $types;

        $json_array = [];
        foreach ($types as $key => $value) {
            $json_array[] = [ "label" => $value, "value" => $key ];
        }
        return $json_array;
    }

    public function getEventType(){
        switch ($this->type) {
            case 1:
                return "Training Session";
                break;

            case 2:
                return "Match";
                break;

            case 3:
                return "Workshop";
                break;

            case 4:
                return "Meeting";
                break;

            case 5:
                return "Online Class";
                break;
    
            case 10:
                return "Other";
                break;
            
            default:
                return "";
                break;
        }
    }

    public function removeStudents(){
        DB::table("bbfs_event_students")->where("bbfs_event_id",$this->id)->delete();
    }

    public function removeMembers(){
        DB::table("bbfs_event_members")->where("bbfs_event_id",$this->id)->delete();
    }

    public static function removeMemberFromEvents($operation_day_id,$coach_id,$start_date, $end_date){

        if(!$end_date){
            DB::table("bbfs_event_members")->join("bbfs_events","bbfs_events.id","=","bbfs_event_members.bbfs_event_id")->where("bbfs_events.operation_day_id",$operation_day_id)->where("bbfs_events.start_date",">=",$start_date)->where("bbfs_event_members.user_id",$coach_id)->delete();
        } else {
            DB::table("bbfs_event_members")->join("bbfs_events","bbfs_events.id","=","bbfs_event_members.bbfs_event_id")->where("bbfs_events.operation_day_id",$operation_day_id)->whereBetween("bbfs_events.start_date",[$start_date,$end_date])->where("bbfs_event_members.user_id",$coach_id)->delete();
        }

    }

    public static function addMemberInEvents($operation_day_id,$coach_id,$start_date, $end_date){

        if(!$end_date){
            $events = BBFSEvent::select("id","group1_id")->where("operation_day_id",$operation_day_id)->where("start_date",">=",$start_date)->get();
        } else {
            $events = BBFSEvent::select("id","group1_id")->where("operation_day_id",$operation_day_id)->whereBetween("start_date",[$start_date,$end_date])->get();
        }
        
        foreach ($events as $event) {
            DB::table("bbfs_event_members")->insert(array(
                "bbfs_event_id" => $event->id,
                "user_id" => $coach_id,
                "group_id" => $event->group1_id
            ));
        }

    }

    public function sendSMSForCancellation($demo, $demo_number){

        return [];

        $event_type = BBFSEvent::getEventType($this->type);

        $bbfs_students = DB::table("bbfs_event_students")->select("students.id","students.mobile","students.father_mob","students.mother_mob")->join("students","students.id","=","bbfs_event_students.student_id")->where("bbfs_event_students.bbfs_event_id",$this->id)->get();
        $messages = [];
        $count = 0;
        foreach ($bbfs_students as $bbfs_student) {

            if($this->type == 2){
                $message = $event_type." at ";
                $message .= $this->location;

                $message .= " b/w ";

                if($this->group1_name){
                    $message .= $this->group1_name;
                }

                $message .= " and ";

                if($this->group2_name){
                    $message .= $this->group2_name;
                } else {
                    $message .= $this->team2_name;
                }

                $message .= " on ".date("d-m-Y",strtotime($this->start_date));
                $message .= " will not take place due to ".$this->cancel_reason;
            } else {
                $message = $event_type." at ";
                if($this->center1_name){
                    $message .= $this->center1_name;
                } else {
                    $message .= $this->location;
                }
                $message .= " for ";
                if($this->group1_name){
                    $message .= $this->group1_name;
                }
                $message .= " on ".date("d-m-Y",strtotime($this->start_date));
                $message .= " will not take place due to ".$this->cancel_reason;
            }

            if($demo){
                if($count == 0){
                    Lead::sendSMS($demo_number, $message,1);
                    $messages[] = $message;    
                }
            } else {
                if($bbfs_student->mobile){
                    Lead::storeSMS($bbfs_student->mobile, $message,1);
                    $messages[] = $message;
                }

                if($bbfs_student->father_mob){
                    Lead::storeSMS($bbfs_student->father_mob, $message,1);
                    $messages[] = $message;
                }
                
                if($bbfs_student->mother_mob){
                    Lead::storeSMS($bbfs_student->mother_mob, $message,1);
                    $messages[] = $message;
                }

            }

            $count++;

        }

        return $messages;

    }

}

