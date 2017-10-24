<?php
namespace Home\Controller;

use Think\Controller;

class CartController extends Controller
{
	public function showCart(){
		$model=D('Cart');
		$Goods=D('Goods');
		$uid=$_POST['uid'];
		$gids=$model->where('uid='.$uid)->field('id,gid,count')->select();

		$data=array();
		foreach($gids as $g){
			$result=$Goods->where('id='.$g['gid'])->field('title,price,img')->select();
			$goods=array(
				id=>$g['id'],
				gid=>$g['gid'],
				count=>$g['count'],
				total=>$g['count']*$result[0]['price'],
				goods=>array(
					title=>$result[0]['title'],
					price=>$result[0]['price'],
					img=>$result[0]['img']
				)
			);
			array_push($data, $goods);	
		};
		$this->ajaxReturn($data);
	}

	public function addCart(){
		$model=D('Cart');
		$gid=$model->where('gid='.$_POST['gid'].' and uid='.$_POST['uid'])->select();

		if(count($gid)==0){
			$_POST['count']=1;
			$result=$model->add($_POST);
		}else{
			$save['count']=$gid[0]['count']+1;
			$result=$model->where('gid='.$_POST['gid'].' and uid='.$_POST['uid'])->save($save);
		}

		if($result > 0){
			$data=array(
				code=>'0',
				msg=>'加入购物车成功'
			);
		}else{
			$data=array(
				code=>'102',
				msg=>'加入购物车失败'
			);
		}
		$this->ajaxReturn($data);
	}
}