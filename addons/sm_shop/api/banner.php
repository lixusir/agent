<?php
//namespace api;
//use controller;
//
//use model\tool\imageModel;
//use model\bannerModel;

namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\bannerModel;
use sm_shop\model\tool\imageModel;

class banner extends controller{

    public function index(){
        global $_W;
        global $_GPC;

//        $img_width = !empty( $_GPC['img_width'] ) ? $_GPC['img_width'] : 750;
//        $img_height = !empty( $_GPC['img_height'] ) ? $_GPC['img_height'] : 370;

        $banner = [];
        if( !empty( $_GET['name'] )){
            $name = $_GET['name'];
            $banner = bannerModel::findByName( $name );
            if( !empty( $banner ) ){
                $image_list = bannerModel::findImageById( $banner['banner_id'] );
                foreach( $image_list as &$image_obj ){
                    $image_obj['image'] = imageModel::resize2( $image_obj['image'], $banner['image_width'],$banner['image_height'] );
                }
                $banner['image_list'] = $image_list;
                $banner['img_width'] = $banner['image_width'];
                $banner['img_height'] = $banner['image_height'];
            }

        }

        echo json_encode( $banner );

    }




}