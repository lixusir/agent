<?php
namespace sm_shop\admin\api;
use sm_shop\controller;
use sm_shop\admin\model\categoryModel;
class category extends controller{

    public function page_list(){

        $this->template( 'catalog/category/list' );

    }

    public function page_edit(){
        $this->template( 'catalog/category/edit' );
    }

    public function index(){

        global $_GPC;
        if( isset($_GPC['name']) ){
            $filter['name'] = $_GPC['name'];
        }
        if( isset($_GPC['status']) ){
            $filter['status'] = $_GPC['status'];
        }
        if( isset($_GPC['category_ids']) ){
            $filter['category_ids'] = $_GPC['category_ids'];
        }

        $list = categoryModel::get_path_list( $filter );

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

        $category = categoryModel::single( $id );

        $paths = categoryModel::get_category_path( $id );

        $path_name = [];
        foreach( $paths as $path ){

            if( $path['path_id'] == $id ){
                break;
            }
            $path_name[] = $path['name'];
        }

        $category['path'] = implode($path_name, '>');
        echo json_encode( $category );

    }

    public function edit(){

        $category_id = $_GET['category_id'];

        $parent_id = $_POST['parent_id'];

        $name = $_POST['name'];
        $sort_order = $_POST['sort_order'];
        $status = $_POST['status'];
        $image = $_POST['image'];



        $data = [
            'name'      => $name,
            'sort_order'=> $sort_order,
            'status'    => $status,
            'parent_id' => $parent_id,
            'image'     => $image,
        ];
        //todo 修改 category

        $ret = categoryModel::edit( $category_id, $data );

        //todo 找到 category_path 关系, 删除并重新，并插入path
        categoryModel::remove_category_path( $category_id);
        categoryModel::set_category_path( $category_id, $parent_id );
        $res['status'] = 0;
        $res['query'] = $ret;
        echo json_encode( $res );

    }

    public function remove(){

        $res= [
            'status'=>0,
        ];

        $category_id = $_POST['category_id'];
        if( empty( $category_id ) ){

            $res= [
                'status'=>1,
                'description'=>'请选中你要删除的分类',
            ];
            echo json_encode( $res );
            die();
        }

        //todo 查看是否有子分类，有则报错
        $parent = categoryModel::hasChildCategory( $category_id );

        if( !empty($parent) ){

            $res['status'] = 1;
            $res['category_id'] = $category_id;
            $res['description'] = '分类下有子分类,无法删除';
        }else{

            //todo 查看其下是否挂有产品， 有则报错

            //todo 删除分类
            categoryModel::remove( $category_id );
            categoryModel::remove_category_path( $category_id );
        }

        echo json_encode( $res );

    }

    public function create(){

        $parent_id = $_POST['parent_id'];

        $name = $_POST['name'];
        $sort_order = $_POST['sort_order'];
        $status = $_POST['status'];
        $image = $_POST['image'];



        $data = [
            'name'=> $name,
            'sort_order'=> $sort_order,
            'status'=> $status,
            'image'=> $image,
            'parent_id'=> $parent_id,
        ];
        //todo 修改 category

        $category_id = categoryModel::create(  $data );

        $res = [
            'status'=>0,
            'category_id'=>$category_id
        ];

        if( $category_id ){
            //todo 找到 category_path 关系, 删除并重新，并插入path
            categoryModel::set_category_path( $category_id, $parent_id );
        }

        echo json_encode( $res );
    }


}
