<?php

namespace sm_shop\api\checkout;
use sm_shop\controller;
use sm_shop\model;
use sm_shop\model\addressModel;
use sm_shop\model\cartModel;
use sm_shop\model\goodsModel;
use sm_shop\model\shippingModel;
use sm_shop\model\paymentModel;
use sm_shop\model\orderModel;
use sm_shop\model\tool\imageModel;

class order extends controller{

    public function __construct()
    {

        //todo 登录校验
        $res = [
            'status'=>'1',
            'description'=>'请先登录',
        ];

        global $_W;
        if( empty( $_W['customer'] ) ){
            echo json_encode( $res );
            die();
        }
    }

    public function create(){

//        $info = $_POST;
        $res = [
            'status'=>0
        ];

        $checkout = $_SESSION['checkout'];


        $order_data = [];
        global $_W;
        $order_data['customer_id'] = $_W['customer']['id'];

        $order_data['goods'] = [];
        if( !empty( $checkout['goods'] ) ){
            foreach( $checkout['goods'] as $key => $goods ){
                $order_data['goods'][$goods['goods_id']] = [
                    'goods_id' => $goods['goods_id'],
                    'goods_name' => $goods['name'],
                    'goods_num' => $goods['goods_num'],
                    'goods_price' => $goods['price'],
                    'goods_total' => $goods['total_price'],
                ];

            }
        }else{
            $res['status'] =1;
            $res['description'] = '商品不存在';
            echo json_encode( $res );
            die();
        }

        if( empty($checkout['address_id']) ){

            $res['status'] = 1;
            $res['description'] = '请填写地址';
            echo json_encode( $res );
            die();
        }


        $address = addressModel::single( $checkout['address_id'], $_W['customer']['id'] );

        $order_data['order_no'] = time();

        $order_data['shipping_method'] = $checkout['shipping_code']?:'package';
        $order_data['payment_method'] = $checkout['payment_code']?:'wechat';
        $order_data['is_payed'] = 0;
        $order_data['address_name'] = $address['name'];
        $order_data['address_phone'] = $address['telephone'];
        $order_data['address_province'] = $address['province_name'];
        $order_data['address_city'] = $address['city_name'];
        $order_data['address_area'] = $address['area_name'];
        $order_data['address_detail'] = $address['detail_address'];
        $order_data['message'] = !empty( $_POST['message'] ) ? $_POST['message'] : '';
        $order_data['status'] = 'pending';

        $order_data['total'] = $checkout['total'];
        $order_data['goods'] = $checkout['goods'];

        //todo 添加商品



        $res['order_id'] = orderModel::create( $order_data );

        if( empty($res['order_id']) ){

            $res['status'] = 1;
            $res['description'] = '订单生成失败';

        }else{

            //todo 删除购物车相关产品
            if( !empty( $_SESSION['checkout']['cart'] ) ){
                cartModel::removeMore($_SESSION['checkout']['cart'] );
            }

            unset($_SESSION['checkout']);
        }


        echo json_encode( $res );



    }

    /**
     * 小程序创建订单调用
     */
    public function create_by_xcx(){

        global $_W, $_GPC;

        $res = [
            'status'=>0
        ];

        $checkout =[
            'goods'         => '',
            'address_id'    => '',
            'message'       => ''
        ];

        $order_data = [];
        global $_W;

        $order_data['customer_id'] = $_W['customer']['id'];
        $post = [];
        if( $_GPC['__input'] ){
            $post = $_GPC['__input'];
        }else{
            $post = $_GPC;
        }
        $order_data['goods'] = json_decode( $post['goods'], true);


        if( empty($post['address']) ){

            $res['status'] = 1;
            $res['description'] = '请填写地址';
            echo json_encode( $res );
            die();
        }

        $address = json_decode( $post['address'], true );

        if( !is_array( $address ) ){
            $res['status'] = 1;
            $res['description'] = '请填写地址';
            echo json_encode( $res );
            die();
        }
        $order_data['order_no'] = time();
        $order_data['shipping_method'] = !empty( $post['shipping_method'] ) ? $post['shipping_method'] : 'package';
        $order_data['payment_method'] = 'wechat';
        $order_data['is_payed'] = 0;
        $order_data['address_name'] = $address['name'];
        $order_data['address_phone'] = $address['telephone'];
        $order_data['address_province'] = $address['province_name'];
        $order_data['address_city'] = $address['city_name'];
        $order_data['address_area'] = $address['area_name'];
        $order_data['address_detail'] = $address['detail_address'];
        $order_data['message'] = !empty( $post['message'] ) ? $post['message'] : '';
        $order_data['status'] = 'pending';

        if( $order_data['shipping_method'] == 'pickup' ){
            if( empty( $post['pickup_person'] ) || empty( $post['pickup_phone'] ) ){
                $res['status'] = 1;
                $res['description'] = '请填写收货人姓名和电话';
                echo json_encode( $res );
                die();
            }
            if( empty( $post['pickup'] ) ){
                $res['status'] = 1;
                $res['description'] = '请选择自提点';
                echo json_encode( $res );
                die();
            }
            $order_data['address_name'] = $post['pickup_person'] ;
            $order_data['address_phone'] = $post['pickup_phone'] ;
            $order_data['pickup'] = json_decode( $post['pickup'], true );
        }


        $order_data['is_pindan'] = !empty( $post['is_pindan'] ) ? $post['is_pindan'] : 0 ;
        $order_data['pindan_order'] = !empty( $post['pindan_order'] ) ? $post['pindan_order'] : 0 ;


        orderModel::calculate( $order_data );

        //todo 添加商品


        $res['order_id'] = orderModel::create( $order_data );

        if( empty($res['order_id']) ){

            $res['status'] = 1;
            $res['description'] = '订单生成失败';

        }else{

            //todo 删除购物车相关产品

            if( !empty( $post['cartids'] ) ){
//                $cart = json_decode( $_GPC['cart'], true );
                cartModel::removeMore( $post['cartids'] );
            }

//            unset($_SESSION['checkout']);
        }


        echo json_encode( $res );


    }

