<?php
namespace Home\Model;

use Think\Model;

class UserModel extends Model
{
	public function login($data){
		$condition['username'] = $data['username'];
		$result = $this->where($data)->count();
		echo $result;
	}
}