<?php

namespace sm_shop\admin\model;
use sm_shop\model;
class bannerModel extends model
{

    public static function get_banner_info( $banner_id ){

        $sql = "select * from sh_banner where banner_id=" . $banner_id;

//        $query = self::$db->query( $sql );
//
//        return $query->row;

        return pdo_fetch( $sql );

    }

    public static function get_banner_image_list( $banner_id ){


        $sql = "select * from sh_banner_image where banner_id=" . $banner_id;

//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );
    }

    public static function get_list($filter = [])
    {

        global $_W;
        $sql = "select * from sh_banner where uniacid=" . $_W['uniacid'];

//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );
    }

    public static function banner_image_remove( $banner_id ){

        $sql = "delete from sh_banner_image where banner_id= " . $banner_id;
//        $query = self::$db->query( $sql );
//        return $query->rows;
        return pdo_fetchall( $sql );
    }
    public static function banner_image_set( $banner_id, $data ){

        $values = '';

        foreach( $data as $val ){

            $values .= "(" ;
            $values .= "'" . $banner_id . "',";
            $values .= "'" . $val['title'] . "',";
            $values .= "'" . $val['link'] . "',";
            $values .= "'" . $val['image'] . "',";
            $values .= "'" . $val['sort_order'] . "'";
            $values .=  "),";
        }

        $values = trim( $values , ',') ;
        $sql = "insert into sh_banner_image (`banner_id`,`title`,`link`,`image`,`sort_order`) values " . $values ;
//        $query = self::$db->query( $sql );
//        return $query->num_rows;

        return pdo_query( $sql );
    }

    public static function edit( $banner_id, $data ){

        $sql = "update sh_banner set `name`= '" . $data['name']
            . "', `status`=  " . $data['status']
            . ", `image_width`=  '" . $data['image_width'] ."'"
            . ", `image_height`=  '" . $data['image_height'] ."'"
            . " where banner_id=" . $banner_id;

//        $query = self::$db->query( $sql );
//
//        return $query->num_rows;

        return pdo_query( $sql );
    }

    public static function create( $data ){

        global $_W;
        $sql = "insert into sh_banner set `name`= '" . $data['name']
            . "', `uniacid`=  '" . $_W['uniacid']
            . "', `image_width`=  '" . $data['image_width']
            . "', `image_height`=  '" . $data['image_height']
            . "', `status`=  " . $data['status'];

//        self::$db->query( $sql );
//
//        return self::$db->getLastId();
        pdo_query( $sql );
        return pdo_insertid();

    }


    public static function remove( $banner_id ){

        $sql = "delete from sh_banner "
            . " where banner_id=" . $banner_id;

//        $query = self::$db->query( $sql );
//
//        return $query->num_rows;
        return pdo_query( $sql );
    }
    public static function image_list_remove( $banner_id ){

        $sql = "delete from sh_banner_image "
            . " where banner_id=" . $banner_id;

//        $query = self::$db->query( $sql );
//
//        return $query->num_rows;
        return pdo_query( $sql );
    }

}