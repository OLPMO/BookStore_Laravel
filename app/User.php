<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	//$primaryKey='name' 可以设置主键的名字
    protected $fillable = [
        'name', 'email', 'password',
    ];
	
	protected $guarded=['id'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	public function TestFind(){
		return $this->find(1);//根据主键查找
	}
	//以下为where查询的应用
	public function TestWhere(){
		return $this->where('name','amy')->get();
	}
	public function TestWhere1(){
		return $this->where('id','>','1')->get();
	}
	//直接插入数据
	public function TestInsert(){
		$this->name='迷茫';
		$this->email='mimang@126.com';
		$this->password='sdassfa';
		$this->save();
	}
	//用数组的方式插入数据
	public function TestInsertGather(){
		$user=['name'=>'夏花','email'=>'aa@bb.com','password'=>'123w',
					'name'=>'Fsl','email'=>'autn@sing.com','password'=>					'12sdsdsf'
		];
		$this->fill($user);
		$this->save();
	}
	//以下为laravel修改数据库数据的实例
	public function TestUpdate(){
		$upuser=$this->find(1);
		$upuser->name='cherry';
		$upuser->email='fdsdfg@163.com';
		$upuser->password='svfdfs';
		$upuser->save();	
	}
	public function TestUpdate1(){
		$upusers=$this->where('id','>','3');
		$upusers->update(['password'=>'111111111']);
	}
	//删除实例
	public function TestDelete(){
		$deuser=$this->where('name','Cam');
		$deuser->delete();
	}
}
