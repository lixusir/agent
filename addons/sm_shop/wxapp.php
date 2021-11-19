<?php
/**
 * 小程序入口
 */
defined('IN_IA') or exit('Access Denied');


if( !empty( $_SERVER['HTTP_ORIGIN']) ){
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
//跨域问题, 否则session无法保存
//        header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods:GET,POST");
header("Access-Control-Allow-Headers:Content-Type");
header("Content-Type: text/html;charset=utf-8");

class sm_shopModuleWxapp extends WeModuleWxapp {
//    public $token = 'sm_shoptoken'; //接口通信token
    public function doPageIndex() { // 接口一个名为"index"的接口

        echo 123;
//        $this->result(0, '', array('test' => 1235)); //  响应json串
    }

    public function doPageApi(){

        $this->getCustomer();
        require_once IA_ROOT . '/addons/sm_shop/init.php';

    }

    private function getCustomer(){

        global $_GPC, $_W;
        if( !empty($_GPC['openid']) ) {
            $_W['openid'] = $_GPC['openid'];
            $params = [
                'openid' => $_GPC['openid']
            ];

            $sql = 'select * from sh_customer where open_id="' . $_W['openid'] . '"';
            $_W['customer'] = pdo_fetch($sql);
            $_W['platform'] = 'wxapp';
        }

    }
}