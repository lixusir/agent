<?php
namespace sm_shop\model;
use sm_shop\model;
class pickupModel extends model{


    public static function get_list( $filter, $page=1, $page_num=20){

        global $_W;
        $limit = ' limit ' . ( $page - 1 ) * $page_num . ', '. $page * $page_num;
        $sql = 'select * from sh_pick_up where status=1 and uniacid=' . $_W['uniacid'];
        $sql .= $limit;

        return pdo_fetchall( $sql );

    }

}