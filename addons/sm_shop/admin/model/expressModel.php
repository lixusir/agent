<?php
namespace sm_shop\admin\model;
use sm_shop\model;
class expressModel extends model{

    public static function get_list(){

        $sql = 'select * from sh_express where status=1';

        return pdo_fetchall( $sql );

    }

}