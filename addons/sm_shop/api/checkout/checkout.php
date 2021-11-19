<?php

namespace sm_shop\api\checkout;
use sm_shop\controller;
//use library\image;
use sm_shop\model\tool\imageModel;
use sm_shop\model\addressModel;
use sm_shop\model\cartModel;
use sm_shop\model\goodsModel;
use sm_shop\model\shippingModel;
use sm_shop\model\paymentModel;

class checkout extends controller{

    private $checkout_info;
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
    // 计算总价
    private function calculate(){

        global $_W, $_GPC;
        $customer_id = $_W['customer']['id'];
        $total = 0;

        $goods_total = 0;
        if( !empty( $this->checkout_info['cart'] ) ){

            $list = cartModel::get_list( $customer_id, $this->checkout_info['cart'] );
            $this->checkout_info['goods'] = [];

            foreach( $list as &$item ){

                $goods = goodsModel::detail( $item['goods_id'] );

                $price = isset($item['option_price'])?$item['option_price']:$item['price'];
                $goods_total += $price * $item['goods_num'];
                $key = $item['id'];

                $this->checkout_info['goods'][ $key ] = [
                    'goods_id' => $item['goods_id'],
                    'goods_num' => $item['goods_num'],
                    'name' => $item['name'],
                    'price' => $price,
                    'goods_option'=>$item['goods_option'],
//                    'total_price'=> number_format(  floatval( $price ) * intval( $item['goods_num'] ),2),
                    'total_price'=> round(floatval( $price ) * intval( $item['goods_num'] ), 2),
                    'img' => imageModel::resize2( $goods['image'], 100, 100 ),
//                    'img' => tomedia( $goods['image'] ),

                ];
            }
        }

        //todo: 添加 单产品直接购买 模式
        else if( !empty( $this->checkout_info['buy'] )
            && !empty( $this->checkout_info['buy']['goods_id'] ) ){
            $this->checkout_info['goods'] = [];

            $goods_id = $this->checkout_info['buy']['goods_id'];



            $goods_num = !empty( $this->checkout_info['buy']['goods_num'] ) ? intval( $this->checkout_info['buy']['goods_num'] ) : 1;
            $goods_num = $goods_num < 1 ? 1 : $goods_num;

            $goods = goodsModel::detail( $goods_id );
            $price = $goods['price'];
            if( !empty( $this->checkout_info['buy']['option_id']  ) ){
                $option = goodsModel::get_goods_spec_obj_by_id( $this->checkout_info['buy']['option_id'] );
            }
            if( !empty( $option ) ){
                $price = !empty($this->checkout_info['is_pindan']) ? $option['price_pindan'] : $option['price'];
            }

            $goods_total += $price * $goods_num;

            $goods_img = imageModel::resize2( $goods['image'], 100, 100 );

            $this->checkout_info['goods'][ $goods_id ] = [
                'goods_id' => $goods['goods_id'],
                'goods_num' => $goods_num,
                'name' => $goods['name'],
                'goods_option' => !empty($option)?$option['attr']:'',
                'price' => $price,
                'total_price'=> round(  floatval( $price ) * $goods_num,2),
                'img' => $goods_img,
            ];
        }


        $shipping_total = 0;
        $total = $goods_total + $shipping_total;
        $this->checkout_info['total'] = [
            'goods' => [
                'name'=>'商品总额',
                'price'=>round( $goods_total, 2)
            ],
            'shipping'=>[
                'name'=>'运费',
                'price'=>round( $shipping_total,2)
            ],
            'total' => [
                'name'=>'订单总额',
                'price'=>round($total,2)
            ],
        ];

        if( empty( $this->checkout_info['payment_code']) ){


            $default_payment = paymentModel::getDefault();

            $this->checkout_info['payment_code'] = $default_payment['code'];

        }

        if( empty( $this->checkout_info['shipping_code']) ){

            $default_shipping = shippingModel::getDefault();
            $this->checkout_info['shipping_code'] = $default_shipping['code'];

        }

        if( empty( $this->checkout_info['address_id']) ) {

//            $customer_id = $_SESSION['customer_id'];
            $address = addressModel::getDefault( $customer_id );
            $this->checkout_info['address_id'] = !empty($address)? $address['id']:'';
        }

        $_SESSION['checkout'] = $this->checkout_info;




    }


