<?php

class Bargain_log_EweiShopV2Page extends MobileLoginPage{

    public function main(){

        global $_W;
        global $_GPC;

        include $this->template();
    }

    public function get_list(){

        global $_W;
        global $_GPC;

        if($_W['isajax']){

            $pindex = max(1,$_GPC['page']);

            $psize = 20;

            $total = pdo_fetchcolumn('select count(1) from '.tablename('ewei_shop_creditshop_bargain_log')." as l join ".tablename('ewei_shop_creditshop_goods')." as g on g.id=l.gid  where  l.openid=:o and l.uniacid=:u ",array(':o'=>$_W['openid'],':u'=>$_W['uniacid']));

            $list = pdo_fetchall('select l.*,g.title,g.thumb from '.tablename('ewei_shop_creditshop_bargain_log')." as l join ".tablename('ewei_shop_creditshop_goods')." as g on g.id=l.gid  where  l.openid=:o and l.uniacid=:u order by createtime desc limit ".($pindex-1)*$psize.','.$psize,array(':o'=>$_W['openid'],':u'=>$_W['uniacid']));

            foreach($list as $k=>$v){

                $list[$k]['user'] = pdo_fetch('select m.nickname,m.mobile,m.realname from '.tablename('ewei_shop_creditshop_log')." as l join ".tablename('ewei_shop_member')." as m on m.openid=l.openid where l.id=:id ",array(':id'=>$v['oid']));

                $list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);

                $list[$k]['thumb']      = tomedia($v['thumb']);

            }

            show_json(1,['list'=>$list,'pagesize'=>$psize,'total'=>$total]);

        }
    }
}