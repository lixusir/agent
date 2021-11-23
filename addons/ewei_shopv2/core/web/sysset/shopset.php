<?php

class Shopset_EweiShopV2Page extends WebPage{

    public function main(){

        global $_W, $_GPC;

        if ($_W['ispost']) {

            ca('sysset.contact.edit');

            $data = is_array($_GPC['data']) ? $_GPC['data'] : array();

            $data['release_rate'] = floatval($data['release_rate']);


            m('common')->updateSysset(array('shopset' => $data));

            $shop = m('common')->getSysset('shop');

            $shop['release_rate'] = $data['release_rate'];

            m('common')->updateSysset(array('shop' => $shop));

            plog('sysset.contact.edit', '修改系统设置-平台设置');

            show_json(1);
        }

        $data = m('common')->getSysset('shopset');

        if (empty($data)) {
            $shop = m('common')->getSysset('shop');
            $data['qq'] = $shop['qq'];
            $data['address'] = $shop['address'];
            $data['phone'] = $shop['phone'];
        }

        include $this->template();

    }
}