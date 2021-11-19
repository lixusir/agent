<?php
namespace sm_shop\admin\api;
use sm_shop\controller;
use sm_shop\admin\model\bannerModel;

class banner extends controller{

    public function page_list(){

        $this->template( 'design/banner/list' );

    }

    public function page_edit(){

        $this->template( 'design/banner/edit' );

    }

    public function index(){

        $banner_list = bannerModel::get_list();


        echo json_encode( $banner_list );


    }

    public function single(  ){

        $banner_id = $_GET['banner_id'];
        $banner_info = bannerModel::get_banner_info( $banner_id );
        $banner_info['image_list'] = bannerModel::get_banner_image_list( $banner_id );

        echo json_encode( $banner_info );
    }

    public function create(){

        $name = $_POST['name'];

        $status = $_POST['status'];
        $image_width = $_POST['image_width'];
        $image_height = $_POST['image_height'];


        $image_list = [];

        if( isset( $_POST['image_list'] ) ){

            $json = json_decode( $_POST['image_list'], true );

            if( is_array( $json ) ){
                foreach( $json as $image ){

                    $image_list[] = [
                        'title'     => $image['title'],
                        'link'      => $image['link'],
                        'image'     => $image['image'],
                        'sort_order'=> $image['sort_order'],
                    ];
                }
            }
        }

        $data = [
            'name'      => $name,
            'status'    => $status,
            'image_width'    => $image_width,
            'image_height'    => $image_height,
//            'image_list'=> $image_list,
        ];
        //todo 修改 category

        $banner_id = bannerModel::create(  $data );

        bannerModel::banner_image_set( $banner_id, $image_list );


        $res['status'] = 0;
        echo json_encode( $res );
    }

    public function edit(){

        $banner_id = $_GET['banner_id'];

        $name = $_POST['name'];

        $status = $_POST['status'];
        $image_width = $_POST['image_width'];
        $image_height = $_POST['image_height'];

        $image_list = [];

        if( isset( $_POST['image_list'] ) ){

            $json = json_decode( $_POST['image_list'], true );

            if( is_array( $json ) ){
                foreach( $json as $image ){

                    $image_list[] = [
                        'title'     => $image['title'],
                        'link'      => $image['link'],
                        'image'     => $image['image'],
                        'sort_order'=> $image['sort_order'],
                    ];
                }
            }
        }

        $data = [
            'name'      => $name,
            'status'    => $status,
            'image_width'    => $image_width,
            'image_height'    => $image_height,
//            'image_list'=> $image_list,
        ];
        //todo 修改 category

        $ret = bannerModel::edit( $banner_id, $data );


        //todo 找到 category_path 关系, 删除并重新，并插入path
        bannerModel::banner_image_remove( $banner_id );
        bannerModel::banner_image_set( $banner_id, $image_list );


        $res['status'] = 0;
        $res['query'] = $ret;
        echo json_encode( $res );

    }


    public function delete(){

        $res = [
            'status'=>0
        ];
        if(isset($_GET['banner_id']) ){
            $banner_id = $_GET['banner_id'];
            $ret = bannerModel::remove( $banner_id );
            $ret = bannerModel::image_list_remove( $banner_id );

        }else{
            $res['status'] = 1;
            $res['description'] = '参数不正确';
        }


        echo json_encode( $res );

    }

}