<?php
//namespace model;
//use model;

namespace sm_shop\model;
use sm_shop\model;
class brandModel extends model{

    public static function get_list(  ){

        $sql = 'select * from sh_brand ';
        return pdo_fetchall( $sql );

    }

}