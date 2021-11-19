<?php

define('DEVELOPMENT', true);
define('IMS_FAMILY', "v");
define('IN_IA', 1);
define('MAGIC_QUOTES_GPC', (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) || @ini_get('magic_quotes_sybase'));
define('IA_ROOT', str_replace('\\', '/', dirname(dirname(dirname(__FILE__)))));

error_reporting(0);

if( empty( $_GET['r'] ) ){
    $_GET['r'] = 'home';
}
global $_W, $_GPC;
$_W = $_GPC = array();
//$sitepath = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
$sitepath = '/';
$_W['siteroot'] = htmlspecialchars('http://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $sitepath);

$_W['uniacid'] = $_GET['i'];

require_once( IA_ROOT . '/data/config.php');
$_W['config'] = $config;
$_W['config']['db']['tablepre'] = !empty($_W['config']['db']['master']['tablepre']) ? $_W['config']['db']['master']['tablepre'] : $_W['config']['db']['tablepre'];
define('IN_UC', true);
//include_once(IA_ROOT.'/framework/library/uc/model/base.php');

require_once( IA_ROOT . '/framework/const.inc.php');
require_once( IA_ROOT . '/framework/class/loader.class.php');
require_once( IA_ROOT . '/framework/function/cache.func.php');
load()->model('account');
//require_once( IA_ROOT . '/framework/model/account.mod.php');
require_once( IA_ROOT . '/framework/function/global.func.php');
require_once( IA_ROOT . '/framework/function/pdo.func.php');

// 微擎20200828更新后，找不到下面的系统函数:uni_setting
if (!function_exists('uni_setting')) {
    function uni_setting($uniacid = 0, $fields = '*', $force_update = false) {
        global $_W;
        load()->model('account');
        if ($fields == '*') {
            $fields = '';
        }
        return uni_setting_load($fields, $uniacid);
    }
}

define('CLIENT_IP', getip());
if ($_W['config']['setting']['development'] == 1) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL ^ E_NOTICE);
}

if (MAGIC_QUOTES_GPC) {
    $_GET = istripslashes($_GET);
    $_POST = istripslashes($_POST);
    $_COOKIE = istripslashes($_COOKIE);
}
foreach ($_GET as $key => $value) {
    if (is_string($value) && !is_numeric($value)) {
        $value = safe_gpc_string($value);
    }
    $_GET[$key] = $_GPC[$key] = $value;
}
$cplen = strlen($_W['config']['cookie']['pre']);
foreach ($_COOKIE as $key => $value) {
    if ($_W['config']['cookie']['pre'] == substr($key, 0, $cplen)) {
        $_GPC[substr($key, $cplen)] = $value;
    }
}

unset($cplen, $key, $value);

$_GPC = array_merge($_GPC, $_POST);
$_GPC = ihtmlspecialchars($_GPC);
$_GPC['c'] = !empty($_GPC['c'])?$_GPC['c']:'';
$_GPC['a'] = !empty($_GPC['a'])?$_GPC['a']:'';
$_GPC['m'] = !empty($_GPC['m'])?$_GPC['m']:'';
$_GPC['do'] = !empty($_GPC['do'])?$_GPC['do']:'';


if( !empty($_GPC['openid']) ){
    $_W['openid'] = $_GPC['openid'];
    $params = [
        'openid'=>$_GPC['openid']
    ];

    $sql = 'select * from sh_customer_xcx cx inner join sh_customer c on cx.telephone = c.telephone where cx.open_id="' . $_W['openid'] . '"';
    $_W['customer'] = pdo_fetch( $sql );
    $_W['platform'] = 'wxapp';
/*
    $fan = pdo_get('mc_mapping_fans', $params );

    if( empty($fan) ){


        $record = array(
            'openid' => $_GPC['openid'],
            'uid' => 0,
            'acid' => $_W['uniacid'],
            'uniacid' => $_W['uniacid'],
            'salt' => random(8),
            'updatetime' => TIMESTAMP,
            'nickname' => '',
            'follow' => 0,
            'followtime' => 0,
            'unfollowtime' => 0,
            'tag' => base64_encode(iserializer('')),
            'unionid' => '',
            'user_from' => 1, // 1: 小程序，0：公众号
        );

        $default_groupid = table('mc_groups')
            ->where(array(
                'uniacid' => $_W['uniacid'],
                'isdefault' => 1
            ))
            ->getcolumn('groupid');
        $data = array(
            'uniacid' => $_W['uniacid'],
            'email' => md5($oauth['openid']).'@we7.cc',
            'salt' => random(8),
            'groupid' => $default_groupid,
            'createtime' => TIMESTAMP,
            'password' => md5('xcx' . $data['salt'] . $_W['config']['setting']['authkey']),
            'nickname' => '',
            'avatar' => '',
            'gender' => '',
            'nationality' => '',
            'resideprovince' => '',
            'residecity' => '',
        );
        table('mc_members')
            ->fill($data)
            ->save();
        $uid = pdo_insertid();
        $record['uid'] = $uid;
        $_SESSION['uid'] = $uid;
        table('mc_mapping_fans')->fill($record)->save();

        $fan = table('mc_mapping_fans')->searchWithOpenid($_GPC['openid']);
    }
    load()->model('mc');
    $_W['member'] = mc_fetch( $_GPC['openid'] );
*/




}


require "./init.php";



