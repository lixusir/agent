<?php

namespace sm_shop\admin\model;
use sm_shop\model;
class customerGroupModel extends model
{

    public static function get_list( $filter ){

        global $_W;
        $where_arr = [];
        $where_arr[] = ' cg.uniacid=' . $_W['uniacid'];
        if( !empty($filter['name']) ){
            $where_arr[] = ' cg.name like "%' . $filter['name'] . '%"';
        }

        $where_str = '';
        if( !empty( $where_arr ) ){
            $where_str = ' where ' . join(' and ', $where_arr );
        }

        $sql = 'select * from sh_customer_group cg ' . $where_str;

        return pdo_fetchall( $sql );

    }


    public static function single( $customer_group_id ){

        $sql = 'select * from sh_customer_group where id=' . $customer_group_id;

        return pdo_fetch( $sql );

    }

    public static function edit( $id, $data ){

        $sql = 'update sh_customer_group set '.
        '`name`="' . $data['name'] . '",'.
        '`description`="' . $data['description'] . '",'.
        '`is_default`=' . $data['is_default'] .
        ' where id=' . $id
        ;
//        echo $sql;
        return pdo_query( $sql );

    }

    public static function create( $data ){

        global $_W;
        $sql = 'insert into sh_customer_group set '.
            '`name`="' . $data['name'] . '",'.
            '`uniacid`="' . $_W['uniacid'] . '",'.
            '`description`="' . $data['description'] . '",'.
            '`is_default`=' . $data['is_default']
        ;

//        echo $sql;
        pdo_query( $sql );
        return pdo_insertid( );
    }


}

