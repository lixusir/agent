<?php
//namespace api;
//use controller;
//
//use model\tool\imageModel;
//use model\moduleModel;
//use model\goodsModel;


namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\tool\imageModel;
use sm_shop\model\moduleModel;
use sm_shop\model\goodsModel;
use sm_shop\model\categoryModel;

class recommend extends controller{



    public function index(){

        global $_GPC;

        $img_width = !empty( $_GPC['img_width'] ) ? $_GPC['img_width'] : 280;
        $img_height = !empty( $_GPC['img_height'] ) ? $_GPC['img_height'] : 380;
        $recommend = moduleModel::getRecommend();



        if( !empty($recommend) && !empty($recommend['settings'])  ){
            $settings = json_decode( $recommend['settings'], true );
            $goods_list = !empty($settings['goods_list'])? $settings['goods_list']: [];

            $recommend['goods_list'] = [];
            foreach ( $goods_list as $goods_id ){

                $goods = goodsModel::detail( $goods_id );

                if( empty( $goods ) ){
                    continue;
                }

                if( !empty($goods['image'] ) ){

                    $goods['image'] = imageModel::resize2( $goods['image'], $img_width, $img_height );
//                    $goods['image'] = tomedia(  $goods['image'] );
                }else{
//                    $goods['image'] = tomedia( 'images/global/nopic.jpg' );
                    $goods['image'] = imageModel::resize2(  'images/global/nopic.jpg', $img_width,$img_height );
                }

                if( !empty( $goods ) ){
                    $recommend['goods_list'][] = $goods;
                }

            }
        }

        $recommend['goods_list'] = !empty( $recommend['goods_list'] ) ? $recommend['goods_list'] : [];
        echo json_encode( $recommend );

    }

    public function home_category(){

        $img_width = 100;
        $img_height = 100;
        $recommend = moduleModel::home_category();
        $recommend['list'] = [];

        if( !empty($recommend) && !empty($recommend['settings']) ){

            $settings = json_decode( $recommend['settings'], true );
            $category_list = !empty($settings['list'])? $settings['list']: [];

            foreach ( $category_list as $category_id ){

                $category = categoryModel::single( $category_id );

                if( empty( $category ) ){
                    continue;
                }

                if( !empty($category['image'] ) ){
                    $category['image'] = imageModel::resize2( $category['image'], $img_width, $img_height );
                }else{
                    $category['image'] = imageModel::resize2(  'images/global/nopic.jpg', $img_width,$img_height );
                }

                if( !empty( $category ) ){
                    $recommend['list'][] = $category;
                }

            }
        }

        echo json_encode( $recommend );
    }


}