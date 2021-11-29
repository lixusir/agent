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

	/**
	 * 推广区逻辑处理
	 */
	public function credit_log(){

		global $_W;

		$credit_log = pdo_getall('ewei_shop_creditshop_log',array('is_fan'=>0,'status >='=>2),array('id','logno','openid','credit','goodsid','address'),'',[],2);

		$upd_ids = '';

		foreach($credit_log as $log){

			/**商品信息**/
			$credit_goods = pdo_get('ewei_shop_creditshop_goods',array('id'=>$log['goodsid']),array('id','abonus_rate','pool_price'));

			/**业务逻辑处理**/
			m('levelrate')->level_upd($log['openid']);

			m('levelrate')->level_rate($log);

			m('levelrate')->set_pool($credit_goods['pool_price']);

			m('levelrate')->abonus_rate($log,$credit_goods);
			/**结束**/

			$upd_ids .= $log['id'].',';


		}

		$upd_ids = trim($upd_ids,',');

		if(!empty($upd_ids)){

			$is_upd = pdo_query('update '.tablename('ewei_shop_creditshop_log')." set is_fan=1 where id in ($upd_ids) ");

		}

		echo '更新-'.intval($is_upd).'条数据';

	}

	/**
	 * 兑换区业务处理
	 */
	public function shop_list(){

		global $_W;

		$order = pdo_getall('ewei_shop_order',array('is_fan'=>0,'status'=>3),array('id','ordersn','openid','finishtime'),'',[],2);

		$upd_ids = '';

		foreach($order as $o){

			/**业务逻辑处理**/
			//if($order['finishtime'] > TIMESTAMP+(24*60*60*7)){

				$upd_ids .= $o['id'].',';

				m('levelrate')->commission_rate($o);

			//}

			/**结束**/

		}

		$upd_ids = trim($upd_ids,',');

		if(!empty($upd_ids)){

			$is_upd = pdo_query('update '.tablename('ewei_shop_order')." set is_fan=1 where id in ($upd_ids) ");

		}

		echo '更新-'.intval($is_upd).'条数据';

	}

	/**
	 * 奖金池分红
	 */
	public function pool(){

		global $_W;

		$w = date('w');

		if($w != 1){

			//echo  'not';die;

		}

		m('levelrate')->get_pool();


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

		$field = ['id','openid','createtime','set_score','get_score','release_score'];

		while (true){

			$release_list = pdo_getall('ewei_shop_creditshop_log',$where,$field,'',[],[$page,$limit]);

			if(empty($release_list)){

				break;

			}

			foreach($release_list as $v){

				$upd_data = [];

				//待释放积分
				$tobe_release = bcsub(bcadd($v['set_score'],$v['get_score'],2) ,$v['release_score'],2);

				//释放至钱包
				$tobe_amount =  bcmul($tobe_release,$config['release_rate']/100,2);

				if($tobe_amount <= 0){

					$upd_data['release_status'] = 1;

					$tobe_amount = $tobe_release;

				}

				if($tobe_amount > 0){

					m('member')->setCredit($v['openid'],'credit2',$tobe_amount,[$_W['uid'],'积分释放'],500);

				}

				//递减
				$upd_data['release_score'] = bcadd($v['release_score'],$tobe_amount,2);

				pdo_update('ewei_shop_creditshop_log',$upd_data,array('id'=>$v['id']));

			}

			$page++;
		}

		echo 'ok';
	}

	public function get_del(){

		m('level_rate')->del(1);
	}
}

?>
