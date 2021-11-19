<?php
namespace sm_shop\admin\model;
use sm_shop\model;
class moduleModel extends model{

    public static function getRecommendGoods( $recommend_id ){

        $sql = "select * from sh_recommend_goods where recommend_id='" . $recommend_id . "'";
//        $result = self::$db->query( $sql );
//        return $result->rows;

        return pdo_fetchall( $sql );
    }

    public static function findByKey( $key ){

        global $_W;
        $sql = "select * from sh_module where `key`='" . $key . "' and uniacid=" . $_W['uniacid'];
//        $result = self::$db->query( $sql );
//        return $result->row;
        return pdo_fetch( $sql );

    }

    public static function deleteByKey( $key ){
        global $_W;
        $sql = 'delete from sh_module where '
            .' `key`="' . $key . '"'
            .' and `uniacid`="' . $_W['uniacid'] . '"'
        ;
//        $result = self::$db->query( $sql );
//        return $result->row;
        return pdo_fetch( $sql );

    }

    public static function save( $data ){

        global $_W;
        $sql = "insert into sh_module set  `key`='" . $data['key']
            . "', title='" .$data['title']
            . "', uniacid='" .$_W['uniacid']
            . "', status='" .$data['status']
            . "', settings='" .$data['settings']
            . "'";
//        $result = self::$db->query( $sql );
//
//        return $result->row;

        return pdo_fetch( $sql );

    }


}