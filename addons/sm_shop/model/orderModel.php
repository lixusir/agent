<?php
namespace sm_shop\model;
use sm_shop\model;
use sm_shop\model\goodsModel;
use sm_shop\model\paymentModel;
use sm_shop\model\shippingModel;
use sm_shop\model\addressModel;
use sm_shop\model\tool\imageModel;
class orderModel extends model{


    public static function create( $data ){

        global $_W;
        $total = $data['total']['total']['price'];
        $create_time = date("Y-m-d H:i:s");
        $sql = "INSERT INTO sh_order SET ".
            "`order_no`='" . $data['order_no'] . "',".
            "`uniacid`='" . $_W['uniacid'] . "',".
            "`create_time`='" . $create_time . "',".
            "`customer_id`='" . $data['customer_id'] . "',".
            "`total`='" . $total . "',".
            "`shipping_method`='" . $data['shipping_method'] . "',".
            "`payment_method`='" . $data['payment_method'] . "',".
            "`is_payed`='" . $data['is_payed'] . "',".
            "`address_name`='" . $data['address_name'] . "',".
            "`address_phone`='" . $data['address_phone'] . "',".
            "`address_province`='" . $data['address_province'] . "',".
            "`address_city`='" . $data['address_city'] . "',".
            "`address_area`='" . $data['address_area'] . "',".
            "`address_detail`='" . $data['address_detail'] . "',".
            "`message`='" . $data['message'] . "',".
            "`status`='" . $data['status'] . "';";

//        $res = self::$db->query( $sql );
        $res_num_rows = pdo_query( $sql );
        $order_id = 0;
        if( $res_num_rows ){
//            $order_id = self::$db->getLastId();
            $order_id = pdo_insertid();

            //todo 添加 订单商品表
            self::add_order_goods( $order_id, $data['goods'] );

            //todo 添加 订单总计表
            self::add_order_total( $order_id, $data['total'] );

            //todo 添加 订单自提表
            if( $data['shipping_method'] == 'pickup' && !empty($data['pickup']) ){
                self::add_order_pickup( $order_id, $data['pickup'] );
            }

            //todo 添加 拼单信息
            if( !empty($data['is_pindan'])  ){

                self::add_order_pindan( $order_id, $data['pindan_order'] );

                // 如果为发起的拼单，添加拼单属性
                if( empty( $data['pindan_order'] ) ){
                    $goods_id = current($data['goods'])['goods_id'];

                    self::add_order_pindan_info( $order_id, $goods_id );
                }
            }

            //todo 添加订单历史
            $history_data = [
                'comment'=>$data['message'],
                'order_status'=>$data['status'],
            ];
            self::add_order_history( $order_id, $history_data );

        }


        return $order_id;

    }

    public static function get_order_pickup( $order_id ){

        $sql = "select * from sh_order_pickup where order_id=" . $order_id ;

        return pdo_fetch( $sql );

    }

    public static function add_order_pickup( $order_id, $pickup ){

        global $_W;
        $sql = 'insert into sh_order_pickup set '
            . ' order_id=' . $order_id . ','
            . ' uniacid=' . $_W['uniacid'] . ','
            . ' pickup_id=' . $pickup['id'] . ','
            . ' pickup_name="' . $pickup['name'] . '",'
            . ' pickup_phone="' . $pickup['phone'] . '"'
        ;
        pdo_query( $sql );
    }

    public static function add_order_goods( $order_id, $order_goods ){

        global $_W;
        $sql = "insert into sh_order_goods (order_id,uniacid,goods_id,goods_name,goods_option,goods_num,goods_price,goods_total) values";
        $sql_split = '';
        foreach( $order_goods as $goods ){

            $sql_value = "('" . $order_id ."','"
                . $_W['uniacid'] ."','"
                . $goods['goods_id'] ."','"
                . $goods['name'] ."','"
                . $goods['goods_option'] ."','"
                . $goods['goods_num'] ."','"
                . $goods['price'] ."','"
                . $goods['total_price'] ."')";
//            $sql_data += "order_id='" . $order_id ."',";
//            $sql_data += "goods_id='" . $goods['goods_id'] ."',";
//            $sql_data += "goods_name='" . $goods['name'] ."',";
//            $sql_data += "goods_num='" . $goods['goods_num'] ."',";
//            $sql_data += "goods_price='" . $goods['price'] ."',";
//            $sql_data += "goods_total='" . $goods['total'] ."',";

            if( !empty($sql_split) ){
                $sql_split .= ',';
            }
            $sql_split .= $sql_value;


        }
        $sql .= $sql_split;
//        self::$db->query( $sql );
        pdo_query( $sql );
    }

