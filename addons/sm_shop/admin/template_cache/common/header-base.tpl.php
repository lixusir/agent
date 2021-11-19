<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <?php if(!defined('FRAME')) { ?><?php define('FRAME', '')?><?php } ?>
	<?php $frames = buildframes(FRAME);_calc_current_frames($frames);?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php if(!empty($_W['page']['title'])) { ?><?php echo $_W['page']['title'];?><?php } ?><?php if(empty($_W['page']['copyright']['sitename'])) { ?><?php if(IMS_FAMILY != 'x') { ?><?php if(!empty($_W['page']['title'])) { ?> - <?php } ?>微擎 - 公众平台自助引擎 -  Powered by W7.CC<?php } ?><?php } else { ?><?php if(!empty($_W['page']['title'])) { ?> - <?php } ?><?php echo $_W['page']['copyright']['sitename'];?><?php } ?></title>
	<meta name="keywords" content="<?php if(empty($_W['page']['copyright']['keywords'])) { ?><?php if(IMS_FAMILY != 'x') { ?>微擎,微信,微信公众平台,w7.cc<?php } ?><?php } else { ?><?php echo $_W['page']['copyright']['keywords'];?><?php } ?>" />
	<meta name="description" content="<?php if(empty($_W['page']['copyright']['description'])) { ?><?php if(IMS_FAMILY != 'x') { ?>公众平台自助引擎（www.w7.cc），简称微擎，微擎是一款免费开源的微信公众平台管理系统，是国内最完善移动网站及移动互联网技术解决方案。<?php } ?><?php } else { ?><?php echo $_W['page']['copyright']['description'];?><?php } ?>" />
	<link rel="shortcut icon" href="<?php if(!empty($_W['setting']['copyright']['icon'])) { ?><?php echo to_global_media($_W['setting']['copyright']['icon'])?><?php } else { ?>./resource/images/favicon.ico<?php } ?>" />
	<link href="./resource/css/bootstrap.min.css?v=<?php echo IMS_RELEASE_DATE;?>" rel="stylesheet">
	<link href="/addons/sm_shop/admin/assets/css/datetimepicker.css" rel="stylesheet">
	<link href="./resource/css/common.css?v=<?php echo IMS_RELEASE_DATE;?>" rel="stylesheet">
	<link href="/addons/sm_shop/admin/assets/print/print.min.css" rel="stylesheet">
	<link href="/addons/sm_shop/admin/assets/css/file_manager.css" rel="stylesheet">
<!--	<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">-->
	<link rel="stylesheet" href="/addons/sm_shop/admin/assets/element-ui@2.14.1/index.css">
<!--	<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>-->
	<script src="/addons/sm_shop/admin/assets/js/vue@2.6.11.js"></script>
<!--	<script src="https://unpkg.com/element-ui/lib/index.js"></script>-->
	<script src="/addons/sm_shop/admin/assets/element-ui@2.14.1/index.js"></script>
<!--	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>-->
	<script src="/addons/sm_shop/admin/assets/js/axios.min.js"></script>
	<script src="/addons/sm_shop/admin/assets/js/common.js"></script>

<!--	<script src="/addons/sm_shop/admin/assets/js/require.js"></script>-->
<!--	<script src="/addons/sm_shop/admin/assets/js/config1.0.js"></script>-->
<!--	<script src="/addons/sm_shop/admin/assets/js/myconfig.js"></script>-->
	<script type="text/javascript">
	if(navigator.appName == 'Microsoft Internet Explorer'){
		if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
			alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
		}
	}
	window.sysinfo = {
		<?php if(!empty($_W['uniacid'])) { ?>'uniacid': '<?php echo $_W['uniacid'];?>',<?php } ?>
		<?php if(!empty($_W['acid'])) { ?>'acid': '<?php echo $_W['acid'];?>',<?php } ?>
		<?php if(!empty($_W['openid'])) { ?>'openid': '<?php echo $_W['openid'];?>',<?php } ?>
		<?php if(!empty($_W['uid'])) { ?>'uid': '<?php echo $_W['uid'];?>',<?php } ?>
		<?php if(!empty($_W['role'])) { ?>'role': '<?php echo $_W['role'];?>',<?php } ?>
        <?php if(!empty($_W['highest_role'])) { ?>'highest_role': '<?php echo $_W['highest_role'];?>',<?php } ?>
		'isfounder': <?php if(!empty($_W['isfounder'])) { ?>1<?php } else { ?>0<?php } ?>,
		'family': '<?php echo IMS_FAMILY;?>',
		'siteroot': '<?php echo $_W['siteroot'];?>',
		'siteurl': '<?php echo $_W['siteurl'];?>',
		'attachurl': '<?php echo $_W['attachurl'];?>',
		'attachurl_local': '<?php echo $_W['attachurl_local'];?>',
		'attachurl_remote': '<?php echo $_W['attachurl_remote'];?>',
		'module' : {'url' : '<?php if(defined('MODULE_URL')) { ?><?php echo MODULE_URL;?><?php } ?>', 'name' : '<?php if(defined('IN_MODULE')) { ?><?php echo IN_MODULE;?><?php } ?>'},
		'cookie' : {'pre': '<?php echo $_W['config']['cookie']['pre'];?>'},
		'account' : <?php echo json_encode($_W['account'])?>,
		'server' : {'php' : '<?php echo phpversion()?>'},
		'frame': '<?php echo FRAME;?>',
	};
	</script>

	<script>var require = { urlArgs: 'v=<?php echo IMS_RELEASE_DATE;?>' };</script>
	<script type="text/javascript" src="./resource/js/lib/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="./resource/js/lib/jquery-ui-1.10.3.min.js"></script>
	<script type="text/javascript" src="./resource/js/lib/bootstrap.min.js"></script>
	<script type="text/javascript" src="./resource/js/app/util.js?v=<?php echo IMS_RELEASE_DATE;?>"></script>
	<script type="text/javascript" src="./resource/js/app/common.min.js?v=<?php echo IMS_RELEASE_DATE;?>"></script>
	<script type="text/javascript" src="/addons/sm_shop/admin/assets/print/print.min.js"></script>
	<script type="text/javascript" src="./resource/js/require.js?v=<?php echo IMS_RELEASE_DATE;?>"></script>

	<script type="text/javascript" src="./resource/js/lib/jquery.nice-select.js?v=<?php echo IMS_RELEASE_DATE;?>"></script>

	<script src="/addons/sm_shop/admin/assets/js/bootstrap-datetimepicker.min.js"></script>
	<style>
		.datetimepicker{
			font-size:10px;
		}
		.datetimepicker .table-condensed{
			width:240px;
		}
		.datetimepicker .switch::before,.datetimepicker .switch::after{
			content:none;
		}
	</style>
	<script>
		$.fn.datetimepicker.dates['zh-CN'] = {
			days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
			daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
			daysMin:  ["日", "一", "二", "三", "四", "五", "六", "日"],
			months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
			monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
			today: "今天",
			suffix: [],
			meridiem: ["上午", "下午"]
		};
	</script>
</head>
<body>
	<div class="loader" style="display:none">
		<div class="la-ball-clip-rotate">
			<div></div>
		</div>
	</div>
<?php include $this->template('common/file-manager', TEMPLATE_INCLUDEPATH, "sm_shop")?>