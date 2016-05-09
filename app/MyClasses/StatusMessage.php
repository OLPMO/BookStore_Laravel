<?php
namespace App\MyClasses;
class StatusMessage{
	public $statusId;
	public $statusMsg;
	public function toJson(){
		return json_encode($this,JSON_UNESCAPED_UNICODE);
	}
}