    public static function add_order_total ( $order_id, $order_total ){
        global $_W;
        $sql = "insert into sh_order_total ( order_id, uniacid, code, `value` ) values";
        $sql_split = '';

        foreach( $order_total as $key=>$total ){

            $sql_value = "('" . $order_id ."','"
                .  $_W['uniacid'] ."','"
                .  $key ."','"
                . $total['price'] ."')";

            if( !empty($sql_split) ){
                $sql_split .= ',';
            }
            $sql_split .= $sql_value;
        }
        $sql .= $sql_split;

        pdo_query( $sql );
    }


    public static function get_list( $customer_id, $filter, $page, $page_size ){

        $sql = 'SELECT o.*, os.`name` AS status_text FROM sh_order o '
            . ' LEFT JOIN sh_order_status os ON o.`status`=os.code ';

        $sql_page = ' limit ' . ($page - 1) * $page_size .','. $page_size;

        $where = ' where o.`status`<>"canceled" and o.customer_id='. $customer_id ;
        if( !empty($filter) ){
            foreach( $filter as $key => $value ){
                $where .= ' and o.' . $key . '= "' . $value . '" ';
            }
        }

        $sort_order = ' order by create_time desc ';

        $sql .= $where . $sort_order . $sql_page ;
//        $res = self::$db->query( $sql );
//        return $res->num_rows ? $res->rows: [];

        return pdo_fetchall( $sql );
    }

    public static function get_order_number( $customer_id ){

        $sql = 'select count(id) as total from sh_order where customer_id='. $customer_id ;
//        $res = self::$db->query( $sql );
//
//        return $res->row['total'];
        $res = pdo_fetchcolumn( $sql );
        return $res;
    }

    public static function get_order_goods( $order_id ){

        $sql = 'SELECT og.*,g.image FROM sh_order_goods og'
            . ' inner join sh_goods g on og.goods_id=g.id '
            . ' WHERE order_id=' . $order_id;
//        $res = self::$db->query( $sql );
//        return $res->num_rows ? $res->rows : [];

        return pdo_fetchall( $sql );
    }

    public static function get_order_total( $order_id ){

        $sql = 'SELECT * FROM sh_order_total WHERE order_id=' . $order_id;
//        $res = self::$db->query( $sql );
//        return $res->rows;

        return pdo_fetchall( $sql );
    }

    public static function findById( $customer_id, $order_id ){

        $sql = 'select o.*, p.name as payment_name, s.name as shipping_name from sh_order o '
            . ' left join sh_payment p on o.payment_method=p.code '
            . ' left join sh_shipping s on o.shipping_method=s.code '
            . ' where o.customer_id=' . $customer_id
            . ' and o.id=' . $order_id;
//        $res = self::$db->query( $sql );
//        return $res->num_rows ? $res->row : null;

        return pdo_fetch( $sql );
    }

    public static function cancel( $customer_id, $order_id ){

        $sql = 'update sh_order set `status`="canceled" where customer_id=' . $customer_id . ' and id=' . $order_id;
//        $res = self::$db->query( $sql );
//        return $res->num_rows > 0 ;

        $res = pdo_query( $sql );

        // todo 取消订单
        $log_data = [
            'order_status'=>'canceled',
            'comment'=>'用户取消订单',
        ];
        self::add_order_history($order_id, $log_data );

        return $res > 0;
    }

    public static function get_history_list( $order_id ){

        $sql = 'SELECT 
              h.*,
              s.`name` as order_status_text
            FROM
              sh_order_history h 
              LEFT JOIN sh_order_status s 
                ON h.`order_status` = s.`code` where h.order_id=' . $order_id . ' order by h.create_time desc';

//        $query = self::$db->query( $sql );
//
//        return $query->rows;

        return pdo_fetchall( $sql );

    }

