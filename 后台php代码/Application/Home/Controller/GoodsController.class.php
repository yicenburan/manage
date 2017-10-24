<?php
namespace Home\Controller;

use Think\Controller;

class GoodsController extends Controller
{
	public function showGoods(){
		$model=D("Goods");
		$data=$model->select();
		$this->ajaxReturn($data);
	}

	public function selectGoods(){
		$model=D("Goods");
		$map['id']=$_POST['id'];
		$data=$model->where($map)->select();
		$this->ajaxReturn($data);
	}

	public function postGoods(){
		$postData = $_POST;
		$add=true;
		$this->upload($postData,$add);  
	}

	public function editGoods(){
		$model=D("Goods");
		if(empty($_FILES)){
			$save['title']=$_POST['title'];
			$save['price']=$_POST['price'];
			$result=$model->where('id='.$_POST['id'])->save($save);
			if($result >= 0){
				$data=array(
					code=>'0',
					msg=>'修改成功'
				);
				$this->ajaxReturn($data);
			}else{
				$data=array(
					code=>'102',
					msg=>'修改失败'
				);
				$this->ajaxReturn($data);
			}
		}else{
			$add=false;
			$this->upload($_POST,$add);  
		}
	}


	public function upload($postData,$isAdd){
	    $upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    // 上传文件 
	    $info   =   $upload->upload();

	    if(!$info) {// 上传错误提示错误信息
	        $this->error($upload->getError());
	    }else{// 上传成功
	    	$add['title']=$postData['title'];
	    	$add['price']=$postData['price'];
	    	$add['img']=$info['picture']['savepath'].$info['picture']['savename'];

	    	if($isAdd){
	    		$this->addGoods($add);
	    	}else{
	    		$this->edit($add,$postData['id']);
	    	}
	    }
	}

	public function addGoods($addGoods){
		$model = D('Goods');
		$result=$model->add($addGoods);
		if($result >= 0){
			$data=array(
				code=>'0',
				msg=>'添加成功',
				data=>$addGoods
			);
			$this->ajaxReturn($data);
		}else{
			$data=array(
				code=>'102',
				msg=>'添加失败'
			);
			$this->ajaxReturn($data);
		}
	}

	public function edit($save,$id){
		$model = D('Goods');
		$result=$model->where('id='.$id)->save($save);
		if($result >= 0){
			$data=array(
				code=>'0',
				msg=>'修改成功'
				);
			$this->ajaxReturn($data);
		}else{
			$data=array(
				code=>'102',
				msg=>'修改失败'
			);
			$this->ajaxReturn($data);
		}
	}

	public function delete(){
		$model=D("Goods");
		$img=$model->where('id='.$_POST['id'])->select();
		$result=$model->where('id='.$_POST['id'])->delete();
		unlink('Uploads/'.$img[0]['img']); 
		if($result >= 0){
			$data=array(
				code=>'0',
				msg=>'删除成功'
				);
			$this->ajaxReturn($data);
		}else{
			$data=array(
				code=>'102',
				msg=>'删除失败'
			);
			$this->ajaxReturn($data);
		}
	}
}