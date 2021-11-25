<?php
/*珍贵资源 请勿转卖*/
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Log_credit_EweiShopV2Page extends MobileLoginPage
{
	public function main()
	{
		global $_W;
		global $_GPC;

		include $this->template();
	}

	public function get_list()
	{
		global $_W;
		global $_GPC;
		global $_W;
		global $_GPC;
		$type = $_GPC['type'];
		$pindex = max(1, intval($_GPC['page']));

		$psize = 20;

		$condition = ' and openid=:openid and uniacid=:uniacid ';

		$params = array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']);

		if(!empty($type) && $type != '' ){

			$condition .= " and change_type in ($type) ";

		}

		$list = pdo_fetchall('select * from ' . tablename('ewei_shop_member_credit_record') . (' where 1 ' . $condition . ' order by createtime desc LIMIT ') . ($pindex - 1) * $psize . ',' . $psize, $params);

		$total = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member_credit_record') . (' where 1 ' . $condition), $params);

		foreach ($list as &$row) {

			$row['createtime'] = date('Y-m-d H:i', $row['createtime']);

		}

		unset($row);

		show_json(1, array('list' => $list, 'total' => $total, 'pagesize' => $psize));
	}

}

?>
