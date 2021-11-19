<?php
namespace sm_shop\model;
use sm_shop\model;
class shippingModel extends model{

    public static function getDefault(){

        $sql = "select * from sh_shipping where is_default='1'";
//        $result = self::$db->query( $sql );
//        return !empty($result->row)?$result->row:false;

        $row = pdo_fetch( $sql );
        return !empty( $row ) ? $row : false;

    }

    public static function getList(){

        $sql = "select * from sh_shipping where active='1'";
//        $result = self::$db->query( $sql );
//        return !empty($result->num_rows)?$result->rows:false;

        $row = pdo_fetchall( $sql );
        return !empty( $row ) ? $row : false;
    }

    public static function findByCode( $code ){
        $sql = "select * from sh_shipping where active='1' and code ='" . $code . "'";
//        $result = self::$db->query( $sql );
//        return !empty($result->row)?$result->row:false;

        $row = pdo_fetch( $sql );
        return !empty( $row ) ? $row : false;
    }

}