<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH)?>
<link href="/addons/sm_shop/admin/assets/css/jquery.contextMenu.css" rel="stylesheet">
<script src="/addons/sm_shop/admin/assets/js/designer.js?v=0.0.1"></script>
<script src="/addons/sm_shop/admin/assets/js/jquery.contextMenu.js?v=0.0.1"></script>
<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH)?>

    <div id="content" >
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button v-on:click="do_edit()" class="btn btn-primary" data-original-title="保存">
                        <i class="fa fa-save"></i>
                    </button>
                    <!--<button type="button" id="save-variant" title="" class="btn btn-success" >
                        <i class="fa fa-save"></i>
                    </button>-->
                    <a v-bind:href="config.web_url + '&r=goods.page_list'" title="" class="btn btn-default" >
                        <i class="fa fa-reply"></i>
                    </a>
                </div>
                <h1>商品管理</h1>
                <ul class="breadcrumb">
                    <li><a href="#">首页</a></li>
                    <li><a href="#">商品管理</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑海报</h3>
                </div>

                <div class="panel-body">
                    <div class="form-horizontal">

                        <div class="tab-content">

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="input-name2">名称</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.name"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="input-name2">回复关键字</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.reply"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="input-name2">开始时间</label>
                                <div class="col-md-10">
                                    <input type="date"  v-model="info.date_start"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="input-name2">结束时间</label>
                                <div class="col-md-10">
                                    <input type="date"  v-model="info.date_end"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="input-name2">状态</label>
                                <div class="col-md-10">
                                    <select v-model="info.status" id="input-status" class="form-control">
                                        <option value="0">禁用</option>
                                        <option value="1" >启用</option>
                                    </select>


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH)?>
