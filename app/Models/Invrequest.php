<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invrequest extends Model
{
    protected $table = 'inv_request';

	public static function Status(){

		$status = [
			"0" => "Pending",
			"1" => "Approved",
			"2" => "Rejected",
		];

		return $status;
	}
}
