<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header-base', TEMPLATE_INCLUDEPATH, "sm_shop")?>
<link rel="stylesheet" href="/addons/sm_shop/admin/assets/css/stylesheet.css?v=0.2">
<link rel="stylesheet" href="/addons/sm_shop/admin/assets/css/font-awesome.min.css">


<header id="header" class="navbar navbar-static-top">
    <div class="container-fluid">
        <div id="header-logo" class="navbar-header">
            <a
               class="navbar-brand">
                <!--                    <img src="view/image/logo.png" alt="OpenCart" title="OpenCart">-->
                神秘商城
            </a>
        </div>
        <a href="#" id="button-menu"><span class="fa fa-bars"></span></a>
        <a style="line-height: 60px; margin-left: 25px;" >青岛红树林信息技术有限公司开发，技术支持电话（微信）：15244237038</a>
        <a class="app-entry" style="line-height: 60px;float: right;margin-right: 25px;"
           target="_blank"
           href="<?php echo $this->url_host .'/app/index.php?c=entry&a=site&m=sm_shop&do=mobile&i=' . $_W['uniacid']?>">前台入口</a>
    </div>
</header>

