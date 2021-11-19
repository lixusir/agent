<?php
namespace sm_shop\model;
use sm_shop\model;
class settingModel extends model{

    public static $settings = [];

    public static function get( $code, $key ){

        global $_W;
        $sql = "select * from sh_setting where `code`='". $code
            ."' and `key`='". $key ."' and uniacid=" . $_W['uniacid'];
        $result = pdo_fetch( $sql );
        return !empty( $result ) ? $result : false;
    }

    public static function getItem( $code, $key ){
        global $_W;
        $sql = "select value from sh_setting where `code`='". $code
            ."' and `key`='". $key ."' "
            ." and `uniacid`=". $_W['uniacid']
        ;
        $result = pdo_fetchcolumn( $sql );
        return !empty( $result ) ? $result : false;
    }

    public static function get_all(){
        global $_W;
        if( empty($settings ) ){
            $sql = "select * from sh_setting where `uniacid`=". $_W['uniacid'];
            $settings = pdo_fetchall( $sql );
        }
        return $settings;
    }

    public static function get_live(){

        $settings = self::get_all();

        $live_ali = [];
        foreach ( $settings as $item ){
            if( $item['code'] == 'live_ali' ){

                $live_ali[ $item['key'] ] = $item['value'];

            }
        }

        return $live_ali;


    }

    public static function get_xcx(){

        $settings = self::get_all();

        $res = [];
        foreach ( $settings as $item ){
            if( $item['code'] == 'xcx' ){

                $res[ $item['key'] ] = $item['value'];

            }
        }

        return $res;


    }

    public static function get_config(){

        $settings = self::get_all();

        $res = [];
        foreach ( $settings as $item ){
            if( $item['code'] == 'config' ){

                $res[ $item['key'] ] = $item['value'];

            }
        }

        return $res;


    }


}