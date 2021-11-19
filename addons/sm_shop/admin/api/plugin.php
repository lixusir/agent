<?php
namespace sm_shop\admin\api;
use sm_shop\controller;

class plugin extends controller{

    public function page_list(){

        $this->template( 'plugin/list' );

    }


    public function index(){

        global $_W;

        $plugin_list = [];

        if( !empty( $_W['current_module']['plugin_list']) ){
            $list = $_W['current_module']['plugin_list'];
            foreach( $list as $plugin_code ){
                if( $plugin_code = 'sm_shop_plugin_diy' ){
                    $plugin_list[] = [
                        'name'=>'自定义装修插件',
                        'code'=>'sm_shop_plugin_diy',
                        'logo'=>'/addons/' . $plugin_code . '/icon.jpg',
                        'link'=>'/web/index.php?c=site&a=entry&m=sm_shop_plugin_diy&do=entry',
                    ];
                }

            }
        }


        echo json_encode( $plugin_list );

    }

}