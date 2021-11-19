<?php

namespace sm_shop\admin\model;
use sm_shop\model;
class customerModel extends model
{

    public static function get_list( $filter ){

        global $_W;
        $where_arr = [];
        $where_arr[] = ' c.uniacid=' . $_W['uniacid'];
        if( !empty($filter['name']) ){
            $where_arr[] = ' c.name like "%' . $filter['name'] . '%"';
        }

        if( isset($filter['status']) ){
            $where_arr[] = ' c.status = "' . $filter['status'] . '"';
        }

        if( !empty($filter['telephone']) ){
            $where_arr[] = ' c.telephone like "%' . $filter['telephone'] . '%"';
        }

        if( !empty($filter['customer_group_id']) ){
            $where_arr[] = ' c.customer_group_id = "' . $filter['customer_group_id'] . '"';
        }

        $where_str = '';
        if( !empty( $where_arr ) ){
            $where_str = ' where ' . join(' and ', $where_arr );
        }

        $sql = 'select c.*, cg.name as customer_group, cx.open_id as xcx_open_id '
            . ' from sh_customer c '
            . ' left join sh_customer_group cg on c.customer_group_id=cg.id '
            . ' left join sh_customer_xcx cx on c.open_id=cx.open_id '
            . $where_str
            . ' order by c.create_time desc '
        ;

        return pdo_fetchall( $sql );

    }


    public static function single( $customer_id ){

        $sql = 'select * from sh_customer where id=' . $customer_id;

        return pdo_fetch( $sql );

    }

    public static function edit( $id, $data ){

        $sql = 'update sh_customer set customer_group_id= '
            . $data['customer_group_id']
            . ', remark= "' . $data['remark'] . '"'
            . ' where id=' . $id;

        return pdo_query( $sql );

    }
}

