<?php

namespace sm_shop\admin\model;
use sm_shop\model;
class orderModel extends model
{

    public static function get_list( $filter = [] ){

        global $_W;
        $sql = 'select o.*, c.name as customer_name, os.name as status_text from sh_order o 
left join sh_customer c on o.customer_id=c.id 
left join sh_order_status os on o.status=os.code ';

        $where = ' where o.uniacid= ' . $_W['uniacid'];
        if( !empty( $filter['customer']) ){

            $where .= 'and c.name like "%' . $filter['customer'] . '%"';
        }
        if( !empty( $filter['order_no']) ){

            $where .= 'and o.order_no = "' . $filter['order_no'] . '"';
        }
        if( !empty( $filter['status']) ){

            $where .= 'and o.status ="' . $filter['status'] .'"';
        }

        if( !empty( $filter['start_time']) ){

            $where .= 'and o.create_time >= "' . $filter['start_time'] . '"';
        }
        if( !empty( $filter['end_time']) ){

            $where .= 'and o.create_time <= "' . $filter['end_time'] . '"';
        }

        $sql .= $where;
        $sql .= ' order by o.id desc ';
//        echo $sql;

        return pdo_fetchall( $sql );
    }

    public static function get_order( $order_id ){

        $sql = 'select o.*, c.name as customer_name, c.telephone, 
cg.name as customer_group_name 
 from sh_order o 
left join sh_customer c on o.customer_id=c.id
left join sh_customer_group cg on c.customer_group_id=cg.id
where o.id=' . $order_id;

        $order = pdo_fetch( $sql );
        $order['goods'] = self::get_order_goods( $order_id );
        $order['total'] = self::get_order_total( $order_id );
//        $order['package'] = self::get_order_package( $order_id );
        $order['history_list'] = self::history_list( $order_id );

        if( $order['shipping_method'] == 'pickup' ){

            $order['pickup'] = self::get_order_pickup( $order_id );

        }else if( $order['shipping_method'] == 'package' ){
            $order['package'] = self::get_order_package( $order_id );
        }

        return $order;

    }

    public static function get_order_goods( $order_id ){

        $sql = 'select og.*,g.image from sh_order_goods og
left join sh_goods g on g.id=og.goods_id
where og.order_id=' . $order_id;
//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );
    }

    public static function get_order_total( $order_id ){

        $sql = 'select * from sh_order_total where order_id=' . $order_id;
//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );
    }

    public static function get_all_status(){

        $sql = "SELECT * FROM sh_order_status WHERE is_active = 1;";
//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );
    }

    public static function add_history( $order_id, $data ){

        global $_W;
        $sql = "INSERT INTO sh_order_history SET order_id = '" . $order_id . "',
                `comment` = '" . $data['comment'] . "',
                `uniacid` = '" . $_W['uniacid'] . "',
                order_status = '" . $data['order_status'] . "',
                create_time = '" . $data['create_time'] . "'";
//        $query = self::$db->query( $sql );
        $query =  pdo_query( $sql );

        //todo 修改订单状态
        $change_sql = "UPDATE sh_order SET status= '" . $data['order_status'] . "' WHERE id= " . $order_id;

        return pdo_query( $change_sql );
    }

    public static function history_list( $order_id ){

        $sql = 'SELECT 
              h.*,
              s.`name`
            FROM
              sh_order_history h 
              LEFT JOIN sh_order_status s 
                ON h.`order_status` = s.`code` where h.order_id=' . $order_id . ' order by h.create_time desc';

//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );
    }


    public static function get_order_pickup( $order_id ){

        $sql = "select * from sh_order_pickup where order_id=" . $order_id ;

        return pdo_fetchall( $sql );

    }

    public static function get_order_package( $order_id ){

        $sql = "select op.*,e.name from sh_order_package op left join sh_express e on op.express_code=e.code where op.order_id=" . $order_id ;

        return pdo_fetchall( $sql );
    }

    public static function addPackage( $order_id, $data ){
        global $_W;
        $sql = "insert into sh_order_package set order_id=" . $order_id
            . ", uniacid='" . $_W['uniacid'] . "'"
            . ", package_no='" . $data['package_no'] . "'"
            . ", express_code='" . $data['express_code'] . "'"
        ;

        return pdo_query( $sql );
    }

    public static function removePackage( $order_id ){

        $sql = 'delete from sh_order_package where order_id=' . $order_id;

        return pdo_query( $sql );

    }

    public static function refund( $data ){

        $order_id = $data['order_id'];
        $history_data = [
            'order_status'=>'canceled',
            'comment'=>$data['reason'] ? $data['reason'] : '退款',
            'create_time'=>date("Y-m-d H:i:s")
        ];

        $sql = 'update sh_order set is_payed=2 where id=' . $order_id;
        $ret = pdo_query( $sql );
        orderModel::add_history( $order_id, $history_data );
        return $ret;


    }
}