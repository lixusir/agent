<?php
namespace sm_shop\admin\api;
use sm_shop\admin\model\customerModel;
use sm_shop\controller;

class customer extends controller{


    public function index(){


        global $_GPC;
        if( isset($_GPC['name']) ){
            $filter['name'] = $_GPC['name'];
        }
        if( isset($_GPC['status']) ){
            $filter['status'] = $_GPC['status'];
        }
        if( isset($_GPC['telephone']) ){
            $filter['telephone'] = $_GPC['telephone'];
        }

        if( isset($_GPC['customer_group_id']) ){
            $filter['customer_group_id'] = $_GPC['customer_group_id'];
        }

        $list = customerModel::get_list( $filter );

        echo json_encode( $list );

    }

    public function page_list(){

        $this->template( 'customer/list' );

    }

    public function page_edit(){

        $this->template( 'customer/edit' );

    }

    public function single(){

        $customer_id = $id = $_GET['id'];
        $single = customerModel::single( $customer_id );

        echo json_encode( $single );
    }

    public function edit(){

        $id = $_GET['id'];

        $data = [
            'customer_group_id'=> $_POST['customer_group_id'],
            'remark'=> $_POST['remark']?:''
        ];

        $ret = customerModel::edit( $id, $data );
        $res = [
            'status'=>0,
            'ret'=>$ret
        ];

        echo json_encode( $res );

    }


}