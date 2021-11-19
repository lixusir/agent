<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\7\17 0017
 * Time: 9:23
 * 模型 基类
 */
namespace sm_shop;
abstract class model {

    protected static $db = null;



    public static function setDB( $db ){
        self::$db = $db;
    }


}