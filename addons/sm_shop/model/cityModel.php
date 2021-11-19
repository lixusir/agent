<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\7\17 0017
 * Time: 11:29
 */
namespace sm_shop\model;
use sm_shop\model;
class cityModel extends model{


    public static function get_city_list( $povince_id ){

        $sql = "select * from sh_city where zone_id='" . $povince_id
            . "' and up_id=0";
//        $result = self::$db->query( $sql );
//        return $result->rows;
        return pdo_fetchall( $sql );
    }

    public static function get_district_list( $city_id ){
        $sql = "select * from sh_city where up_id=" . $city_id;
//        $result = self::$db->query( $sql );
//        return $result->rows;
        return pdo_fetchall( $sql );
    }

}