<?php/* * 人人商城 * * 青岛易联互动网络科技有限公司 * http://www.we7shop.cn * TEL: 4000097827/18661772381/15865546761 */if (!defined('IN_IA')) {    exit('Access Denied');}return array(    'version' => '1.0',    'id' => 'open_farm',    'name' => '人人农场',    'v3' => true,    'menu' => array(        'title' => '菜单',        'plugincom' => 1,        'icon' => 'page',        'items' => array(            array(                'title' => '用户统计',                'route' => '',            ),            // array(            //     'title' => '初次指示',            //     'route' => 'indicate',            // ),            array(                'title' => '公告管理',                'route' => 'notice',            ),            array(                'title' => '回复管理',                'route' => 'reply',            ),            array(                'title' => '心情管理',                'route' => 'mood',            ),            array(                'title' => '任务管理',                'route' => 'task',            ),            // array(            //     'title' => '广告管理',            //     'route' => 'advertisement',            // ),            array(                'title' => '等级管理',                'route' => 'grade',            ),            // array(            //     'title' => '集市管理',            //     'route' => 'market',            // ),            // array(            //     'title' => '订单管理',            //     'route' => 'order',            // ),            array(                'title' => '彩蛋管理',                'route' => 'surprised',            ),            array(                'title' => '农场设置',                'route' => 'seting',            ),            array(                'title' => '农场配置',                'route' => 'configure',            ),        )    ));