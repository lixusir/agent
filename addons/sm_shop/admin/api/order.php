<?php
namespace sm_shop\admin\api;
use sm_shop\controller;

use sm_shop\admin\model\orderModel;
use sm_shop\model\tool\imageModel;

class order extends controller{

    public function page_list(){

        $this->template( 'sale/order/list' );

    }

    public function page_edit(){

        $this->template( 'sale/order/edit' );

    }

    public function page_show(){

        $this->template( 'sale/order/show' );

    }

    public function index(){

        $filter = [
            'customer'      =>  $_GET['customer']?:'',
            'status'        =>  $_GET['status']?:'',
            'order_no'      =>  $_GET['order_no']?:'',
            'start_time'    =>  $_GET['start_time']?:'',
            'end_time'      =>  $_GET['end_time']?:'',
        ];
        $list = orderModel::get_list( $filter );

        echo json_encode( $list );

    }

    /**
     * 获取单条 订单信息
     */
    public function info(){

        $order_id = $_GET['order_id'];
        $order_info = orderModel::get_order( $order_id );

        foreach( $order_info['goods'] as &$goods ){

            $goods['image'] = imageModel::resize2($goods['image'], 100, 100 );
        }

        $order_info['payment_method_text'] = $order_info['payment_method'] == 'wechat'?'微信支付':'';
        $order_info['payment_method_text'] = $order_info['payment_method'] == 'alipay'?'支付宝':$order_info['payment_method_text'];

        if( $order_info['shipping_method'] == 'package' ){
            $order_info['shipping_method_text'] = count($order_info['package']) ?$order_info['package'][0]['name']:'其他快递';
        }else if( $order_info['shipping_method'] == 'pickup' ) {
            $order_info['shipping_method_text'] = '自提订单';
        }

        $code_text = [
            'goods'     => '商品',
            'shipping'  => '配送',
            'total'     => '总计',
        ];
        foreach( $order_info['total'] as &$total ){

            $total['code_text'] = $code_text[ $total['code'] ];
        }

        echo json_encode( $order_info );

    }

    public function status_list(){

        $order_all_status = orderModel::get_all_status();

        echo json_encode( $order_all_status );
    }

    //todo 改变订单状态(添加订单历史)
    public function add_history(){

        $order_id = $_GET['order_id'];

        $order_status = $_POST['order_status'];

        $comment = $_POST['comment'];


        $data = [
            'order_status'=>$order_status,
            'comment'=>$comment,
            'create_time'=>date("Y-m-d H:i:s")
        ];
        $res = orderModel::add_history( $order_id, $data );

        echo json_encode( $res );

    }

    public function set_order_package(){

        $res = [
            'status' => 0
        ];

        $order_id = $_GET['order_id'];
        $package_no = $_POST['package_no'];
        $express_code = $_POST['express_code'];

        $data = [
            'package_no'=>$package_no,
            'express_code'=>$express_code,
        ];


        orderModel::removePackage( $order_id );
        $res['ret'] = orderModel::addPackage( $order_id, $data );

        $history_data = [
            'order_status'=>'shipping',
            'comment'=>'确定发货',
            'create_time'=>date("Y-m-d H:i:s")
        ];
        orderModel::add_history( $order_id, $history_data );

        echo json_encode( $res );

    }

    // todo 退款操作
    public function refund(){

        global $_GPC;
        // todo 退款操作

        $json = [

            'status'=>0,

        ];
        if( empty( $_GPC['order_id'] ) ){
            $json['status'] = 1;
            $json['description'] = '参数不合法';
            echo json_encode( $json );
            die();
        }

        $order_id = $_GPC['order_id'];
        $order = orderModel::get_order( $order_id );

        if( empty( $order ) ){
            $json['status'] = 1;
            $json['description'] = '订单不存在.';
            echo json_encode( $json );
            die();
        }


        //首先load模块函数
        load()->model('refund');

        //创建退款订单
        //$tid  模块内订单id
        //$module 需要退款的模块
        //$fee 退款金额
        //$reason 退款原因
        //成功返回退款单id，失败返回error结构错误

        $tid = 'order-' . $order['order_no'];
        $module = $_GPC['m'];
        $fee = $_GPC['fee']?:'';
        $reason = $_GPC['reason']?:'';

        $refund_id = refund_create_order($tid, $module, $fee, $reason);
        if (is_error($refund_id)) {
//            itoast($refund_id['message'], referer(), 'error');
            $json['status'] = 1;
            $json['description'] = $refund_id['message'];
            echo json_encode( $json );
            die();
        }

        //发起退款
        $refund_result = refund( $refund_id );
        if (is_error($refund_result)) {
//            itoast($refund_result['message'], referer(), 'error');
            $json['status'] = 1;
            $json['description'] = $refund_result['message'];
            echo json_encode( $json );
            die();
        } else {
            pdo_update('core_refundlog', array('status' => 1), array('id' => $refund_id));

            // todo 更改订单状态 到已取消

            $refund_data = [
                'order_id'=>$order_id,
                'reason'=>$reason
            ];
            $ret = orderModel::refund( $refund_data );
            // todo 更改支付状态 到已退款
            $json['ret'] = $ret;
            $json['description'] = '退款成功';
            echo json_encode( $json );
            die();

        }




    }

}
