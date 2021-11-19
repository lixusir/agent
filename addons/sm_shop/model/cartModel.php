<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\7\17 0017
 * Time: 11:29
 */
namespace sm_shop\model;
use sm_shop\model;
class cartModel extends model{



    public static function get_list( $user_id, $cart_ids ){

        $sql = "SELECT c.*, g.price, g.name, gao.price as option_price FROM sh_cart c  LEFT JOIN sh_goods g  ON c.goods_id = g.`id` "
                . " left join sh_goods_attr_object gao on c.goods_option=gao.attr and c.goods_id=gao.goods_id "
                . " where user_id=" . $user_id;

        $where_id = [];
        if( !empty( $cart_ids ) ){
            foreach( $cart_ids as $cart_id ){
                $where_id[] = 'c.id='. $cart_id;
            }
            $sql .= ' and ( ' .implode( ' or ', $where_id) . ') ';
        }

//        $result = self::$db->query( $sql );
//
//
//        return $result->rows;
        return pdo_fetchall( $sql );

    }


    public static function filter( $user_id, $cart_ids ){

        $sql = "SELECT c.*, g.price, g.name FROM sh_cart c  LEFT JOIN sh_goods g  ON c.goods_id = g.`id` ".
            " where user_id=" . $user_id;
//        $result = self::$db->query( $sql );
//
//
//        return $result->rows;
        return pdo_fetchall( $sql );
    }

    public static function add( $product_id, $goods_option, $number, $user_id ){

        global $_W;
        $sql = 'SELECT * FROM sh_cart WHERE user_id=' . $user_id
            .' and goods_id=' . $product_id
            .' and goods_option="' . $goods_option . '"';

        $result_row = pdo_fetch( $sql );


        if( !empty( $result_row) ){
            $number = $result_row['goods_num'] + $number;
            $cart_sql = 'update sh_cart set user_id='. $user_id
                .',goods_id=' . $product_id
                .',goods_option="' . $goods_option . '"'
                .',goods_num=' . $number
                .' where id=' . $result_row['id']
            ;
        }else{
            $cart_sql = 'insert into sh_cart set user_id='. $user_id
                .',goods_id=' . $product_id
                .',uniacid=' . $_W['uniacid']
                .',goods_option="' . $goods_option . '"'
                .',goods_num=' . $number;
        }

//        self::$db->query( $cart_sql );

        return pdo_query( $cart_sql );
    }

    public static function change( $cart_id, $number ){

        $sql = "update sh_cart set goods_num=". $number . " where id=" . $cart_id;
//        $res = self::$db->query( $sql );
//        return $res;

        return pdo_query( $sql );
    }

    public static function remove( $cart_id ){

        $sql = 'delete from sh_cart where id=' . $cart_id;

//        $res = self::$db->query( $sql );
//
//        return $res;
        return pdo_query( $sql );
    }

    public static function removeMore( $cart_ids ){

        if( is_array( $cart_ids) ){
            $in_values = implode(',',$cart_ids );
        }else{
            $in_values = $cart_ids;
        }


        $sql = "delete from sh_cart where id in(" . $in_values . ")";

//        $res = self::$db->query( $sql );
//
//        return $res;
        return pdo_query( $sql );
    }


}