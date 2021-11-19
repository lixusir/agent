<?php
//namespace model;
//use model;

namespace sm_shop\model;
use sm_shop\model;
class bannerModel extends model{


    public static function findByName( $name ){
        global $_W;
        $sql = "select * from sh_banner where `status`='1' "
            . " and `name` ='" . $name . "'"
            . " and `uniacid` ='" . $_W['uniacid'] . "'";
//        $result = self::$db->query( $sql );
//        return !empty($result->row)?$result->row:false;

        $result = pdo_fetch( $sql );
        return !empty( $result ) ? $result : false;
    }

    public static function findImageById( $banner_id ){
        $sql = "select * from sh_banner_image where banner_id ='" . $banner_id . "' order by sort_order";
//        $result = self::$db->query( $sql );
//        return $result->rows;

        $result = pdo_fetchall( $sql );
        return $result;
    }

}