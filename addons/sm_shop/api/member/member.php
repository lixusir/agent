<?php


namespace sm_shop\api\member;
use sm_shop\controller;


use sm_shop\model\customerModel;

class member extends controller{

    /**
     * 获取登录状态
     */
    public function status(){


        $res= [
            'status'=>0
        ];
        global $_W, $_GPC;
//        print_r( $_SESSION );
        if( !empty($_W['member']['uid'] ) && !empty($_W['customer'] ) ){
            $res['customer_id'] = $_W['customer']['id'];
            $fans = mc_fansinfo( $_W['member']['uid'] );
            $res['nickname'] = !empty($fans['nickname'])?$fans['nickname']:'';
            $res['avatar'] = !empty($fans['avatar'])?$fans['avatar']:'';
        }else{
            $res['status'] = 1;
            $res['description'] = '没有登录';
        }

        echo json_encode( $res );
    }

}