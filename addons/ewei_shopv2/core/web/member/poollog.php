<?php

class Poollog_EweiShopV2Page extends WebPage {

    public function main(){

        global $_W;
        global $_GPC;

        $where = '';

        $params = array();

        $pindex = max(1,$_GPC['page']);

        $psize = 20;

        if(!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])){

            $where .= " and get_time >= :starttime ";

            $where .= " and get_time <= :endtime ";

            $params[':starttime'] = strtotime($_GPC['time']['start']);

            $params[':endtime'] = strtotime($_GPC['time']['end']);
        }

        $total = pdo_fetchcolumn('SELECT count(id) from '.tablename('ewei_shop_member_pool')." where 1 ".$where,$params);

        $list = pdo_fetchall('SELECT * from '.tablename('ewei_shop_member_pool')." where 1 ".$where." order by state asc limit ".($pindex-1)*$psize.','.$psize,$params);

        include $this->template();
    }

    public function log(){

        global $_W;
        global $_GPC;


        $where = ' and p.pid=:pid ';

        $params = array(':pid'=>intval($_GPC['pid']));

        $pindex = max(1,$_GPC['page']);

        $psize = 20;

        if(!empty($_GPC['keyword'])){

            $where .= " and (m.nickname like :keyword or m.mobile like :keyword or p.ordersn like :keyword )" ;

            $params[':keyword'] = '%'.$_GPC['keyword'].'%';

        }

        if(!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])){

            $where .= " and p.createtime >= :starttime ";

            $where .= " and p.createtime <= :endtime ";

            $params[':starttime'] = strtotime($_GPC['time']['start']);

            $params[':endtime'] = strtotime($_GPC['time']['end']);
        }

        $total = pdo_fetchcolumn('SELECT count(p.id) from '.tablename('ewei_shop_member_pool_log')." as p join ".tablename('ewei_shop_member')." as m on m.openid=p.openid where 1 ".$where,$params);

        $list = pdo_fetchall('SELECT p.*,m.avatar,m.nickname from '.tablename('ewei_shop_member_pool_log')." as p join ".tablename('ewei_shop_member')." as m on m.openid=p.openid where 1 ".$where." order by p.createtime asc limit ".($pindex-1)*$psize.','.$psize,$params);

        include $this->template();
    }
}
