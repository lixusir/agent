<?php
/*珍贵资源 请勿转卖*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Release_log_EweiShopV2Page extends MobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $_GPC['type'] = intval($_GPC['type']);
        include $this->template();
    }

    public function get_list()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));

        $psize = 10;

        $apply_type = array(0 => '微信钱包', 2 => '支付宝', 3 => '银行卡');

        $condition = ' and  b.uniacid=:uniacid and b.get_openid=:get_openid ';

        $params = array(':uniacid' => $_W['uniacid'],':get_openid'=>$_W['openid']);

        $total = pdo_fetchcolumn('select count(b.id) from '.tablename('ewei_shop_creditshop_bargain')." as b join ".tablename('ewei_shop_member')." as m on m.openid=b.openid  where 1 ".$condition,$params);

        $list = pdo_fetchall('select b.id,b.score,b.openid,b.createtime,m.avatar,m.mobile,m.nickname from '.tablename('ewei_shop_creditshop_bargain')." as b left join ".tablename('ewei_shop_member')." as m on m.openid=b.openid  where 1 ".$condition." order by b.id desc limit ".($pindex-1)*$psize.','.$psize,$params);

        foreach($list as $k=>$v){

            $list[$k]['avatar'] = $v['avatar']?tomedia($v['avatar']):'../addons/ewei_shopv2/static/images/noface.png';

            $list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);


            if($v['openid'] != 'null'){

                $list[$k]['realname'] = $v['realname']?$v['realname']:$v['nickname'];

            }else{

                $list[$k]['realname'] = '成本释放';
            }


        }

        show_json(1, array('list' => $list, 'total' => $total, 'pagesize' => $psize));
    }
}

?>
