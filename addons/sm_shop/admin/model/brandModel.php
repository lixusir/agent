<?php
namespace sm_shop\admin\model;
use sm_shop\model;
class brandModel extends model{

    public static function search( $filter ){

        global $_W;
        $sql = 'select * from sh_brand ';

        $where = [];
        $where[] = " uniacid=" . $_W['uniacid'];
        if( !empty( $filter['name'] ) ){
            $where[] = " `name` like '%" . $filter['name'] . "%' " ;
        }
        if( isset( $filter['status'] ) ){
            $where[] = " `status` =" . $filter['status'] . " " ;
        }

        if( !empty( $where ) ){

            $where_str = implode( 'and', $where );
            $sql .= ' where ' . $where_str;

        }

        return pdo_fetchall( $sql );

    }

    public static function single( $brand_id ){

        $sql = 'select * from sh_brand where id=' . $brand_id;
        return pdo_fetch( $sql );

    }


    public static function update( $brand_id, $data ){

        $sql = 'update sh_brand set '
            . '`name`="' . $data['name'] . '", '
            . '`img`="' . $data['image'] . '", '
            . '`letter_pre`="' . $data['letter_pre'] . '", '
            . '`status`=' . $data['status']
            . ' where id=' . $brand_id
        ;

        $ret = pdo_query( $sql );
        return $ret;

    }

    public static function insert( $data ){

        global $_W;
        $sql = 'insert into sh_brand set '
            . '`uniacid`="' . $_W['uniacid'] . '", '
            . '`name`="' . $data['name'] . '", '
            . '`img`="' . $data['image'] . '", '
            . '`letter_pre`="' . $data['letter_pre'] . '", '
            . '`status`=' . $data['status']
            ;
        $ret = pdo_query( $sql );
        return $ret;

    }

    public static function remove( $brand_id ){

        $sql = 'delete from sh_brand where id=' . $brand_id;
        $ret = pdo_query( $sql );
        return $ret;

    }


}