<?php

class PoolLog_EweiShopV2Page extends PluginWebPage{

    public function main(){

        global $_W;
        global $_GPC;

        $where = '';

        $params = array(':u'=>$_W['uniacid']);

        $pindex = max(1,$_GPC['page']);

        $psize = 20;

        if(!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])){

            $where .= " and get_time >= :starttime ";

            $where .= " and get_time <= :endtime ";

            $params[':starttime'] = strtotime($_GPC['time']['start']);

            $params[':endtime'] = strtotime($_GPC['time']['end']);
        }

        $total = pdo_fetchcolumn('SELECT count(id) from '.tablename('ewei_shop_member_pool')." where uniacid=:u ".$where,$params);

        $list = pdo_fetchall('SELECT * from '.tablename('ewei_shop_member_pool')." where uniacid=:u ".$where." order by status asc limit ".($pindex-1)*$psize.','.$psize,$params);


        include $this->template();
    }
}
