<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('diypage/_common', TEMPLATE_INCLUDEPATH)) : (include template('diypage/_common', TEMPLATE_INCLUDEPATH));?>
<style>
    /*装修组件的复制*/
    .diy-toolbar{
        width: 132px;
        height: 48px;
        background: rgba(0,0,0,0.4);
        border-radius: 6px;
        position: absolute;
        left: 0;
        top: -59px;
        padding: 0 10px;

    }
    .diy-toolbar .item{
        width: 56px;
        height: 100%;
        text-align: center;
        color: #fff;
        font-size: 12px;
        float: left;
        cursor: pointer;
    }
    .diy-toolbar .item p{
        width: 100%;
    }
    .diy-toolbar .item p.icow{
        margin-top: 7px;
        margin-bottom: 6px;
    }
    .diy-toolbar .item p.txt{
        line-height: 1;
    }
    .hotareaModal {
        z-index: 2060 !important;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        /*display: none;*/
        overflow: hidden;
        -webkit-overflow-scrolling: touch;
        outline: 0;
        background-color: rgba(0,0,0,0.5);
        -webkit-transition: opacity .15s linear;
        -o-transition: opacity .15s linear;
        transition: opacity .15s linear;
    }
    .hotareaCont {
        width: 610px;
        z-index: 2061 !important;
        margin: 200px auto 0;
        outline: 0;
        position: relative;
        background-color: #fff;
        border-radius: 2px;
        box-sizing: border-box;
        background-clip: padding-box
    }
    .hotHeader {
        border-bottom: 1px solid #e9edef;
        padding: 14px 16px;
        line-height: 1;
    }
    .hotHeader .header-inner {
        font-size: 16px;
        font-weight: bold;
        line-height: 22px;
        display: inline-block;
        width: 100%;
        height: 20px;
        color: #17233d;
        box-sizing: border-box;
    }
    .hotHeader .hotClose {
        z-index: 1;
        font-size: 24px;
        position: absolute;
        right: 8px;
        top: 8px;
        cursor: pointer;
        transition: color .2s ease;
    }
    .hotBody {
        padding: 20px 20px 0 20px;
        overflow-y: auto;
        max-height: 600px;
    }
    .hotBody .step {
        margin-bottom: 20px;
        padding: 2.5px;
        box-sizing: border-box;
        display: flex;
        flex-direction: row;
    }
    .hotBody .step .step-item {
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .hotBody .step .step-item .item-circle {
        margin-right: 4px;
        border: 1px solid #e9edef;
        border-radius: 50%;
        height: 25px;
        width: 25px;
        font-size: 14px;
        text-align: center;
        line-height: 25px;
        color: #b8b9bd;
        background: #ffffff;
    }
    .hotBody .step .step-item .item-text {
        font-size: 12px;
        line-height: 16px;
        color: #636669;
    }
    .hotBody .step .step-item img {
        margin: 0 12px;
    }
    .hotBody .min-box .hotContainer {
        position: relative;
        width: 570px;
        user-select: none;
    }
    .hotBody .min-box .hotContainer img {
/*        position: absolute;
        top: 0;
        left: 0;*/
        width: 100%;
    }
    .hotBody .min-box .hotContainer .dragable-item {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(45, 140, 240, 0.6);
        border: 1px solid #2d8cf0;
        position: absolute;
        cursor: all-scroll;
    }
    .hotBody .min-box .hotContainer .draggable .handle {
        width: 8px;
        height: 8px;
        background: #fff;
        border: 1px solid #2d8cf0;
        -webkit-box-shadow: 0 0 2px #bbb;
        box-shadow: 0 0 2px #bbb;
        position: absolute;
        /*display: none;*/
    }
    .hotBody .min-box .hotContainer .draggable .addUrl {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 18px;
        font-size: 12px;
        line-height: 18px;
        color: #ffffff;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        cursor: pointer;
        /*display: none;*/
    }
    .hotBody .min-box .hotContainer .draggable .delHot{
        position: absolute;
        top: -10px;
        right: -12px;
        font-size: 18px;
        font-weight: 400;
        height: 18px;
        line-height: 18px;
        width: 18px;
        text-align: center;
        background: #636669;
        color: #fff;
        border-radius: 50%;
        cursor: pointer;
        z-index: 999;
    }
    .hotfooter {
        border-top: 1px solid #e9edef;
        padding: 12px 18px 12px 18px;
        text-align: center;
    }
    .hotfooter .hotsave {
        padding: 4px 15px;
        border-radius: 2px;
        font-weight: normal;
        font-size: 14px;
        line-height: 20px;
        min-width: 66px;
        margin-right: 20px;
        border: 1px solid #2d8cf0;
        color: #2d8cf0;
        background: transparent;
    }
    .rLeftDown,.rRightUp{cursor:ne-resize;}
    .rRightDown,.rLeftUp{cursor:nw-resize;}
    .rRight,.rLeft{cursor:e-resize;}
    .rUp,.rDown{cursor:n-resize;}
    .draggable .hotTitle {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: center;
        padding: 5px;
        font-size: 14px;
        font-weight: bold;
        line-height: 16px;
        color: #ffffff;
    }
    .bigHot  {
        background: rgba(45, 140, 240, 0.6);
        border: 1px solid #2d8cf0;
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bigHot div{
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: center;
        padding: 5px;
        font-size: 14px;
        font-weight: bold;
        line-height: 16px;
        color: #fff;
    }

</style>

<div class="page-header">当前位置：<span class="text-primary">
    <?php  if($do=='edit') { ?>编辑<?php  } else { ?>新建<?php  } ?> <?php  echo $typename;?> <?php  if($pagetype!='mod') { ?>页面<?php  } ?>
        <?php  if(!empty($page)) { ?>
            <small><?php  if($do=='edit') { ?>(<?php  echo $page['name'];?>)<?php  } else if(($do=='add' && !empty($template) && !empty($template['data']))) { ?>(通过模板：<?php  echo $template['name'];?> 创建)<?php  } ?></small>
        <?php  } ?>
    </span>
</div>

<div class="page-content">
    <div class="row relative w840">
        <div class="diy-phone" data-merch="<?php  echo intval($_W['merchid'])?>">
            <div class="phone-head"></div>
            <div class="phone-body">
                <div class="phone-title" id="page">loading...</div>
                <div class="phone-main" id="phone">
                    <p style="text-align: center; line-height: 400px">您还没有添加任何元素</p>
                </div>
            </div>
            <div class="phone-foot"></div>
        </div>

        <div class="diy-editor form-horizontal" style="position: relative;" id="diy-editor">
            <div class="diy-toolbar">
                <div class="item copy btn-copy">
                    <p class="icow icow-fuzhilianjie1"></p>
                    <p class="txt">复制</p>
                </div>
                <div class="item delete btn-del">
                    <p class="icow icow-shanchu2"></p>
                    <p class="txt ">删除</p>
                </div>
            </div>
            <div class="editor-arrow"></div>
            <div class="inner"></div>
        </div>

        <div class="diy-menu">
            <div class="navs" id="navs"></div>
            <div class="action">
                <nav class="btn btn-default btn-sm" style="float: left; display: none" id="gotop"><i class="icon icon-top" style="font-size: 12px"></i> 返回顶部</nav>
                <?php  if($pagetype=='sys') { ?>
                    <?php if(cv('diypage.page.sys.savetemp')) { ?>
                        <nav class="btn btn-warning btn-sm btn-save" data-type="savetemp">另存为模板</nav>
                    <?php  } ?>
                <?php  } ?>
                <?php  if($pagetype=='diy') { ?>
                    <?php if(cv('diypage.page.diy.savetemp')) { ?>
                        <nav class="btn btn-warning btn-sm btn-save" data-type="savetemp">另存为模板</nav>
                    <?php  } ?>
                <?php  } ?>
                <?php  if($pagetype=='plu') { ?>
                    <?php if(cv('diypage.page.plu.savetemp')) { ?>
                        <nav class="btn btn-warning btn-sm btn-save" data-type="savetemp">另存为模板</nav>
                    <?php  } ?>
                <?php  } ?>
                <nav class="btn btn-primary btn-sm btn-save" data-type="save">保存<?php  if($pagetype=='mod') { ?>模块<?php  } else { ?>页面<?php  } ?></nav>
                <?php  if($pagetype=='sys' || $pagetype=='diy' || $pagetype=='plu') { ?>
                    <?php  if($_GPC['type']!=5 && $page['type']!=5 && $_GPC['type']!=7 && $page['type']!=7 && $_GPC['type']!=8 && $page['type']!=8) { ?>
                    <nav class="btn btn-success btn-sm btn-save" data-type="preview">保存并预览</nav>
                    <?php  } ?>
                <?php  } ?>
            </div>
        </div>
    </div>

    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('diypage/_template', TEMPLATE_INCLUDEPATH)) : (include template('diypage/_template', TEMPLATE_INCLUDEPATH));?>
    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('diypage/_template_edit', TEMPLATE_INCLUDEPATH)) : (include template('diypage/_template_edit', TEMPLATE_INCLUDEPATH));?>
