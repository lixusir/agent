<?php
echo "\r\n";

if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'poster',
	'name'    => '超级海报',
	'v3'      => true,
	'menu'    => array(
		'title'     => '分享海报',
		'plugincom' => 1,
		'icon'      => 'mendianguanli',
		'items'     => array(
			array(
				'title'   => '海报管理',
				'route'   => '',
				'extends' => array('poster.scan')
				)
			)
		)
	);

?>
