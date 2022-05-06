<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $table = 'clients';

    public static function AuthenticateClient($code){
        if(!$code || $code == NULL){
            die("client not found");
        } else {
            $client = Client::where('code',$code)->first();
            if($client){
                return $client;
            } else {
                die("client not found");
            }
        }
    }
}