    public function get_list(){

        global $_GPC;
        global $_W;
        $page = !empty( $_GPC['page'] ) && is_numeric( $_GPC['page']) ? $_GPC['page'] : 1;

        $page_size = !empty( $_GPC['page_size'] ) && is_numeric( $_GPC['page_size']) ? $_GPC['page_size'] : 20;

//        $customer_id = $_SESSION['customer_id'];
        $customer_id = $_W['customer']['id'];

        $filter = [];
        if( !empty($_GPC['status']) ){
            $filter['status'] = $_GPC['status'];
        }

        $list = orderModel::get_list( $customer_id, $filter, $page, $page_size );



        foreach( $list as &$item ){

            $goods = orderModel::get_order_goods( $item['id'] );

            foreach( $goods as &$g ){

                $g['image'] = imageModel::resize2( $g['image'], 100, 100);
            }
            $item['goods'] = $goods;
        }
        $total = orderModel::get_order_number( $customer_id );

        $json = [
            'list'=> $list,
            'total'=> $total,
        ];
        echo json_encode( $json );
    }

    public function info(){

        $res = [
            'status'=>0
        ];
        global $_W;
        $customer_id = $_W['customer']['id'] ;

        if( empty($customer_id) ){

            $res['status'] = 1;
            $res['description'] = '请先登录';
            echo json_encode( $res );
            die();
        }

        $order_id = $_GET['order_id'];

        if( empty($order_id) ){
            $res['status'] = 1;
            $res['description'] = '请求错误';
            echo json_encode( $res );

            die();
        }

        $order = orderModel::findById( $customer_id, $order_id );

        $img_width= 100;
        $img_height= 100;
        $order['goods'] = orderModel::get_order_goods( $order_id );
        foreach( $order['goods'] as &$goods ){
//            $goods['image'] = tomedia( $goods['image'] );
            $goods['image'] = imageModel::resize2( $goods['image'], $img_width,$img_height );
        }
        $order['total'] = orderModel::get_order_total( $order_id );
        $order['history'] = orderModel::get_history_list( $order_id );

        $order['package'] = orderModel::get_order_package( $order_id );

        if($order['shipping_method'] == 'pickup' ){

            $order['pickup'] = orderModel::get_order_pickup( $order_id );
        }

        if( $order['shipping_method'] == 'package' ){
            $order['shipping_method_text'] = count($order['package']) ?$order['package'][0]['name']:'其他快递';
        }else if( $order['shipping_method'] == 'pickup' ) {
            $order['shipping_method_text'] = '自提订单';
        }


        $res['order'] = $order;

        echo json_encode( $res );

    }

    public function cancel(){

        $res = [
            'status'=>0
        ];

        global $_W;
        global $_GPC;

        $customer_id = $_W['customer']['id'];


        $order_id = $_GPC['order_id'];

        $order = orderModel::findById( $customer_id, $order_id );

        if( empty($order ) ){
            $res['status'] = 1;
            $res['description'] = '订单不存在';
            echo json_encode( $res );
            die();
        }

        if( $order['status'] != 'pending' ) {
            $res['status'] = 1;
            $res['description'] = '订单无法取消';
            echo json_encode( $res );
            die();
        }

        if( orderModel::cancel( $customer_id, $order_id )){
            echo json_encode( $res );
        }else{
            $res['status'] = 1;
            $res['description'] = '网络异常，请稍后再试';
            echo json_encode( $res );
        }

    }

    public function pindan_list(){

        $goods_id = $_GET['goods_id'];

        $pindan_list = orderModel::pindan_list( $goods_id );

        echo json_encode( $pindan_list );

    }

}