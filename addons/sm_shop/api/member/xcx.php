<?php


namespace sm_shop\api\member;
use sm_shop\controller;
use sm_shop\model\settingModel;

use sm_shop\model\customerModel;


use sm_shop\lib\wx_aes\WXBizDataCrypt;
use sm_shop\lib\wx_aes\ErrorCode;

class xcx extends controller{

    /**
     * 获取微信 小程序 openid
     */
    private $customer_bg_img_default = '/addons/sm_shop/assets/image/user-cover.png';
    public function login(){

        global $_GPC;
        global $_W;
        $json = [
            "status"=>0
        ];
        if( empty($_GET['code']) ){

            $json['status'] = 1;
            $json['description'] = '参数不合法';
            echo json_encode( $json );
            die();

        }

        $xcx_setting = settingModel::get_xcx();
        if( !empty( $xcx_setting['app_id'] ) || !empty( $xcx_setting['app_secret'] ) ){
            $appId = $xcx_setting['app_id'];
            $appSecret = $xcx_setting['app_secret'];
        }else {
            $appId = $_W['account']['key'];
            $appSecret = $_W['account']['secret'];
        }

        //todo 获取 后台设置中的小程序 appID, appSecret
//        $appId = 'wx85aca1163a609063';
//        $appSecret = '2d28d18418f438a3c3bfac0f7a526244';


        $code = $_GPC['code'];
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='. $appId . '&secret='. $appSecret . '&js_code=' .  $code . '&grant_type=authorization_code';

        $res = $this->request_remote( $url );
        $res['shop_name'] = !empty($xcx_setting['shop_name'])?$xcx_setting['shop_name']:'神秘商城';
        $res['location_key'] = !empty($xcx_setting['location_key'])?$xcx_setting['location_key']:'';

        $config_setting = settingModel::get_config();
        $res['shop_phone']      = !empty($config_setting['phone'])          ? $config_setting['phone']          : '';
        $res['shop_wechat']     = !empty($config_setting['wechat'])         ? $config_setting['wechat']         : '';
        $res['shop_qq']         = !empty($config_setting['qq'])             ? $config_setting['qq']             : '';
        $res['float_nav_show']  = !empty($xcx_setting['float_nav_show'])    ? $xcx_setting['float_nav_show']    : 0;

        // todo 获取用户中心背景图片
        $customer_bg_img = settingModel::get('customer_center','bg_img');
        $this->customer_bg_img_default = $this->url_host . $this->customer_bg_img_default;
        $res['customer_bg_img'] = $customer_bg_img ? tomedia($customer_bg_img['value']): $this->customer_bg_img_default;
        if( !empty($res['openid']) ){
            $_SESSION['openid'] = $res['openid'];

//            $res['customer'] = customerModel::getCustomerXcx( $res['openid'] );
            $res['customer'] = customerModel::getCustomer( $res['openid'] );
            $res['status'] = 0;
        }else{
            $res['status'] = 1;
        }

        echo json_encode( $res );
    }

    public function request_remote( $url , $data = [] ){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $file = curl_exec($ch);
        curl_close($ch);

        if( $file === false ){
            $file = curl_error( $ch );
        }

        return json_decode( $file, true );
    }


    public function getCustomer(){

        $res = [
            'status'=>0,
            'telephone'=>'',
        ];

        if( empty( $_GET['openid']) ){

            $res['status'] = 1;
            $res['description'] = '请求不合法';
            echo json_encode( $res );
            die();

        }

        $openid = $_GET['openid'];

//        $customer_xcx = customerModel::getCustomerXcx( $openid );
        $customer_xcx = customerModel::getCustomer( $openid );

        if( !empty( $customer_xcx ) &&  !empty( $customer_xcx['telephone'] )  ){
            $res['telephone'] = $customer_xcx['telephone'];
            $res['customer'] = $customer_xcx;
        }else{
            $res['status'] = 1;
            $res['description'] = '用户未注册';
        }

        echo json_encode( $res );

    }

    /**
     * 小程序注册
     */
    public function register(){

        global $_W,$_GPC;
        $res = [
            'status'=>0
        ];

        if( empty( $_GET['openid']) ){

            $res['status'] = 1;
            $res['description'] = '请求不合法';
            echo json_encode( $res );
            die();

        }

        $openid = $_GET['openid'];
//        $uniacid = $_GET['i'];
        $referee = !empty( $_GET['referee'] ) ? intval($_GET['referee']) : 0;

        $xcx_setting = settingModel::get_xcx();
        if( !empty( $xcx_setting['app_id'] ) ){
            $appId = $xcx_setting['app_id'];
        }else{
            $appId = $_W['account']['key'];
        }

        if( $_GPC['__input'] ){
            $post = $_GPC['__input'];
        }else{
            $post = $_GPC;
        }
        $sessionKey = $post['session_key'];
        $encryptedData = $post['encryptedData'];
        $iv = $post['iv'];

        $userInfo = !empty($post['userInfo'])? json_decode( $post['userInfo'], true) :[];

        $pc = new WXBizDataCrypt($appId, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode == 0) {
            $res['data'] = json_decode($data, true);

            //todo 手机号 存储到数据库，
            if( !empty( $res['data']['phoneNumber'] ) ){

                $res['customer_id'] = customerModel::registerByXcx( $openid, $res['data']['phoneNumber'], $referee, $userInfo );
                if( empty( $res['customer_id'] ) ){
                    $res['status'] =  1;
                    $res['description'] = '注册失败';
                }
            }else{
                $res['status'] =  1;
                $res['description'] = '手机号获取失败';

            }

        } else {
            $res['status'] =  $errCode;
        }


        echo json_encode( $res );


    }

    //todo 获取用户的推广二维码
    public function myQrcode(){

        global $_W;
        $res = [
            'status'=>0,
        ];
        $customer_id = $_W['customer']['id'];
        $customerQrcode = customerModel::getCustomerQrcode( $customer_id );

        if( !empty($customerQrcode) ){

            $customerQrcode['qrcode'] = tomedia( $customerQrcode['qrcode_path'] );
            $res['data'] = $customerQrcode;
            echo json_encode( $res );
            return;

        }else{

            $scene_params = 'customer_id=' . $customer_id . '&uniacid=' . $_W['uniacid'];

            $account_api = \WeAccount::create();

            $response = $account_api->getCodeUnlimit($scene_params, '', 430, array(
                'auto_color' => false,
                'line_color' => array(
                    'r' => '#ABABAB',
                    'g' => '#ABABAC',
                    'b' => '#ABABAD',
                ),
            ));

            if( is_error( $response )){
                $res['status'] = 1;
                $res['description'] = '二维码生成失败';
                echo json_encode( $res );
                die();
            }

            load()->func('file');

            $qrcode_path =  'qrcode/' . $_W['uniacid'];
            $qrcode_path .= '/customer_' . $customer_id . '.png' ;
            $ret = file_write(  $qrcode_path, $response );

            //todo 保存数据库
            $data = [
                'customer_id'   => $customer_id,
                'scene'         => $scene_params,
                'qrcode_path'   => $qrcode_path,
            ];

            $res['ret'] = customerModel::setCustomerQrcode( $data );

            if( $res['ret'] ){
                $data['qrcode'] = tomedia( $data['qrcode_path'] );
                $res['data'] = $data;
            }else{
                $res['status'] = 1;
                $res['description'] = '二维码保存失败';
            }


            echo json_encode( $res );
        }



    }
}