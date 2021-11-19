<?php

namespace sm_shop\api\checkout;
use sm_shop\controller;
use sm_shop\model\addressModel;
use sm_shop\model\cartModel;
use sm_shop\model\goodsModel;
use sm_shop\model\shippingModel;
use sm_shop\model\paymentModel;
use sm_shop\model\orderModel;
use sm_shop\model\settingModel;

class xpay extends controller{

    private $wechatPay;
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

    public function index(){

        global $_GPC;
        global $_W;
        $order_id = $_GPC['order_id'];

        $customer_id = $_W['customer']['id'];
        $order = orderModel::findById( $customer_id,  $order_id );

        load()->classs('pay/pay');
        $this->wechatPay = \Pay::create();
        $res = $this->buildJsApiPrepayid( $order );

        echo is_string( $res ) ? $res: json_encode( $res );

    }

    public function index_xcx(){

        global $_GPC;
        global $_W;
        if( $_GPC['__input']){
            $post = $_GPC['__input'];
        }else{
            $post = $_POST;
        }
        $order_id = $_GPC['order_id']?:$post['order_id'];

        $customer_id = $_W['customer']['id'];
        $order = orderModel::findById( $customer_id, $order_id );


        load()->classs('pay/pay');
        $this->wechatPay = \Pay::create();

        $xcx_setting = settingModel::get_xcx();
        if( !empty($xcx_setting['app_id']) && !empty($xcx_setting['mch_id'])  && !empty($xcx_setting['mch_key']) ){

            $this->wechatPay->wxpay['appid'] = $xcx_setting['app_id'];
            $this->wechatPay->wxpay['mch_id'] = $xcx_setting['mch_id'];
            $this->wechatPay->wxpay['key'] = $xcx_setting['mch_key'];

        }else if( !empty( $_W['account']['setting']['payment']['wechat'] ) ){

            $payment_wechat = $_W['account']['setting']['payment']['wechat'];
            $this->wechatPay->wxpay['appid'] = $_W['account']['key'];
            $this->wechatPay->wxpay['mch_id'] = $payment_wechat['mchid'];
            $this->wechatPay->wxpay['key'] = $payment_wechat['signkey'];

        }else {

            $res = [
                'status'        => 1,
                'description'   => '支付参数异常',
            ];
            echo json_encode( $res );
            die();

        }




//        $this->wechatPay->wxpay['appid'] = 'wx85aca1163a609063';
//        $this->wechatPay->wxpay['mch_id'] = '1516012861';
//        $this->wechatPay->wxpay['key'] = 'c5db249ac3fbca0d005d17348db4967d';

        $res = $this->buildJsApiPrepayid( $order );

        echo is_string( $res ) ? $res: json_encode( $res );

    }

    //
    function buildJsApiPrepayid( $order ){

        global $_W, $_GPC;

        $params = [
            'openid'=>$_W['openid'],
            'module'=>$_GPC['m'],
            'tid'=>'order-' . $order['order_no'],
            'fee'=>$order['total'],
            'type'=>'wechat',
            'card_fee'=>$order['total'],
        ];

        $plid = $this->wechatPay->buildPayLog( $params );

        $order = pdo_get('core_paylog', array('plid' => $plid));
        if (empty($order)) {
            return error(-1, '订单不存在');
        }
        if (1 == $order['status']) {
            return error(-1, '该订单已经支付,请勿重复支付');
        }

        $jspai = array(
            'out_trade_no' => $order['uniontid'],
            'trade_type' => 'JSAPI',
            'openid' => $order['openid'],
            'body' => !empty($order['body'])?$order['body']:'前台零售',
            'total_fee' => $order['fee'] * 100,
            'attach' => $order['uniacid'],
        );
        $result = $this->wechatPay->buildUnifiedOrder($jspai);
        if (is_error($result)) {
            return $result;
        }
        $jspai = array(
            'appId' => $this->wechatPay->wxpay['appid'],
            'timeStamp' => '' . TIMESTAMP,
            'nonceStr' => random(32),
            'package' => 'prepay_id=' . $result['prepay_id'],
            'signType' => 'MD5',
        );
        $jspai['paySign'] = $this->wechatPay->bulidSign($jspai);
        return $jspai;
        $jspai = <<<EOF
		<script type="text/javascript">
		    function wxPay(){
		       console.log('start:');
		       // document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
			    console.log( 'WeixinJSBridgeReady' );
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest', {
						appId:'{$jspai['appId']}',
						timeStamp:'{$jspai['timeStamp']}',
						nonceStr:'{$jspai['nonceStr']}',
						package:'{$jspai['package']}',
						signType:'MD5',
						paySign:'{$jspai['paySign']}'
					},
					function(res){
					    console.log( res );
						if(res.err_msg == 'get_brand_wcpay_request：ok' ) {
						 alert('支付成功');
						 //todo 前端进行回调，标记订单支付状态
						 
						} else {
						}
					}
				);
			 // }, false); 
		    }
		    wxPay();
			
		</script>
EOF;

        return $jspai;
    }

    // 付款成功后通知
    public function pay_notify( ){

        $res = [];
        global $_GPC;
        global $_W;
        $customer_id = $_W['customer']['id'] ;
        if( $_GPC['__input']){
            $post = $_GPC['__input'];
        }else{
            $post = $_POST;
        }
        $order_id = $_GPC['order_id']?:$post['order_id'];
        $order = orderModel::findById( $customer_id, $order_id );

        if( $order['shipping_method'] == 'package' ){
            $order_status = 'processing';
        }else if( $order['shipping_method'] == 'pickup' ){
            $order_status = 'shipping';
        }
        $res['ret'] = orderModel::order_pay( $order_id, $order_status );
        echo json_encode( $res );

    }


    //申请退款
    public function refund(){

        global $_GPC;
        global $_W;

        $res = [
            'status'=>0
        ];
        $customer_id = $_W['customer']['id'] ;
        $order_id = $_GPC['order_id'];
        $res['ret'] = $order = orderModel::applyForRefund( $customer_id, $order_id );
        echo json_encode( $res );
    }

}