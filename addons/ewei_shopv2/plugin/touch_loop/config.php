<?php
/*
 * 人人商城
 *
 * 青岛易联互动网络科技有限公司
 * http://www.we7shop.cn
 * TEL: 4000097827/18661772381/15865546761
 */
if(!defined('IN_IA')) {
     exit('Access Denied');
}

return array(
    'version'=>'1.0',
    'id'=>'touch_loop',
    'name'=>'一键发圈',
    'v3'=>true,
    'menu'=>array(
        'plugincom'=>1,
        'icon'=>'page',
        'items'=>array(
            array(
                'title'=>'设置',
                'route'=>'setting'
            ),
            array(
                'title'=>'素材管理',
                'route'=>''
            ),
            array(
                'title'=>'海报设置',
                'route'=>'poster'
            )
        )
    )
);

