<?php
namespace sm_shop\admin\model;
use sm_shop\model;
class settingModel extends model{

    public static function set( $code, $key, $value ){

        global $_W;
        self::delete( $code, $key );

        $sql = "insert into sh_setting set `code`= '" . $code
            . "',`uniacid`='" . $_W['uniacid']
            . "',`key`='" . $key
            . "',`value`='" . $value . "'";

//        $result = self::$db->query( $sql );
//        return $result->num_rows;
        return pdo_query( $sql );

    }

    public static function delete( $code, $key ){

        global $_W;
        $sql = "delete from sh_setting where `code`='". $code
            ."' and `key`='". $key
            ."' and `uniacid`='". $_W['uniacid'] ."' "
        ;
//        $result = self::$db->query( $sql );
//
//        return $result->num_rows;

        return pdo_query( $sql );

    }

    public static function get( $code, $key ){

        global $_W;
        $sql = "select * from sh_setting where `code`='". $code
            ."' and `key`='". $key
            ."' and `uniacid`='". $_W['uniacid'] ."' "
        ;
//        $result = self::$db->query( $sql );
//        return !empty($result->row)?$result->row['value']:false;
        $result = pdo_fetch( $sql );
        return !empty( $result ) ? $result : false;
    }

    public static function getByCode( $code ){

        global $_W;
        $sql = "select * from sh_setting where `code`='". $code
            ."' and `uniacid`='". $_W['uniacid'] ."' "
        ;
        $result = pdo_fetchall( $sql );
        return $result;
    }

    public static function get_all(){

        global $_W;
        $sql = "select * from sh_setting where `uniacid`=" . $_W['uniacid'] ;
//        $result = self::$db->query( $sql );
//        return $result->rows;
        return pdo_fetchall( $sql );
    }

    public static function set_all( $data ){
        global $_W;

//        $del_sql = "truncate table sh_setting";
//        pdo_query( $del_sql );

        $sql = "INSERT INTO sh_setting (`uniacid`,`code`,`key`,`value`) VALUES ";

        $values = [];
        $codes = [];
        foreach( $data as $val ){

            $str = "'".$_W['uniacid'] . "','" . $val['code'] ."','" . $val['key'] . "','" . $val['value']."'";
            $values[] = "(" . $str . ")";

            if( !in_array($val['code'], $codes) ){
                $codes[] =  $val['code'];
            }

        }

//        $codes_str = implode("','", $codes);
//        $codes_str = "'" . $codes_str . "'";
//        $del_sql = "delete from sh_setting where uniacid= " . $_W['uniacid'] . " and code in (" . $codes_str . ")";

        if(!empty( $values ) ){

            foreach( $codes as $del_code ){
                $del_sql = "delete from sh_setting where uniacid= " . $_W['uniacid'] . " and code = '" . $del_code . "'";
                $ret_del = pdo_query( $del_sql );
            }
            $sql .= implode(',', $values );
            $ret_insert = pdo_query( $sql );

            return $ret_insert;


        }

        return 0;


    }


}