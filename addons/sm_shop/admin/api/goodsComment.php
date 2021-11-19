<?php
namespace sm_shop\admin\api;
use sm_shop\admin\model\goodsModel;
use sm_shop\controller;
use sm_shop\admin\model\commentModel;
use sm_shop\model\tool\imageModel;

class goodsComment extends controller
{

    public function page_list()
    {

        $this->template('catalog/comment/list');

    }

    public function page_edit()
    {
        $this->template('catalog/comment/edit');
    }

    public function get_list(){


        global $_GPC;

        if( isset($_GPC['author']) ){
            $filter['author'] = $_GPC['author'];
        }

        if( isset($_GPC['status']) ){
            $filter['status'] = $_GPC['status'];
        }

        if( isset($_GPC['date']) ){
            $filter['date'] = $_GPC['date'];
        }

        if( isset($_GPC['goods_name']) ){
            $filter['goods_name'] = $_GPC['goods_name'];
        }

        if( isset($_GPC['goods_id']) ){
            $filter['goods_id'] = $_GPC['goods_id'];
        }

        $list = commentModel::get_list( $filter );

        foreach( $list as &$comment ){

            $comment['goods_image'] = imageModel::resize2($comment['goods_image'],50,50);
            $comment['date'] = Date('Y-m-d', strtotime($comment['date']));

        }

        echo json_encode( $list );


    }

    public function edit(){

        global $_GPC, $_W;
        $comment_id = $_GPC['id'];
        $res = [
            'status'=>0,
        ];

        $images = [];
        $img_path_json = [];
        // base64 放到json 中解码错误
/*        if( !empty( $_POST['images'] ) ){
            $img_show = $_POST['images'] ;
            $img_show = json_decode( $img_show, true );

            foreach( $img_show as $img ){

                if( !empty( $img['base64'] ) ){
                    $base64 = $img['base64'];
                    $base64 = urldecode( $base64 );
                    $base64 = htmlspecialchars_decode( $base64 );
                    $result = imageModel::save_base64( $base64 );
                    if( $result ) {
                        $img_path_json[] = $result;
                    }
                } else if( !empty( $img['path'] ) ){
                    $img_path_json[] = $img['path'];
                }
            }
        }*/
        if( !empty( $_POST['images_0'] ) ){
            $images_0 = $_POST['images_0'] ;
            $images_0 = htmlspecialchars_decode( $images_0 );
            if( imageModel::is_base64( $images_0) ){

                $result = imageModel::save_base64( $images_0,time().'-0' );
                if( $result ) {
                    $img_path_json[] = $result;
                }
            }else {
                $img_path_json[] = $images_0;
            }
        }
        if( !empty( $_POST['images_1'] ) ){
            $images_1 = $_POST['images_1'] ;
            $images_1 = htmlspecialchars_decode( $images_1 );
            if( imageModel::is_base64( $images_1) ){

                $result = imageModel::save_base64( $images_1, time().'-1' );
                if( $result ) {
                    $img_path_json[] = $result;
                }
            }else {
                $img_path_json[] = $images_1;
            }
        }
        if( !empty( $_POST['images_2'] ) ){
            $images_2 = $_POST['images_2'] ;
            $images_2 = htmlspecialchars_decode( $images_2 );
            if( imageModel::is_base64( $images_2) ){

                $result = imageModel::save_base64( $images_2, time().'-2' );
                if( $result ) {
                    $img_path_json[] = $result;
                }
            }else {
                $img_path_json[] = $images_2;
            }
        }

        $data = [
            'author'        => $_GPC['author'],
            'avatar'        => $_GPC['avatar'],
            'customer_id'   => $_GPC['customer_id']?:0,
            'goods_id'      => $_GPC['goods_id'],
            'content'       => $_GPC['content'],
            'score'         => $_GPC['score'],
            'images'        => json_encode( $img_path_json, true),
            'date'          => $_GPC['date']?:Date('Y-m-d'),
            'status'        => $_GPC['status']
        ];

        $edit = commentModel::edit( $comment_id, $data );

        if( !$edit){
            $res['status'] = 1;
            $res['description'] = '保存失败';
        }

        $res['edit'] = $edit;
        echo json_encode( $res );

    }

    public function single(){

        $id = $_GET['id'];
        $json = [
            'status'=>0
        ];

        if( empty( $id ) ){
            $json['status'] = 1;
            echo json_encode( $json );
        }

        $comment = commentModel::single( $id );

        $comment['date'] = Date('Y-m-d', strtotime( $comment['date'] ) );
        $comment['goods'] = goodsModel::info( $comment['goods_id'] );

        $comment['img_show'] = [];
        if( !empty($comment['images']) ){
            $images = json_decode( $comment['images'], true );
            foreach( $images as $img ){
                $comment['img_show'][] = [
                    'url' => imageModel::resize2( $img ),
                    'path' => $img,
                ];
            }
        }


        echo json_encode( $comment );

    }

    public function remove(){

        $ids = $_POST['ids'];

        $list = commentModel::remove( $ids );

    }

}
