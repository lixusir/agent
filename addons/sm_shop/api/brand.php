<?php
//namespace api;
//use controller;
//
//use model\tool\imageModel;
//use model\bannerModel;

namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\brandModel;
use sm_shop\model\tool\imageModel;

class brand extends controller{

    public function index(){

        $list = [];
        $list = brandModel::get_list();
        foreach( $list as &$v ){
            $v['img'] = imageModel::resize2( $v['img'], 200, 200 );
        }
        echo json_encode( $list );

    }




}