    public function index(){

        $res = [
            'goods'=>[],
            'address'=>[],
            'shipping'=>[],
            'payment'=>[],
            'total'=>[]

        ];


        global $_W, $_GPC;
        $customer_id = $_W['customer']['id'];

        $this->checkout_info = !empty( $_SESSION['checkout']) ? $_SESSION['checkout'] : [];
        // session 不存在，(或者api请求不被启用的情况下)
        if( empty(session_id()) || $_GPC['a']=='wxapp' ){

            if( !empty( $_GPC['cartids'] ) ){
                $this->checkout_info['cart'] = explode(',' , $_GPC['cartids'] );
            }

            if( !empty( $_GPC['buy'] ) ){

                if( is_array($_GPC['buy']) ){
                    $this->checkout_info['buy'] = $_GPC['buy'];
                }else{
                    $this->checkout_info['buy'] = json_decode( $_GPC['buy'], true );
                }
            }

            if( !empty( $_GPC['is_pindan'] ) ){
                $this->checkout_info['is_pindan'] = $_GPC['is_pindan'];
                $this->checkout_info['pindan_order'] = $_GPC['pindan_order'];
            }
        }

        $this->calculate();

//        $checkout_info = $_SESSION['checkout'];


        if( $this->checkout_info['address_id'] ){

            $this->checkout_info['address'] = addressModel::single( $this->checkout_info['address_id'], $customer_id );
        }else{
            $this->checkout_info['address'] = addressModel::getDefault( $customer_id );
            if( !empty($this->checkout_info['address']) ){
                $this->checkout_info['address_id'] = $this->checkout_info['address']['id'];
                $_SESSION['checkout']['address_id'] = $this->checkout_info['address_id'];
            }

        }

        //todo 获取 支付列表
        $this->checkout_info['payment_list'] = paymentModel::getList();
        //todo 获取 配送列表
        $this->checkout_info['shipping_list'] = shippingModel::getList();



        echo json_encode( $this->checkout_info );

    }

    // 购物车 传递数据
    public function cart(){

        $res = [
            'status'=>0
        ];
        if( empty( $_POST['cart_ids'] ) ){
            $res['status'] = 1;
            $res['description'] = '参数不能为空';
            echo json_encode( $res );
            die();
        }

        $cart = explode( ',', $_POST['cart_ids'] );
        $_SESSION['checkout']['cart'] = $cart;


        echo json_encode( $res );
    }

    // 直接购买单一产品
    public function goods(){

        global $_GPC;
        $res = [
            'status'=>0
        ];
        if( empty( $_GPC['goods_id'] ) ){
            $res['status'] = 1;
            $res['description'] = '参数不能为空';
            echo json_encode( $res );
            die();
        }

        $_SESSION['checkout']['cart'] = [];
        $_SESSION['checkout']['buy'] = [
            'goods_id'  => $_GPC['goods_id'],
            'option_id'  => !empty($_GPC['option_id'])?$_GPC['option_id']:'',
            'goods_num' => $_GPC['number'],
        ];

        echo json_encode( $res );


    }

    public function payment( ){
        $res = [
            'status'=>1,
        ];
        if( empty($_POST['code'] ) ){
            $res['description'] = '数据不能为空';
            echo json_encode( $res );
            die();
        }
        $code = $_POST['code'];
        $payment = paymentModel::findByCode( $code );
        if( empty( $payment ) ){
            $res['description'] = '支付方式不存在';
            echo json_encode( $res );
            die();
        }
        $_SESSION['checkout']['payment_code'] = $code;

        $this->index();
//        $res['status'] = 0;
//        echo json_encode( $res );
    }

    public function shipping( ){
        $res = [
            'status'=>1,
        ];
        if( empty($_POST['code'] ) ){
            $res['description'] = '数据不能为空';
            echo json_encode( $res );
            die();
        }
        $code = $_POST['code'];
        $shipping = shippingModel::findByCode( $code );
        if( empty( $shipping ) ){
            $res['description'] = '配送方式不存在';
            echo json_encode( $res );
            die();
        }
        $_SESSION['checkout']['shipping_code'] = $code;

        $this->index();
//        $res['status'] = 0;
//        echo json_encode( $res );
    }

    public function address(){
        $res = [
            'status'=>1
        ];

        if( empty($_POST['address_id'] ) ){
            $res['description'] = '数据不能为空';
            echo json_encode( $res );
            die();
        }

        $address_id = $_POST['address_id'];

        $_SESSION['checkout']['address_id'] = $address_id;
        $res = [
            'status'=>0
        ];

        echo json_encode( $res );
    }

}