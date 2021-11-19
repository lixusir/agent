<?php
namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\cartModel;
use sm_shop\model\goodsModel;
use sm_shop\model\tool\imageModel;

class cart extends controller{


    public function __construct()
    {
//        parent::__construct($container);

        //todo 登录校验
        $res = [
            'status'=>'1',
            'description'=>'请先登录',
        ];
//        if( empty($_SESSION['customer_id']) ){
//            echo json_encode( $res );
//            die();
//        }

        global $_W;
        if( empty( $_W['customer'] ) ){
            echo json_encode( $res );
            die();
        }
    }

    public function index(){

        $res = [
            'list'=>[]
        ];
        $cart_ids = null;
        if( isset( $_GET['cart_ids'] ) ){
            $cart_ids = [];
            if( !empty( $_GET['cart_ids'] ) ){
                $cart_ids = explode( ',', $_GET['cart_ids'] );
            }else{
                echo json_encode( $res );
            }
        }
        global $_W;
        if( !empty( $_W['customer'] ) ){
            $customer_id = $_W['customer']['id'];

        }

//        $customer_id = $_SESSION['customer_id'];
        $list = cartModel::get_list( $customer_id, $cart_ids );

//        $goods_total = 0;
//        $order_total = 0;
        foreach( $list as &$item ){
//            $goods_total += $item['price'] * $item['goods_num'];
            $item['price'] = isset( $item['option_price'] ) ? $item['option_price'] : $item['price'];
            $img_url = goodsModel::getImgUrl( $item['goods_id'] );
            $item['image'] = imageModel::resize2( $img_url, 100, 100 );

        }
//        $res['goods_total'] = $goods_total;
//        $res['order_total'] = $goods_total;
        $res['list'] = $list;

        echo json_encode( $res );


    }

    public function filter(){

    }

    public function add(){

        $res = [];
        global $_W;
        //todo 获取 customer_id, 如果没有登录，返回（后期可以在登录前 添加购物车）
        if( !empty( $_W['customer'] ) ){
            $customer_id = $_W['customer']['id'];
        }else{
            $res = [
                'status' => 1,
                'description'=> '请先登录.'
            ];
            echo json_encode( $res );
            die();
        }


        //todo 获取 添加的产品, 以及数量
        $goods_option= '';
        $product_id = $_GET['product_id'];
        if( !empty($_GET['option_id']) ){

            $option = goodsModel::get_goods_spec_obj_by_id( $_GET['option_id'] );

            if( !empty($option) ){
                $goods_option = $option['attr'];
            }
        }

        $number = $_GET['number'];
        $number = intval( $number );
        $ret = cartModel::add( $product_id, $goods_option, $number, $customer_id );

        $res = [
            'status' => 0,
            'description'=> '添加成功',
            'ret'=> $ret
        ];

        //todo 添加到数据库

        echo json_encode( $res );

    }

    public function remove(){

        $cart_id = $_GET['cart_id'];
        $res = cartModel::remove( $cart_id );



        echo json_encode( $res );
    }

    public function change(  ){
        $cart_id = $_GET['cart_id'];
        $number = $_GET['number'];
        $res = [
            'status'=>0
        ];

        cartModel::change( $cart_id, $number );

        echo json_encode( $res );

    }

    /**
     * 根据所选计算价格
     */
    public function calculate(){

        $res = [
            'goods_total'=>0,
            'order_total'=>0,
        ];
        $cart_ids = null;
        $cart_ids = [];
        if( !empty( $_GET['cart_ids'] ) ){
            $cart_ids = explode( ',', $_GET['cart_ids'] );
        }else{
            echo json_encode( $res );
            die();
        }

        global $_W;
        $customer_id = $_W['customer']['id'];
        $list = cartModel::get_list( $customer_id, $cart_ids );

        $goods_total = 0;
        $order_total = 0;
        foreach( $list as &$item ){

            $price = !empty($item['option_price'])?$item['option_price']:$item['price'];

//            $item['img_url'] = goodsModel::getImgUrl( $item['goods_id'] );
            $goods_total += $price * $item['goods_num'];

        }
        $res['goods_total'] = round($goods_total,2);
        $res['order_total'] = round($goods_total,2);
//        $res['list'] = $list;

        echo json_encode( $res );

    }

}