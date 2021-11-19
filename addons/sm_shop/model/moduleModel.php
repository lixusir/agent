<?php
//namespace model;
//use model;

namespace sm_shop\model;
use sm_shop\model;

class moduleModel extends model{

    public static function getRecommend( ){

        global $_W;
        $sql = "select * from sh_module where `key`='recommend' and `status`=1 and uniacid=" . $_W['uniacid'];

        $result = pdo_fetch( $sql );
        return $result;

    }

    public static function home_category( ){
        global $_W;
        $sql = "select * from sh_module where `key`='home_category' and `status`=1 and uniacid=" . $_W['uniacid'];
        $result = pdo_fetch( $sql );
        return $result;

    }



}