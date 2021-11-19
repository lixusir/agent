<?php
namespace sm_shop\admin\api;
use sm_shop\controller;
use sm_shop\admin\model\brandModel;
class brand extends controller{

    public function page_list(){

        $this->template( 'catalog/brand/list' );

    }

    public function page_edit(){
        $this->template( 'catalog/brand/edit' );
    }

    public function index(){

        global $_GPC;
        $filter = [];
        if( isset($_GPC['name']) ){
            $filter['name'] = $_GPC['name'];
        }
        if( isset($_GPC['status']) ){
            $filter['status'] = $_GPC['status'];
        }


        $list = brandModel::search( $filter );

        echo json_encode( $list );

    }

    public function search(){

        $filter = [];
        if( !empty( $_GET['filter']) ) {
            $filter['name'] = $_GET['filter'];
        }

        $list = categoryModel::get_path_list( $filter );

        echo json_encode( $list );

    }

    public function single(){

        $id = $_GET['id'];

        $brand = brandModel::single( $id );
        $brand['image'] = $brand['img'];
        echo json_encode( $brand );

    }

    public function create(){


        $name = $_POST['name'];
        $sort_order = $_POST['letter_pre'];
        $status = $_POST['status'];
        $image = $_POST['image'];

        $data = [
            'name'      => $name,
            'letter_pre'=> $sort_order,
            'status'    => $status,
            'image'     => $image,
        ];

        $ret = brandModel::insert( $data );

        $res['status'] = 0;
        $res['query'] = $ret;
        echo json_encode( $res );

    }

    public function edit(){

        $brand_id = $_GET['brand_id'];

        $name = $_POST['name'];
        $letter_pre = $_POST['letter_pre'];
        $status = $_POST['status'];
        $image = $_POST['image'];

        $data = [
            'name'      => $name,
            'letter_pre'=> ucfirst( $letter_pre ),
            'status'    => $status,
            'image'     => $image,
        ];
        //todo 修改 category

        $ret = brandModel::update( $brand_id, $data );

        $res['status'] = 0;
        $res['query'] = $ret;
        echo json_encode( $res );

    }

    public function remove(){

        $res= [
            'status'=>0,
        ];

        $brand_id = $_POST['brand_id'];
        if( empty( $brand_id ) ){

            $res= [
                'status'=>1,
                'description'=>'请选中你要删除的品牌',
            ];
            echo json_encode( $res );
            die();
        }

        $res['ret'] = brandModel::remove( $brand_id );

        echo json_encode( $res );

    }


}
