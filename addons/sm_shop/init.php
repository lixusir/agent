<?php

require_once IA_ROOT . '/addons/sm_shop/controller.php';
require_once IA_ROOT . '/addons/sm_shop/model.php';
require_once IA_ROOT . '/addons/sm_shop/lib/utf8.php';
error_reporting( 0 );
ini_set('memory_limit',"500M");
if( !empty( $_SERVER['HTTP_ORIGIN']) ){
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
//跨域问题, 否则session无法保存
//        header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods:GET,POST");
header("Access-Control-Allow-Headers:Content-Type");
header("Content-Type: text/html;charset=utf-8");


function loadClass( $class_name ){

    $split = explode('\\', $class_name );

    $class_path = IA_ROOT . '/addons/' .  str_replace( '\\','/', $class_name ) . '.php';

    if( file_exists( $class_path ) ){
        require_once( $class_path );
    }


}
spl_autoload_register('loadClass' );

// todo 获取路由 , 进入到指定控制器 和方法


$route = !empty( $_GET['r'] ) ? $_GET['r'] : 'goods.page_list';

$route_arr = explode( '.', $route );

if( count($route_arr) == 1 ){
    $controller_name = $route_arr[0];
    $method_name = 'index';
    $dir ='';
}else if( count($route_arr) == 2 ){
    $controller_name = $route_arr[0];
    $method_name = $route_arr[1];
    $dir ='';
}else if( count($route_arr) == 3 ){
    $controller_name = $route_arr[1];
    $method_name = $route_arr[2];
    $dir = $route_arr[0];
}
global $_W;
$module_name = 'sm_shop';
if($_W['current_module']){
    $module_name = $_W['current_module']['name'];
}
if( strpos($_SERVER['PHP_SELF'], 'app/')!==false ){
    $controller_path = '\\' . $module_name . '\\api\\';
} else if( strpos($_SERVER['PHP_SELF'], 'xcx.php')!==false ) {
    $controller_path = '\\' . $module_name . '\\api\\';
} else{
    $controller_path = '\\' . $module_name . '\\admin\\api\\';
}
//$controller_path = strpos($_SERVER['PHP_SELF'], 'app/')!==false  ? '\\sm_shop\\api\\' : '\\sm_shop\\admin\\api\\';
$controller_path .= !empty( $dir ) ? $dir.'\\' : '';
$controller = $controller_path . $controller_name;



if( class_exists( $controller ) ){

    $controller_obj = new $controller;
//    $methods = !empty($route_arr[1])?$route_arr[1]:'index';

    $controller_obj( $method_name );

}else{
    echo 'API 不存在';
}