    public static function add_order_pindan_info( $order_id, $goods_id ){

        global $_W;
        $sql = "select * from sh_goods_pindan where goods_id=" . $goods_id;

        $goods_pindan = pdo_fetch( $sql );

        if( !empty( $goods_pindan ) ){

            $date = date('Y-m-d H:i:s');
            $insert_sql = 'insert into sh_order_pindan_info set order_id=' . $order_id
            . ', goods_id=' . $goods_id
            . ', uniacid=' . $_W['uniacid']
            . ', number=' . $goods_pindan['number']
            . ', validate_time="' . $goods_pindan['validate_time'] . '"'
            . ', complete=0'
            . ', start_time="' . $date . '"'
            ;
            pdo_query( $insert_sql );
        }


    }

    public static function add_order_pindan( $order_id, $pindan_order ){

        global $_W;
        $pindan_order = !empty( $pindan_order ) ? $pindan_order : $order_id;
        $sql = 'insert into sh_order_pindan set order_id= ' . $order_id
            . ', uniacid="' . $_W['uniacid'] . '"'
            . ', master_order="' . $pindan_order . '"';

        pdo_query( $sql );

    }

    public static function pindan_list( $goods_id ){

        $sql = 'select opi.order_id, opi.start_time, opi.validate_time, c.name, c.headUrl '
            . ' from sh_order_pindan_info opi'
            . ' left join sh_order o on opi.order_id=o.id'
            . ' left join sh_customer c on o.customer_id=c.id'
            . ' where goods_id=' . $goods_id
        . ' and DATE_SUB(NOW(),INTERVAL validate_time HOUR )<start_time';


        return pdo_fetchall( $sql );
    }

    public static function add_order_history( $order_id, $data ){

        global $_W;
        $create_time = date("Y-m-d H:i:s");
        $sql = 'insert into sh_order_history set order_id= ' . $order_id
            . ', uniacid="' . $_W['uniacid'] . '"'
            . ', order_status="' . $data['order_status'] . '"'
            . ', comment="' . $data['comment'] . '"'
            . ', create_time="' . $create_time . '"'
        ;
        pdo_query( $sql );
    }

    public static function order_pay( $order_id, $order_status ){

        $sql = "update sh_order set is_payed = 1 , status='" . $order_status . "' where id=" . $order_id;
        $history_data = [
            'comment'=>'订单支付',
            'order_status' => $order_status,
        ];
        self::add_order_history( $order_id, $history_data );
        return pdo_query( $sql );

    }


    /**
     * @param $checkout
     * @return mixed
     * 计算订单费用， 地址， 付款形式等
     */
    public static function calculate( &$checkout ){

        global $_W, $_GPC;
        $customer_id = $_W['member']['uid'];
        $total = 0;

        $goods_total = 0;

        foreach( $checkout['goods'] as $key=>&$item ){

            $goods = goodsModel::detail( $item['goods_id'] );

            $goods_total += $item['price'] * $item['goods_num'];

            $checkout['goods'][$key] = [
                'goods_id' => $item['goods_id'],
                'goods_num' => $item['goods_num'],
                'name' => $item['name'],
                'goods_option' => $item['goods_option'],
                'price' => $item['price'],
                'total_price'=> round(  floatval( $item['price']) * intval( $item['goods_num'] ),2),
                'img' => imageModel::resize2( $goods['image'], 100, 100 ),
            ];
        }

        $shipping_total = 0;
        $total = $goods_total + $shipping_total;
        $checkout['total'] = [
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

        if( empty( $checkout['payment_code']) ){


            $default_payment = paymentModel::getDefault();

            $checkout['payment_code'] = $default_payment['code'];

        }

        if( empty( $checkout['shipping_code']) ){

            $default_shipping = shippingModel::getDefault();
            $checkout['shipping_code'] = $default_shipping['code'];

        }

        if( empty( $checkout['address_id']) ) {

            $address = addressModel::getDefault( $customer_id );
            $checkout['address_id'] = !empty($address)? $address['id']:'';
        }

    }

    public static function get_order_package( $order_id ){


        $sql = "select op.*,e.name from sh_order_package op left join sh_express e on op.express_code=e.code where op.order_id= " . $order_id;

        return pdo_fetchall( $sql );

    }

    public static function applyForRefund( $customer_id, $order_id ){


        $sql = 'update sh_order set `apply_refund`=1 where customer_id=' . $customer_id . ' and id=' . $order_id;
        $ret = pdo_query( $sql );

        $order = self::findById($customer_id, $order_id );

        // todo 添加订单历史备注，暂不改变状态
        $log_data = [
            'order_status'=> $order['status'],
            'comment'=>'用户申请退款',
        ];

        self::add_order_history($order_id, $log_data );

        return $ret;

    }
}