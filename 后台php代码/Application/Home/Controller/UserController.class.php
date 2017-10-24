<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller
{
	public function index()
	{
		$map=$_GET;
		$model=D("User");
		$data=$model->where($map)->select();
		$this->ajaxReturn($data);
	}

	public function login()
	{
		$model=D("User");
		$info=$_POST;
		$condition['username'] = $_POST['username'];
		$query['username'] = $_POST['username'];
		$query['password'] = $_POST['password'];

		$uid = $model->where($condition)->select()[0]['id'];
		$uname = $model->where($condition)->select()[0]['username'];

		$result = $model->where($condition)->count();
		if($result == 0){
			$data=array(
				code => '102',
				msg => '未找到该用户'
			);
			$this->ajaxReturn($data);
		}else{
			$result1 = $model->where($query)->count();

			if($result1>0){
				$data=array(
					code=>'0',
					msg=>'登录成功',
					uid=>$uid,
					uname=>$uname
				);
			}else{
				$data=array(
					code=>'102',
					msg=>'密码错误'
				);
			}
			$this->ajaxReturn($data);
		}
	}

	public function register(){
		$model=D("User");
		$result=$model->add($_POST);
		if($result > 0){
			$data=array(
				code=>'0',
				msg=>'注册成功'
			);
		}else{
			$data=array(
				code=>'102',
				msg=>'注册失败'
			);
		}
		$this->ajaxReturn($data);
	}
}