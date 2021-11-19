<?php

namespace sm_shop\admin\model;
use sm_shop\model;
class pickupModel extends model
{

    public static function get_list( $filter=[], $page=1, $page_num = 20 ){

        global $_W;
        $page = ' limit ' . ($page-1) * $page_num . ',' . $page * $page_num;

        $sql = 'select * from sh_pick_up where uniacid=' . $_W['uniacid'];

        $sql .= $page;


        return pdo_fetchall( $sql );
    }

    public static function info( $pickup_id ){

        $sql = 'select * from sh_pick_up where id=' . $pickup_id;
        return pdo_fetch( $sql );
    }

    public static function edit( $pickup_id, $data ){

        $sql = 'update  sh_pick_up set '
            . 'name="' . $data['name'] . '"'
            . ',phone="' . $data['phone'] . '"'
            . ',province="' . $data['province'] . '"'
            . ',city="' . $data['city'] . '"'
            . ',area="' . $data['area'] . '"'
            . ',street="' . $data['street'] . '"'
            . ',detail="' . $data['detail'] . '"'
            . ',latitude="' . $data['latitude'] . '"'
            . ',longitude="' . $data['longitude'] . '"'
            . ',status="' . $data['status'] . '"'
            . ' where id=' . $pickup_id;
        ;

        return pdo_query( $sql );

    }

    public static function add( $data ){
        global $_W;
        $sql = 'insert into sh_pick_up set '
            . 'name="' . $data['name'] . '"'
            . 'uniacid="' . $_W['uniacid'] . '"'
            . ',phone="' . $data['phone'] . '"'
            . ',province="' . $data['province'] . '"'
            . ',city="' . $data['city'] . '"'
            . ',area="' . $data['area'] . '"'
            . ',street="' . $data['street'] . '"'
            . ',detail="' . $data['detail'] . '"'
            . ',latitude="' . $data['latitude'] . '"'
            . ',longitude="' . $data['longitude'] . '"'
            . ',status="' . $data['status'] . '"'
        ;

        pdo_query( $sql );
        return pdo_insertid();

    }

    public static function remove( $pickup_id ){

        $sql = 'delete from sh_pick_up where id=' . $pickup_id;

        return pdo_query( $sql );

    }
}

