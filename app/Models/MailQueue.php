<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class MailQueue extends Model
{

    protected $table = 'mail_queue';

    
    public static function createMail($mailto, $mailcc, $mailbcc, $subject, $content){
    	$mail =  new MailQueue;
        
        $mail->mailto = $mailto;
        $mail->mailcc = $mailcc;
        $mail->mailbcc = $mailbcc;
        $mail->subject = $subject;
        $mail->content = $content;
        $mail->save();

        return $mail;
    }

    public static function createSMS($number, $content,$sms_type,$template_id=NULL, $student_id = NULL){
        $mail = new MailQueue;
        $mail->numbers = $number;
        $mail->sms_content = $content;
        $mail->sms_type = $sms_type;
        $mail->solved_sms = 0;
        if($template_id){
            $mail->template_id = $template_id;
        }
        if($student_id){
            $mail->student_id = $student_id;
        }
        $mail->save();
    }
    
}