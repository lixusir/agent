<?php

class sm_shopModuleSite extends WeModuleSite {

    public function doWebWeb(){

//        include IA_ROOT . '/addons/sm_shop/vue/backend.html';
        include IA_ROOT . '/addons/sm_shop/init.php';
    }

    public function doWebApi(){

        //todo 加载 基类（ controller, model ）

        require_once IA_ROOT . '/addons/sm_shop/init.php';


    }

    public function doMobileMobile(){
        checkauth();
        //todo 保存用户
        $this->setCustomerFromWeiqing();
        include IA_ROOT . '/addons/sm_shop/vue/index.html';
    }

    public function doMobileApi(){

        $this->getCustomer();
        include IA_ROOT . '/addons/sm_shop/init.php';
    }

    public function setCustomerFromWeiqing(  ){

        global $_W;

        // 获取用户信息，并存到用户表中
        $userinfo = mc_fansinfo( $_W['openid'] );
        if( !is_error( $userinfo ) && !empty( $userinfo ) ){

            $sql = 'select * from sh_customer where open_id="' . $userinfo['openid'] . '"';
            $customer = pdo_fetch( $sql );

            $date = date('Y-m-d H:i:s');
            if( empty( $customer ) ){
                $insert_sql = "insert into sh_customer set"
                    . " uniacid='" . $userinfo['uniacid'] . "', "
                    . " open_id='" . $userinfo['openid'] . "', "
                    . " customer_group_id='1',"
                    . " `name`='" . $userinfo['tag']['nickname'] . "',"
                    . " headUrl='" . $userinfo['tag']['avatar'] . "',"
                    . " telephone='',"
                    . " `status`='1',"
                    . " remark='" . $userinfo['tag']['remark'] . "',"
                    . " create_time='" . $date . "'";
                pdo_query( $insert_sql );
            }
        }

    }

    private function getCustomer(){

        global $_GPC, $_W;
        //调试
//        $_W['openid'] = 'oUMW05N8nirn5X2kso2xfIQNk2ms';
//        $_W['member']['uid'] = '69';
        if( !empty($_W['openid']) ) {
            $params = [
                'openid' => $_W['openid']
            ];
            $sql = 'select * from sh_customer where open_id="' . $_W['openid'] . '"';
            $_W['customer'] = pdo_fetch( $sql );
        }

    }
}