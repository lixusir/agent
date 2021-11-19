<?php
namespace sm_shop\admin\api;
use sm_shop\controller;
use sm_shop\admin\model\categoryModel;
use sm_shop\admin\model\goodsModel;
use sm_shop\model\tool\imageModel;

class goods extends controller{

    public function page_list(){

        $this->template( 'catalog/product/list' );

    }

    public function page_edit(){
        $this->template( 'catalog/product/edit' );
    }


    public function get_list(){


        global $_GPC;

        if( isset($_GPC['name']) ){
            $filter['name'] = $_GPC['name'];
        }

        if( isset($_GPC['status']) ){
            $filter['status'] = $_GPC['status'];
        }

        if( isset($_GPC['price']) ){
            $filter['min-price'] = explode('-', $_GPC['price'])[0];
            $filter['max-price'] = explode('-', $_GPC['price'])[1];
        }

        if( isset($_GPC['category_name']) ){
            $filter['category_name'] = $_GPC['category_name'];
        }
        if( isset($_GPC['brand_name']) ){
            $filter['brand_name'] = $_GPC['brand_name'];
        }

        $list = goodsModel::get_list( $filter );

        foreach( $list as &$goods ){


//            $goods['image'] = $goods['image'];
            $goods['image'] = imageModel::resize2($goods['image'],100,100);

        }
        echo json_encode( $list );


    }


    public function info(){

        global $_W;
        $goods_id = $_GET['goods_id'];

        $res = [
            'status'=>0,
        ];

        $info = goodsModel::info( $goods_id );


        if( empty($info ) ){

            $res['status'] = 1;
            $res['description'] = '商品不存在';

        }

        $goods_images = goodsModel::get_goods_images( $goods_id );
        $info['goods_images'] = $goods_images;

        $info['description'] = html_entity_decode( $info['description'], ENT_QUOTES, 'UTF-8');
        $info['category'] = [];
        $categories = goodsModel::get_goods_category( $goods_id );
        if( !empty( $categories ) ){

            $filter = [];
            $category_ids = [];
            foreach( $categories as $cat ){
                $category_ids[] = $cat['category_id'];
            }
            $info['category'] = categoryModel::get_path_list( ['category_ids' => $category_ids] );
        }


        $info['pindan'] = goodsModel::get_goods_pindan( $goods_id );

        $info['cron_goods'] = $this->url_host . '/app/index.php?c=entry&a=wxapp&m=sm_shop&do=api&i=' . $_W['uniacid'] . '&r=cron.goods.addSaleNumber';

        $res['product'] = $info;



        echo json_encode( $res );

    }

    public function edit(){

        global $_GPC;
        $goods_id = $_GPC['goods_id'];
        $res = [
            'status'=>0,
        ];

        $goods_images = [];
        if( !empty( $_GPC['goods_images'] ) ){
            $_GPC['goods_images'] = htmlspecialchars_decode($_GPC['goods_images']);
            $goods_images = json_decode( $_GPC['goods_images'], true );
        }

        $categories = [];
        if( !empty( $_GPC['category'] ) ){
            $categories = $_GPC['category'];
        }

        $pindan = [];
        if( !empty( $_GPC['pindan'] ) ){
            $pindan = $_GPC['pindan'];
        }

        $data = [
            'name'          => $_GPC['name'],
            'brand_id'      => $_GPC['brand_id'],
            'price'         => $_GPC['price'],
            'special'       => $_GPC['special'],
            'sale'          => $_GPC['sale'],
            'sale_add'      => $_GPC['sale_add'],
            'model'         => $_GPC['model'],
//            'description'   => strip_tags( htmlentities( $_GPC['description'] ,ENT_QUOTES, 'UTF-8' )),
            'description'   => $_GPC['description'],
            'goods_images'  => $goods_images,
            'image'         => $_GPC['image'],
            'status'        => $_GPC['status'],
            'category'      => $categories,
            'pindan'        => $pindan,
        ];



        $edit = goodsModel::edit( $goods_id, $data );

        $res['edit'] = $edit;
        echo json_encode( $res );

    }


    public function remove(){

        $ids = $_POST['ids'];

        $list = goodsModel::remove( $ids );

    }

    public function search(){

        $filter = [];
        if( !empty( $_GET['filter']) ) {
            $filter['name'] = $_GET['filter'];
        }

        $list = goodsModel::search( $filter );

        echo json_encode( $list );

    }

    public function save_specification(){

        $res['status'] = 0;

        $goods_id = $_GET['goods_id'];

        if( !empty( $_POST['specification'] ) ){
            $specification = json_decode( $_POST['specification'], true);
        }

        if( !empty( $_POST['spec_obj'] ) ){
            $spec_obj = json_decode( $_POST['spec_obj'], true );
        }

//        print_r( $spec_obj );

        goodsModel::delete_spec( $goods_id );

        foreach( $specification as $spec ){
            goodsModel::save_spec( $goods_id, $spec );
        }

        $res['ret'] = goodsModel::save_spec_obj( $goods_id, $spec_obj );

        echo json_encode( $res );

    }

    public function specification(){

        $goods_id = $_GET['goods_id'];

        $res = [
            'status'=>0
        ];
        $spec = goodsModel::get_goods_specification( $goods_id );
        $res['spec_object'] = goodsModel::get_goods_spec_obj( $goods_id );

        $specification = [];
        foreach( $spec as $item ){
            $specification[ $item['name'] ]['name'] = $item['name'];
            $specification[ $item['name'] ]['values'][] = [
                'name'=>$item['value']
            ];
        }

        $res['specification'] = $specification;

        echo json_encode( $res );

    }

}