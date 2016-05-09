<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    //
	protected $table = 'OrderItems';
	protected $primaryKey = 'id';
	public $timestamps = false;
}
