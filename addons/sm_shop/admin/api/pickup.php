<?php
namespace sm_shop\admin\api;
use sm_shop\admin\model\settingModel;
use sm_shop\controller;
use sm_shop\admin\model\pickupModel;

class pickup extends controller{


    public function page_list(){

        $this->template( 'function/pickup/list' );

    }

    public function page_edit(){

        $this->template( 'function/pickup/edit' );

    }

    public function index(){

        $list = pickupModel::get_list( );
        echo json_encode( $list );

    }

    public function info(){

        $id = $_GET['id'];
        $info = pickupModel::info( $id );
        echo json_encode( $info );

    }

    public function edit(){

        global $_GPC;

        $res = [
            'status'=>0
        ];
        if( !empty( $_GPC['id'] ) ){
            $pickup_id = $_GPC['id'];
        }

        if( empty( $_GPC['name'] ) ){
            $res['description'] = '请输入名字';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }

        if( empty( $_GPC['phone'] ) ){
            $res['description'] = '请输入电话';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }

        if( empty( $_GPC['province'] ) ){
            $res['description'] = '请输入省份';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }
        if( empty( $_GPC['city'] ) ){
            $res['description'] = '请输入城市';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }
        if( empty( $_GPC['area'] ) ){
            $res['description'] = '请输入区，县';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }
        if( empty( $_GPC['street'] ) ){
            $res['description'] = '请输入街道';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }
        if( empty( $_GPC['detail'] ) ){
            $res['description'] = '请输入详细地址';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }

        $data = [
            'name'      => $_GPC['name'],
            'phone'      => $_GPC['phone'],
            'province'  => $_GPC['province'] ,
            'city'      => $_POST['city'],
            'area'      => $_GPC['area'],
            'street'    => $_GPC['street'],
            'detail'    => $_GPC['detail'],
            'latitude'  => $_GPC['latitude'],
            'longitude' => $_GPC['longitude'],
            'status'=>isset($_GPC['status']) ? $_GPC['status'] : 1,
        ];



        //todo 修改

        if( !empty( $pickup_id ) ){
            $ret = pickupModel::edit( $pickup_id, $data );
        }else{
            $ret = pickupModel::add(  $data );
        }


        // todo 添加 回复关键字

        $res['query'] = $ret;
        echo json_encode( $res );

    }


    public function remove(){

        $res= [
            'status'=>0,
        ];

        $id = $_POST['id'];
        if( empty( $id ) ){

            $res= [
                'status'=>1,
                'description'=>'请选中你要删除的自提点',
            ];

            echo json_encode( $res );
            die();
        }

        $res['ret'] = pickupModel::remove( $id );

        echo json_encode( $res );

    }

    public function latlng_to_addr(){

        $lat = $_GET['lat'];
        $lng = $_GET['lng'];
//        $key = 'UQSBZ-NWHWU-36AVM-4DM5O-MOEEF-2CFLA';

        $setting_item = settingModel::get('config','latlng_to_addr_ley');
        if( empty($setting_item['value']) ){

            $res = [
                'status'=>1,
                'description'=>'请先在后台设置腾讯位置服务KEY'
            ];
            echo json_encode( $res );
            die();
        }

        $key = $setting_item['value'];
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?location='. $lat . ','. $lng
            . '&key=' . $key . '&get_poi=0';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $file = curl_exec($ch);
        curl_close($ch);


        echo $file;


    }

}