</div>

<script type="text/javascript" src="./resource/components/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="./resource/components/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="./resource/components/ueditor/lang/zh-cn/zh-cn.js"></script>
<link rel="stylesheet" href="../addons/ewei_shopv2/static/js/dist/select2/select2.css">
<link rel="stylesheet" href="../addons/ewei_shopv2/static/js/dist/select2/select2-bootstrap.css">


<script language="javascript">
    var path = '../../plugin/diypage/static/js/diy.min';
    myrequire([path,'tpl','web/biz'],function(modal,tpl){
        modal.init({
            tpl: tpl,
            attachurl: "<?php  echo $_W['attachurl'];?>",
            id: '<?php  echo intval($_GPC["id"])?>',
            type: <?php  echo $type;?>,
            data: <?php  if(!empty($page['data'])) { ?><?php  echo json_encode($page['data'])?><?php  } else { ?>null<?php  } ?>,
            diymenu: <?php  echo json_encode($diymenu)?>,
            diyadvs: <?php  echo json_encode($diyadvs)?>,
            levels: <?php  if(!empty($levels)) { ?><?php  echo json_encode($levels)?><?php  } else { ?>null<?php  } ?>,
            merch: <?php  if($_W['plugin']=='merch' && !empty($_W['merchid'])) { ?>1<?php  } else { ?>0<?php  } ?>,
            merchid: <?php  if($_W['plugin']=='merch' && !empty($_W['merchid'])) { ?><?php  echo $_W['merchid'];?><?php  } else { ?>0<?php  } ?>,
            plugins: <?php  echo $hasplugins;?>,
            shopset: <?php  echo json_encode($_W['shopset']['shop'])?>
        });
    });
    function selectUrlCallback(href){
        var ue =  UE.getEditor('rich');
        if(href){
            ue.execCommand('link', {href: href, 'data-nocache': 'true'});
        }
    }
    function callbackGoods(data) {
        myrequire([path],function(modal) {
            modal.callbackGoods(data);
        });
    }
    function callbackCategory (data) {
        myrequire([path],function(modal) {
            modal.callbackCategory(data);
        });
    }
    function callbackGroup (data) {
        myrequire([path],function(modal) {
            modal.callbackGroup(data);
        });
    }
    function callbackMerch (data) {
        myrequire([path],function(modal) {
            modal.callbackMerch(data);
        });
    }
    function callbackMerchCategory (data) {
        myrequire([path],function(modal) {
            modal.callbackMerchCategory(data);
        });
    }
    function callbackMerchGroup (data) {
        myrequire([path],function(modal) {
            modal.callbackMerchGroup(data);
        });
    }
    function callbackSeckill (data) {
        myrequire([path],function(modal) {
            modal.callbackSeckill(data);
        });
    }
    function callbackCoupon (data) {
        myrequire([path],function(modal) {
            modal.callbackCoupon(data);
        });
    }

    function callbackData(data) {
        myrequire([path],function(modal) {
            modal.callbackData(data);
        });
    }
    // 后台热区
    function callbackHotarea(data) {
        myrequire([path],function(modal) {
            modal.callbackHotarea(data);
        });
    }

    <!--H5添加热区js-->
    function bindEvents(obj){
        var index = obj.attr('index');
        var rs = new Resize(obj, { Max: true, mxContainer: "#hot1" });
        rs.Set($(".rRightDown",obj), "right-down");
        rs.Set($(".rLeftDown",obj), "left-down");
        rs.Set($(".rRightUp",obj), "right-up");
        rs.Set($(".rLeftUp",obj), "left-up");
        rs.Set($(".rRight",obj), "right");
        rs.Set($(".rLeft",obj), "left");
        rs.Set($(".rUp",obj), "up");
        rs.Set($(".rDown",obj), "down");
        rs.Scale = false;
        new Drag(obj, { Limit: true, mxContainer: "#hot1" });
        $('.draggable .remove').unbind('click').click(function(){
            $(this).parent().remove();
        })
    }


</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>