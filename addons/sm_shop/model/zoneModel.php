<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\7\17 0017
 * Time: 11:29
 */
namespace sm_shop\model;
use sm_shop\model;
class zoneModel extends model{

    public static function get_list(){

        $sql = "select * from sh_zone";

//        $result = self::$db->query( $sql );
//        return $result->rows;

        return pdo_fetchall( $sql );
    }

}