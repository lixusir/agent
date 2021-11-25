<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'commission/core/page_login_mobile.php';
class Down_EweiShopV2Page extends CommissionMobileLoginPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$member = $this->model->getInfo($_W['openid']);
		$levelcount1 = $member['level1'];
		$levelcount2 = $member['level2'];
		$levelcount3 = $member['level3'];
		$level1 = $level2 = $level3 = 0;

		$level1 = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . ' where agentid=:agentid and uniacid=:uniacid limit 1', array(':agentid' => $member['id'], ':uniacid' => $_W['uniacid']));

		$level2 = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . ' where agentid in( ' . implode(',', array_keys($member['level1_agentids'])) . ') and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

		$total = $level1 + $level2 + $level3;
		include $this->template();
	}

	public function get_list()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$member = $this->model->getInfo($openid);
		$total_level = 0;
		$level = intval($_GPC['level']);
		((3 < $level) || ($level <= 0)) && ($level = 1);
		$condition = '';
		$levelcount1 = $member['level1'];
		$levelcount2 = $member['level2'];
		$levelcount3 = $member['level3'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$state = intval($_GPC['state']);//0 未参与砍价的团队,1=参与砍价的团队

		$credit_log = pdo_getall('ewei_shop_creditshop_bargain',array('get_openid'=>$member['openid']),array('openid'));

		$openids = '';

		foreach($credit_log as $lo){

			$openids .= "'".$lo['openid']."',";

		}

		if(!empty($state)){

			$condition = " and openid in (".trim($openids,',').") ";

		}else{

			$condition = " and openid not in (".trim($openids,',').") ";

		}

		if ($level == 1) {


			$condition .= ' and agentid=' . $member['id'];

			$hasangent = true;

			$total_level = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . ' where 1 '.$condition.' and uniacid=:uniacid limit 1', array( ':uniacid' => $_W['uniacid']));


		}
		if ($level == 2) {

			if (empty($levelcount1)) {
					show_json(1, array(
						'list'     => array(),
						'total'    => 0,
						'pagesize' => $psize
					)
				);
			}

			$condition .= ' and agentid in( ' . implode(',', array_keys($member['level1_agentids'])) . ')';

			$hasangent = true;

			$total_level = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . ' where 1 '.$condition.' and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

		}

		$list = pdo_fetchall('select id,avatar,nickname,mobile,createtime from ' . tablename('ewei_shop_member') . ' where uniacid = ' . $_W['uniacid'] . ' ' . $condition . '  ORDER BY isagent desc,id desc limit ' . (($pindex - 1) * $psize) . ',' . $psize);

		foreach ($list as &$row) {

			$row['avatar'] = $row['avatar']?tomedia($row['avatar']):'../addons/ewei_shopv2/static/images/noface.png';

			$row['createtime'] = date('Y-m-d H:i', $row['createtime']);
		}

		unset($row);
		show_json(1, array('list' => $list, 'total' => $total_level, 'pagesize' => $psize));
	}
}

?>
