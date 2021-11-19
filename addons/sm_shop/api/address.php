<?php
namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\addressModel;

class address extends controller{

    public function __construct()
    {

        $res = [
            'status'=>'1',
            'description'=>'请先登录',
        ];

        global $_W;
//        print_r( $_W );
        if( empty( $_W['customer'] ) ){
            echo json_encode( $res );
            die();
        }
    }

    public function single(){
        global $_W;
        $customer_id = $_W['customer']['id'];
        $address_id = $_GET['id'];



        $address = addressModel::single( $address_id, $customer_id );

        echo json_encode( $address );
    }

    public function get_list(){

//        $res = [
//            'status'=>'1',
//            'description'=>'请先登录',
//        ];
//        if( empty($_SESSION['customer_id']) ){
//            echo json_encode( $res );
//            die();
//        }
        global $_W;
        $customer_id = $_W['customer']['id'];

        $list = addressModel::get_list( $customer_id );

        echo json_encode( $list );

    }

    public function edit(){
        $res = [
            'status'=>'0',
        ];
//        if( empty($_SESSION['customer_id']) ){
//            echo json_encode( $res );
//            die();
//        }
        global $_W, $_GPC;

        $customer_id = $_W['customer']['id'];

        if( $_GPC['__input']){
            $data = $_GPC['__input'];
        }else{
            $data = $_POST;
        }
        $data['customer_id'] = $customer_id;
        $id = !empty( $_GPC['id'] ) ? $_GPC['id'] : '';

        if( $id ){
            $ret = addressModel::edit( $id, $data );
            // todo bug
            $res['id'] = $ret;
        }else{
            $ret = addressModel::add( $data );
            // todo bug
            $res['id'] = $ret;

        }

        echo json_encode( $res );
    }

    public function remove(){

        global $_GPC;
        $res = [
            'status'=>0
        ];

        global $_W;
        $customer_id = $_W['customer']['id'];

        if( !empty( $_GPC['address_id'] ) ){
            $id = $_GPC['address_id'];
            $res['ret'] = addressModel::remove( $id, $customer_id );
        }


        echo json_encode( $res );
    }

    public function set_default(){
        $res = [
            'status'=>'1',
            'description'=>'请先登录',
        ];

        $address_id = $_GET['address_id'];
        //todo 取消其他地址的默认选项
        //todo 设置改地址为默认值
        global $_W;
        $customer_id = $_W['customer']['id'];

        $ret = addressModel::setDefault( $address_id, $customer_id );
        if( $ret ) {
            $res['status'] = 0;
            $res['description'] = '设置成功';
        }

        echo json_encode( $res );
    }

    public function get_default(){
        $res = [
            'status'=>'1',
            'description'=>'请先登录',
        ];
        global $_W;
        $customer_id = $_W['customer']['id'];
        $address = addressModel::getDefault( $customer_id );

        echo json_encode( $address );
    }
}