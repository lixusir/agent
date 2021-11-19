<?php
namespace sm_shop\admin\api;
use sm_shop\controller;
use sm_shop\admin\model\posterModel;

class poster extends controller{


    public function page_list(){

        $this->template( 'function/poster/list' );

    }

    public function page_edit(){

        $this->template( 'function/poster/edit' );

    }

    public function index(){

        global $_GPC;
        if( isset($_GPC['name']) ){
            $filter['name'] = $_GPC['name'];
        }
        if( isset($_GPC['status']) ){
            $filter['status'] = $_GPC['status'];
        }

        $list = posterModel::get_list( $filter );

        echo json_encode( $list );
    }

    public function get_info(){

        global $_GPC;
        $info = posterModel::get_info( $_GPC['poster_id'] );

        echo json_encode( $info );
    }

    public function edit(){

        global $_GPC;

        $res = [
            'status'=>0
        ];
        if( !empty( $_GPC['poster_id'] ) ){
            $poster_id = $_GPC['poster_id'];
        }

        if( empty( $_GPC['name'] ) ){
            $res['description'] = '请输入名字';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }
        if( empty( $_GPC['bg_img'] ) ){
            $res['description'] = '请设置背景图片';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }

        if( empty( $_GPC['reply'] ) ){
            $res['description'] = '请设置回复关键字';
            $res['status'] = 1;
            echo json_encode( $res );
            die();
        }

        $data = [
            'name'=>$_GPC['name'],
            'reply'=>$_GPC['reply'],
            'bg_img'=> $_GPC['bg_img'] ,
            'design'=>$_POST['design'],
            'date_start'=>$_GPC['date_start'],
            'date_end'=>$_GPC['date_end'],
            'status'=>isset($_GPC['status']) ? $_GPC['status'] : 1,
        ];



        //todo 修改

        if( !empty($poster_id) ){
            $ret = posterModel::edit( $poster_id, $data );
        }else{
            $ret = posterModel::add(  $data );
        }


        // todo 添加 回复关键字

        $res['query'] = $ret;
        echo json_encode( $res );

    }

    public function remove(){

        $res= [
            'status'=>0,
        ];

        $poster_id = $_POST['poster_id'];
        if( empty( $poster_id ) ){

            $res= [
                'status'=>1,
                'description'=>'请选中你要删除的海报',
            ];
            echo json_encode( $res );
            die();
        }

        posterModel::remove( $poster_id );

        echo json_encode( $res );

    }

    public function download_avatar(){

//        $image_new = posterModel::download_avatar();
//
//        echo $image_new;



    }

}