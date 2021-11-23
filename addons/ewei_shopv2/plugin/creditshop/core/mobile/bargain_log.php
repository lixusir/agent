<?php

class Bargain_log_EweiShopV2Page extends MobileLoginPage{

    //我的砍价列表
    public function main(){

        global $_W,$_GPC;

        $openid = $_W['openid'];
        $member = m('member')->getMember($openid);
        $shop = m('common')->getSysset('shop');
        $uniacid = $_W['uniacid'];
        $status = intval($_GPC['status']);

        $set = m('common')->getPluginset('creditshop');

        if($_W['isajax']) {
            $merchid = intval($_W['merchid']);
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $condition = ' and log.openid=:openid and  log.uniacid = :uniacid ';
            if (0 < $merchid) {
                $condition .= ' and log.merchid = ' . $merchid . ' ';
            }
            $params = array(':uniacid' => $_W['uniacid'], ':openid' => $openid);
            if ($status == 2) {
                $condition .= ' and log.status = 2 ';
            } else if ($status == 3) {
                $condition .= ' and log.status = 3 ';
            }
            $sql = 'SELECT COUNT(*) FROM ' . tablename('ewei_shop_creditshop_log') . ' log' . "\r\n" . '                left join ' . tablename('ewei_shop_creditshop_goods') . ' g on log.goodsid = g.id' . "\r\n" . '                where 1 ' . $condition;
            $total = pdo_fetchcolumn($sql, $params);
            $list = array();
            if (!(empty($total))) {
                $sql = 'SELECT log.id,log.logno,log.goodsid,log.goods_num,log.status,log.eno,log.paystatus,g.title,g.type,g.thumb,log.credit,log.money,log.dispatch,g.isverify,g.goodstype,log.addressid,log.storeid,' . 'g.goodstype,log.time_send,log.time_finish,log.iscomment,op.title as optiontitleg,g.merchid ' . ' FROM ' . tablename('ewei_shop_creditshop_log') . ' log ' . ' left join ' . tablename('ewei_shop_creditshop_goods') . ' g on log.goodsid = g.id ' . ' left join ' . tablename('ewei_shop_creditshop_option') . ' op on op.id = log.optionid ' . ' where 1 ' . $condition . ' ORDER BY log.createtime DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
                $list = pdo_fetchall($sql, $params);
                $list = set_medias($list, 'thumb');
                foreach ($list as &$row) {
                    if ((0 < $row['credit']) & (0 < $row['money'])) {
                        $row['acttype'] = 0;
                    } else if (0 < $row['credit']) {
                        $row['acttype'] = 1;
                    } else if (0 < $row['money']) {
                        $row['acttype'] = 2;
                    } else {
                        $row['acttype'] = 3;
                    }
                    if (($row['money'] - intval($row['money'])) == 0) {
                        $row['money'] = intval($row['money']);
                    }
                    $row['isreply'] = $set['isreply'];
                }
                unset($row);
            }

            show_json(1, array('list' => $list, 'pagesize' => $psize, 'total' => $total));
        }

        include $this->template();
    }
}
