<?php
namespace sm_shop\admin\api;
use sm_shop\controller;

use sm_shop\admin\model\tool\imageModel;
use sm_shop\admin\model\moduleModel;
use sm_shop\admin\model\goodsModel;
use sm_shop\admin\model\categoryModel;


class recommend extends controller{

    public function page_list(){

        $this->template( 'function/recommend/list' );


    }

    public function category(){

        $this->template( 'function/recommend/category' );

    }


    public function index(){


        $recommend = moduleModel::findByKey( 'recommend' );


        if( !empty( $recommend ) ){
            $settings = $recommend['settings'];

            if( !empty( $settings ) ){

                $settings = json_decode( $recommend['settings'], true );


                foreach( $settings['goods_list'] as $goods_id ){

                    $goods = goodsModel::info( $goods_id );
                    if( !empty( $goods ) ){
                        $recommend['goods_list'][] = $goods;
                    }

                }
            }

        }

        $recommend['goods_list'] = empty( $recommend['goods_list'] ) ? [] : $recommend['goods_list'];


        echo json_encode( $recommend );

    }

    public function save(){

        $res = [
            'status'=>0
        ];
        $key = 'recommend';
        $title = $_POST['title'];
        $status = $_POST['status'];
        $goods_list = !empty( $_POST['goods_list'] ) ?
            explode(',', $_POST['goods_list'] ) : [];

        $settings = [
            'goods_list' => $goods_list
        ];

        $data = [
            'key'       => 'recommend',
            'title'     => $title,
            'status'    => $status,
            'settings'  => json_encode( $settings ),
        ];

        moduleModel::deleteByKey( $key );
        moduleModel::save( $data );


        echo json_encode( $res );
    }

    public function home_category(){


        $recommend = moduleModel::findByKey( 'home_category' );
        $recommend['list'] = [];
        if( !empty( $recommend ) ){
            $settings = $recommend['settings'];

            if( !empty( $settings ) ){

                $settings = json_decode( $recommend['settings'], true );


                foreach( $settings['list'] as $category_id ){

                    $cat = categoryModel::single( $category_id );
                    if( !empty( $cat ) ){
                        $recommend['list'][] = $cat;
                    }

                }
            }

        }

        echo json_encode( $recommend );

    }

    public function category_save(){

        $res = [
            'status'=>0
        ];
        $key = 'home_category';
        $title = $_POST['title'];
        $status = $_POST['status'];
        $category_list = !empty( $_POST['list'] ) ?
            explode(',', $_POST['list'] ) : [];

        $settings = [
            'list' => $category_list
        ];

        $data = [
            'key'       => $key,
            'title'     => $title,
            'status'    => $status,
            'settings'  => json_encode( $settings ),
        ];

        moduleModel::deleteByKey( $key );
        moduleModel::save( $data );


        echo json_encode( $res );
    }

}