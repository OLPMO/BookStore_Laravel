<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class TempEmail extends Model
{
    //
	protected $table = 'TempEmail';
	protected $primaryKey = 'id';
	public $timestamps=false;
}
