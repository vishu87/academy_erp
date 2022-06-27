<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Utilities, App\Models\MailQueue;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        include(app_path().'/libraries/mailer/PHPMailerAutoload.php');

        $mail_queue = MailQueue::where("type","mail")->where("solved",0)->orderBy("priority","DESC")->limit(3)->get();
        $mailer_objects = [];

        foreach ($mail_queue as $mail_item) {

            if(!isset($mailer_objects[$mail_item->client_id])){

                $params = Utilities::getSettingParams([30,31,32,33,34,35],$mail_item->client_id);

                $mail = new \PHPMailer;
                $mail->IsSMTP();
                $mail->SMTPAuth   = true;
                $mail->SMTPSecure = "tls";
                
                if($params->param_35){
                    $mail->Port = $params->param_35;
                }
                if($params->param_30){
                    $mail->Host = $params->param_30;
                }
                if($params->param_31){
                    $mail->Username = $params->param_31;
                }
                if($params->param_32){
                    $mail->Password = $params->param_32;
                }

                $mail->SetFrom($params->param_33, $params->param_34);
                
                $mail->IsHTML(true);

                $mailer_objects[$mail_item->client_id] = $mail;

            } else {
                $mail = $mailer_objects[$mail_item->client_id];
            }

            $mail->Subject = $mail_item->subject;
            $mail->Body = $mail_item->content;

            if(env('APP_ENV') == "production"){

                if($mail_item->mailto){
                    $emails = explode(',', $mail_item->mailto);
                    foreach ($emails as $email) {
                        $email = trim($email);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
                            $mail->AddAddress($email);
                        }
                    }
                }

                if($mail_item->mailcc){
                    $emails = explode(',', $mail_item->mailcc);
                    foreach ($emails as $email) {
                        $email = trim($email);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
                            $mail->AddCC($email);
                        }
                    }
                }

                if($mail_item->mailbcc){
                    $emails = explode(',', $mail_item->mailbcc);
                    foreach ($emails as $email) {
                        $email = trim($email);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
                            $mail->AddBCC($email);
                        }
                    }
                }
            } else {
                $mail->AddAddress("vishu.iitd@gmail.com");
            }

            if($mail_item->at_file){
                $mail->addAttachment(public_path().'/'.$mail_item->at_file);
            }

            if(!$mail->Send()) {
                $mail_item->solved = -1;
                $mail_item->remarks = "Mailer Error: " . $mail->ErrorInfo;
                $mail_item->save();
            } else {
                $mail_item->solved = 1;
                $mail_item->save();
            }

            $mail->ClearAllRecipients();
            $mail->ClearAttachments();
            $mail->ClearCustomHeaders();
            sleep(1);
        }

        $this->info("emails sent - ".sizeof($mail_queue));
    }
}
