<?php
namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\categoryModel;
use sm_shop\model\settingModel;
class home extends controller{

    private $token=null;

    public function index(){

        $category = new categoryModel();
//        $cat = $category->get_list();
        $cat = $category->tree();

        echo json_encode( $cat );
    }

    public function setting(){
        $shop_name = settingModel::get('config','shop_name');
        $home_live_show = settingModel::get('config','home_live_show');
        $phone = settingModel::get('config','phone');
        $float_nav_show = settingModel::get('xcx','float_nav_show');
        $diy_id = settingModel::getItem( 'diy', 'home' );
        $json = [
            'status'=>0,
            'settings'=>[
                'shop_name'     => !empty($shop_name)?$shop_name['value']:'',
                'phone'         => !empty($phone)?$phone['value']:'',
                'home_live_show'=> !empty($home_live_show)? intval( $home_live_show['value'] ):1,
                'float_nav_show'=> !empty($float_nav_show)? intval( $float_nav_show['value'] ):1,
                'diy_id'        => $diy_id,
            ]
        ];

        echo json_encode( $json );
    }

    public function diy(){

        $json = [
            'status'=>0,
        ];
        $json['diy_id'] = settingModel::getItem( 'diy', 'home' );

        echo json_encode( $json );
    }
/*
    // 暂时先适用于公众号
    public function kefu_open(){

        $json = [

            'status'=>0
        ];
        global $_W;
        $this->token = $_W['account']->getAccessToken();

        $kefu = $this->kefu_list( );

        if( empty( $kefu) ){
            $json['status'] = 1;
            $json['description'] = '客服不存在';
            echo json_encode( $json );
            die();
        }
        $url = "https://api.weixin.qq.com/customservice/kfsession/create?access_token={$this->token}";

        $data = [
            "kf_account" => $kefu['kf_account'],
            "openid"     => $_W['openid']
        ];

        $response = ihttp_post( $url, json_encode( $data ) );
        echo json_encode( $response );

    }

    private function kefu_list(){

        $url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='. $this->token;
        $response = ihttp_get( $url );
        if($response['status'] == 'OK' && !empty( $response['content'] ) ){
            $content =  json_decode( $response['content'], true );
            $kefu_list = $content['kf_list'];
        }
        return !empty( $kefu_list )?$kefu_list[0]:'';
    }
*/

}
