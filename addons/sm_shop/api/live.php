<?php
namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\settingModel;
class live extends controller{


    public function index(){

        $category = new categoryModel();
        $cat = $category->get_list();

        echo json_encode( $cat );
    }

    // 获取直播地址
    public function get_live_setting(){

        $live_setting = settingModel::get_live();

        echo json_encode( $live_setting );
    }


}