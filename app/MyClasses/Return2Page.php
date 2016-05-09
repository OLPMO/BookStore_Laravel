<?php
namespace App\MyClasses;
class Return2Page{
	public $statusId;
	public $statusMsg;
	public $result;
	public function toJson(){
		return json_encode($this,JSON_UNESCAPED_UNICODE);
	}
}
