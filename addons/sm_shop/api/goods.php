<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\7\17 0017
 * Time: 10:03
 */
namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\goodsModel;
use sm_shop\model\commentModel;
use sm_shop\model\tool\imageModel;

class goods extends controller{


/*    public function __construct($container)
    {
        parent::__construct($container);
//        header('Access-Control-Allow-Origin:*');
    }*/

    public function index(){

        global $_GPC;
        $key = $_GPC['key'];

        $category_id = $_GPC['category'];
        $brand_id = $_GPC['brand_id'];

        $params = [
            'key'=>$key,
            'category_id'=>$category_id,
            'brand_id'=>$brand_id,
        ];

        $list = goodsModel::search( $params );

        foreach( $list as &$goods ){

            if(!empty( $goods['image'] ) ){

                $goods['image'] = imageModel::resize2( $goods['image'], 228,228 );

            }else{
                $goods['image'] = imageModel::resize2( '/images/global/nopic.jpg', 228,228 );
            }


        }

        echo json_encode( $list );


    }


    public function info(){

        global $_GPC;
        $goods_id = $_GPC['goods_id'];

        $goods = goodsModel::detail( $goods_id );
        $goods['description'] = html_entity_decode( $goods['description'] );
        $goods['image_width'] = 400;
        $goods['image_height'] = 300;
        $goods['image_list'] = goodsModel::image_list( $goods_id );
        $goods['image'] = imageModel::resize2( $goods['image'], 300, 300 );
        if( !empty( $goods['image_list'] ) ){
//            $imageMdl = new imageModel;
            foreach( $goods['image_list'] as &$img ){

                $img['url'] = imageModel::resize2( $img['url'], $goods['image_width'], $goods['image_height'] );
//                $img['url'] = tomedia( $img['url'] );


            }
        }

        $goods['pindan'] = goodsModel::get_pindan_info( $goods_id );

        


        echo json_encode( $goods );

    }

    //获取 商品规格
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

    /**
     * 用来进行测试， 之后会删除掉
     */
/*    public function add(){
        global $_GPC;
        $price = $_GPC['price'];
        $name = $_GPC['name'];
        $r = goodsModel::add(  $name, $price );

        $res = [
            'status' => 0,
            'description'=> '添加成功'
        ];

        //todo 添加到数据库

        echo json_encode( $res );

    }*/

    /**
     * 用来进行测试， 之后会删除掉
     */
/*    public function delete(){

        global $_GPC;
        $res = [
            'status' => 0,
            'description'=> '删除成功'
        ];

        $id = $_GPC['id'];

        $r = goodsModel::delete( $id );


        if( empty( $r->num_rows ) ){
            $res = [
                'status' => 1,
                'description'=> '删除失败没找到相关数据'
            ];
        }


        //todo 添加到数据库

        echo json_encode( $res );

    }*/

    public function buy(){
        global $_GPC;
        $res = [
            'status' => 0
        ];

        if( empty( $_GPC['product_id'] ) ){

            $res['status'] = 1;
            $res['description'] = '请选定商品';
            die( json_encode( $res ) );

        }

        $goods_id = $_GPC['product_id'];
        $goods = goodsModel::detail( $goods_id );

        if( empty( $goods ) ){

            $res['status'] = 1;
            $res['description'] = '商品不存在';
            die( json_encode( $res ) );

        }

        $goods_num = $_GPC['number'];

        $_SESSION['buy'] = [
            'goods_id' => $goods_id,
            'goods_num' => $goods_num
        ];

        echo json_encode( $res );
    }

    // 评论列表
    public function comment(){

        global $_GPC;

        $list = [];
        $goods_id = $_GPC['goods_id'];

        if( empty( $goods_id ) ){
            echo json_encode( $list );
            die();
        }

        $list = commentModel::get_list( $goods_id );

        foreach( $list as &$item ){
            $item['date'] = Date('Y-m-d', strtotime($item['date']));

            if( !empty( $item['avatar']) ){
                $item['avatar'] = imageModel::resize2( $item['avatar'], 100,100 );
            }else if( !empty( $item['customer_avatar']) ){
                $item['avatar'] = $item['customer_avatar'];
            }else{
                $http_type = (
                    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                        && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                )
                    ? 'https://' : 'http://';
                $item['avatar'] = $http_type . $_SERVER['SERVER_NAME'] . '/addons/sm_shop/assets/image/avatar/profile.png';
            }

            $item['img_show'] = [];
            $images = !empty( $item['images'] ) ? json_decode( $item['images'], true ) : [];
            if( !empty( $images ) ){

                foreach( $images as $v ){
                    $item['img_show'][] = imageModel::resize2( $v, 100, 100);
                }
            }

        }
        echo json_encode( $list );

    }
}