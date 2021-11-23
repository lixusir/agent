<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Task_EweiShopV2Page extends MobilePage
{
	public function main()
	{
		//$this->runTasks();
	}

	public function release_list(){

		global $_W;

		$limit = 2;

		$page = 1;

		//释放比例
		$config = m('common')->getSysset('shopset');

		$where = [];

		$where['status >='] = 2;

		$where['release_status'] = 0;

		//$where['end_time <'] = TIMESTAMP;

		$field = ['id','openid','createtime','credit','get_score','release_score'];

		while (true){

			$release_list = pdo_getall('ewei_shop_creditshop_log',$where,$field,'',[],[$page,$limit]);

			if(empty($release_list)){

				break;

			}

			foreach($release_list as $v){

				$upd_data = [];

				//待释放积分
				$tobe_release = bcsub(bcadd($v['credit'],$v['get_score'],2) ,$v['release_score'],2);

				//释放至钱包
				$tobe_amount =  bcmul($tobe_release,$config['release_rate']/100,2);

				if($tobe_amount <= 0){

					$upd_data['release_status'] = 1;

					$tobe_amount = $tobe_release;

				}

				if($tobe_amount > 0){

					m('member')->setCredit($v['openid'],'credit2',$tobe_amount,[$_W['uid'],'积分释放']);

				}

				//递减
				$upd_data['release_score'] = bcadd($v['release_score'],$tobe_amount,2);

				pdo_update('ewei_shop_creditshop_log',$upd_data,array('id'=>$v['id']));

			}

			$page++;
		}

		echo 'ok';
	}
}

?>
