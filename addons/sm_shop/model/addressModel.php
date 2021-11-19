<?php
namespace sm_shop\model;
use sm_shop\model;
class addressModel extends model{

    public static function get_list( $customer_id ){

        $sql = "SELECT * FROM sh_customer_address WHERE customer_id='" . $customer_id . "';";
//        $result = self::$db->query( $sql );
//        return $result->rows;

        return pdo_fetchall( $sql );

    }

    public static function single( $address_id, $customer_id ){

        $sql = "SELECT * FROM sh_customer_address WHERE id='" . $address_id . "' and customer_id='" . $customer_id . "';";
//        $result = self::$db->query( $sql );
//        return !empty($result->row)?$result->row:false;
        $row = pdo_fetch( $sql );
        return !empty( $row ) ? $row : false;
    }

    public static function add( $info ){

        $sql = "INSERT INTO sh_customer_address SET " ." customer_id='" . $info['customer_id'] . "',"
            ." `name`='" . $info['name'] . "',"
            ." telephone='" . $info['telephone'] . "',"
            ." detail_address='" . $info['detail_address'] . "',"
            ." province_id='" . $info['province_id'] . "',"
            ." province_name='" . $info['province_name']. "',"
            ." city_id='" . $info['city_id'] . "',"
            ." city_name='" . $info['city_name'] . "',"
            ." area_id='" . $info['area_id'] . "',"
            ." area_name='" . $info['area_name'] . "',"
            ." is_default='" . $info['is_default'] . "'";

//        $result = self::$db->query( $sql );
//        return $result;

        return pdo_query( $sql );
    }

    public static function edit( $id, $info ){

        $sql = "update  sh_customer_address SET "
            ." customer_id='" . $info['customer_id'] . "',"
            ." `name`='" . $info['name'] . "',"
            ." telephone='" . $info['telephone'] . "',"
            ." detail_address='" . $info['detail_address'] . "',"
            ." province_id='" . $info['province_id']. "',"
            ." province_name='" . $info['province_name'] . "',"
            ." city_id='" . $info['city_id'] . "',"
            ." city_name='" . $info['city_name'] . "',"
            ." area_id='" . $info['area_id'] . "',"
            ." area_name='" . $info['area_name'] . "',"
            ." is_default='" . $info['is_default'] . "'"
            ." where id='" . $id . "'";

        ;

//        $result = self::$db->query( $sql );
//        return $result->num_rows;

        return pdo_fetchall( $sql );
    }

    public function remove( $id, $customer_id ){
        $sql = 'delete from sh_customer_address where id="' . $id
            . '" and customer_id="' . $customer_id . '"';

//        return self::$db->query( $sql );
        return pdo_query( $sql );

    }

    public static function getDefault( $customer_id ){

        $sql = 'select * from sh_customer_address where is_default=1'
            . ' and customer_id="' . $customer_id .'"';

        $default = pdo_fetch( $sql );

        if( empty($default) ){

            $sql = 'select * from sh_customer_address where customer_id="' . $customer_id .'"';
//            print_r( $sql );
            $default = pdo_fetch( $sql );
        }

        return $default;

    }

    public static function setDefault( $id, $customer_id ){

        $sql = 'update sh_customer_address set is_default=0 '
            . ' and customer_id="' . $customer_id . '"';
//        self::$db->query( $sql );
        pdo_query( $sql );
        $sql = 'update sh_customer_address set is_default=1 where id="' . $id
            . '" and customer_id="' . $customer_id .'"';
//        return self::$db->query( $sql );
        return pdo_query( $sql );
    }

}