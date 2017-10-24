<?php
namespace Home\Controller;

use Think\Controller;

class OrderController extends Controller
{
	public function showOrder(){
		$order=D('Order_tab');
		$orderdetail=D('Orderdetail');
		$goods=D('Goods');

		$orderlist=$order->where('uid='.$_POST['uid'])->select();
		$oids=$order->Distinct(true)->where('uid='.$_POST['uid'])->order("date desc")->select();

		$data=array();
		foreach ($oids as $o){
			$gid=$orderdetail->where('oid='.$o['oid'])->field('gid')->select();
			$order=array(
				oid=>$o['oid'],
				date=>$o['date'],
				total=>$o['total'],
				goods=>array()
			);

			foreach($gid as $g){
				$goodslist=$goods->where('id='.$g['gid'])->select();
				$goodsitem['id'] = $goodslist[0]['id'];
				$goodsitem['title'] = $goodslist[0]['title'];
				$goodsitem['price'] = $goodslist[0]['price'];
				$goodsitem['img'] = $goodslist[0]['img'];
				array_push($order['goods'],$goodsitem);
			}
			array_push($data,$order);
		}
		$this->ajaxReturn($data);
	}

	public function buy(){
		$order=D('Order_tab');
		$orderdetail=D('Orderdetail');
		$cart=D('cart');

		$count=1;
		$date = new \DateTime;
		$datetime=$date->format('Y-m-d H:i:s');

		$dateformat = \DateTime::createFromFormat('YmdHis', '20160618052306');
		$dateid=$date->format('YmdHis');

		$add['uid']=$_POST['uid'];
		$add['oid']=$dateid.$_POST['uid'];
		$add['date']=$datetime;
		$add['total']=$_POST['price']*$count;

		$detail['oid']=$dateid.$_POST['uid'];
		$gid = $_POST['gid'];

		$result=$order->add($add);

		foreach($gid as $g){
			$detail['gid']=$g;
			$result1 = $orderdetail->add($detail);
		}

		if($result>=0 and $result1>=0){
			if($_POST['cid']){
				foreach ($_POST['cid'] as $c){
					$cart->where('id='.$c)->delete();
				}
			}
			$data=array(
				code=>'0',
				msg=>'购买成功'
			);
		}else{
			$data=array(
				code=>'102',
				msg=>'购买失败'
			);
		}

		$this->ajaxReturn($data);
	}

	public function manage(){
		$model=D('Order_tab');
		$result=$model->select();
		$this->ajaxReturn($result);
	}
}