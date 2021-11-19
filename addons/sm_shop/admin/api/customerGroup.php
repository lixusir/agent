<?php
namespace sm_shop\admin\api;
use sm_shop\admin\model\customerModel;
use sm_shop\controller;
use sm_shop\admin\model\customerGroupModel;

class customerGroup extends controller{


    public function index(){


        global $_GPC;
        if( isset($_GPC['name']) ){
            $filter['name'] = $_GPC['name'];
        }


        $list = customerGroupModel::get_list( $filter );

        echo json_encode( $list );

    }

    public function page_list(){

        $this->template( 'customer/group/list' );

    }

    public function page_edit(){

        $this->template( 'customer/group/edit' );

    }

    public function single(){

        $customer_group_id = $_GET['id'];
        $single = customerGroupModel::single( $customer_group_id );

        echo json_encode( $single );
    }

    public function edit(){

        $res = [
            'status'=>0
        ];
        $id = $_GET['id'] ?: 0;

        $name = $_POST['name'];
        $description = $_POST['description'];
        $is_default = $_POST['is_default'];


        //todo 检测用户组名
        $filter = [ 'name' => $name ];
        $cg = customerGroupModel::get_list( $filter );
        foreach ($cg as $group ){

            if( $group['id'] != $id ){

                $res['status'] = 1;
                $res['description'] = '用户组名称已经存在';
                echo json_encode( $res );
                die();

            }
        }

        $data = [
            'name'=> $name,
            'description'=> $description,
            'is_default'=> $is_default,
        ];
        //todo 修改 category
        if( empty( $id ) ){
            $id = customerGroupModel::create(  $data );
            $res['status'] = 0;
            $res['id'] = $id;
        }else{

            $ret = customerGroupModel::edit( $id, $data );
            $res = [
                'status'=>0,
                'ret'=>$ret
            ];

        }

        echo json_encode( $res );

    }


}