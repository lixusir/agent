<?php
namespace sm_shop\admin\api;
use sm_shop\controller;
use sm_shop\admin\model\expressModel;

class express extends controller{

    // 快递列表
    public function index(){


        $list = expressModel::get_list();

        echo json_encode( $list );



    }

}