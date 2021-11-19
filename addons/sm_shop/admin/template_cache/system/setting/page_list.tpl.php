<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a v-on:click="do_edit()" id="button-save" form="form-setting" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="保存"><i class="fa fa-save"></i></a>
                <a href="#" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="取消"><i class="fa fa-reply"></i></a></div>
            <h1>系统设置</h1>
            <ul class="breadcrumb">
                <li><a href="#">首页</a></li>
                <li><a href="#">商店</a></li>
                <li><a href="#">系统设置</a></li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">         <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑系统设置</h3>
        </div>
        <div class="panel-body">
            <form action="http://kkc.cloud.com/admin/index.php?route=setting/setting&amp;user_token=09493e256db937b3aed9e251e5e6d4ed" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
                <ul class="nav nav-tabs">
                    <!--<li class=""><a href="#tab-general" data-toggle="tab" aria-expanded="false">基本信息</a></li>-->
                    <!--<li class=""><a href="#tab-store" data-toggle="tab" aria-expanded="false">商店设置</a></li>-->
                    <!--<li class=""><a href="#tab-local" data-toggle="tab" aria-expanded="false">本地化设置</a></li>-->
                    <li class="active"><a href="#tab-option" data-toggle="tab" aria-expanded="true">选项设置</a></li>
                    <li class=""><a href="#tab-live" data-toggle="tab" aria-expanded="true">直播设置</a></li>
                    <li class=""><a href="#tab-xcx" data-toggle="tab" aria-expanded="true">小程序设置</a></li>
                    <!--<li class=""><a href="#tab-image" data-toggle="tab" aria-expanded="false">图片</a></li>-->
                    <!--<li><a href="#tab-mail" data-toggle="tab">邮件协议</a></li>-->
                    <!--<li><a href="#tab-server" data-toggle="tab">服务器设置</a></li>-->
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-option">

                        <fieldset>
                            <legend>账户</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    商城名称</label>
                                <div class="col-sm-10">
                                    <input v-model="settings.config.shop_name"
                                           placeholder="神秘商城"
                                           id="input-shop-name" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    首页直播间模块显示</label>
                                <div class="col-sm-10">
                                    <select v-model="settings.config.home_live_show" id="home-live-show" class="form-control">
                                        <option value="1" >是</option>
                                        <option value="0" >否</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    电话
                                </label>
                                <div class="col-sm-10">
                                    <input v-model="settings.config.phone"
                                           placeholder="电话" id="input-phone" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    QQ
                                </label>
                                <div class="col-sm-10">
                                    <input v-model="settings.config.qq"
                                           placeholder="QQ" id="input-qq" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    微信
                                </label>
                                <div class="col-sm-10">
                                    <input v-model="settings.config.wechat"
                                           placeholder="微信" id="input-wechat" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    <span data-toggle="tooltip" title="" data-original-title="后台地址逆解析Key">后台地址逆解析Key</span>
                                </label>
                                <div class="col-sm-10">
                                    <input v-model="settings.config.latlng_to_addr_ley"
                                           placeholder="后台地址逆解析Key" id="input-latlng_to_addr_ley" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    <span data-toggle="tooltip" title="" data-original-title="默认客户组。">默认客户组</span></label>
                                <div class="col-sm-10">
                                    <select v-model="settings.config.customer_group_id" id="input-customer-group" class="form-control">
                                        <option v-bind:value="item.id" v-for="item in group_list">{{item.name}}</option>
                                    </select>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                    <div class="tab-pane " id="tab-live">

                        <fieldset>
                            <legend>阿里云直播</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span >accessKeyId</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.access_key_id" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">accessSecret</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.access_secret" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">ApiUrl</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.api_url" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">直播流域名</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.domain_name"  class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">推流地址</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.push_flow" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">推流地址参数</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.push_flow_ext" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">鉴权主KEY</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text"  v-model="settings.live_ali.auth_main_key" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">OssEndpoint</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.oss_endpoint" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">Oss Bucket</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.oss_bucket" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">Time Interval</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.time_interval" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title=""
                                          data-original-title="">CDN</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.live_ali.cdn" class="form-control" >
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane" id="tab-xcx">
                        <fieldset>
                            <legend>小程序</legend>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title="小程序名称"
                                          data-original-title="">小程序名称</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.xcx.shop_name" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title="小程序 APP ID"
                                          data-original-title="">APP ID</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.xcx.app_id" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title="小程序 APP SECRET"
                                          data-original-title="">APP SECRET</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.xcx.app_secret" class="form-control" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title="商户id"
                                          data-original-title="">商户 ID</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.xcx.mch_id" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"
                                       for="input-customer-group">
                                    <span data-toggle="tooltip" title="商户 支付秘钥"
                                          data-original-title="">商户 支付秘钥</span>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" v-model="settings.xcx.mch_key" class="form-control" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    <span data-toggle="tooltip" title="" data-original-title="腾讯位置服务KEY">腾讯位置服务KEY</span>
                                </label>
                                <div class="col-sm-10">
                                    <input v-model="settings.xcx.location_key"
                                           placeholder="腾讯位置服务KEY" id="input-location_key" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-customer-group">
                                    <span data-toggle="tooltip" title="" data-original-title="悬浮在首页侧边栏的导航">侧边栏浮动导航</span>
                                </label>
                                <div class="col-sm-10">
                                    <select v-model="settings.xcx.float_nav_show" class="form-control">
                                        <option value="0">隐藏</option>
                                        <option value="1">显示</option>
                                    </select>

                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
</div>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<script>
    var vue = new Vue({

        el:'#container',
        data:function(){

            return {
                group_list:[],
                config:{
                    web_url:'<?php echo $this->url_pre;?>'
                },
                settings:{
                    config:{
                        // key:'customer_group_id',
                        // value:0,
                        // serialized:0
                        shop_name           : '',
                        phone               : '',
                        qq                  : '',
                        wechat              : '',
                        home_live_show      : 1,
                        latlng_to_addr_ley  :'', // 经纬度转详细地址KEY(后台自提点使用)
                        customer_group_id   : 0,
                    },
                    live_ali:{
                        access_key_id:'',
                        access_secret:'',
                        api_url:'',
                        domain_name:'', // 直播流域名
                        push_flow:'', // 推流地址
                        push_flow_ext:'', // 推流地址参数
                        auth_main_key:'', // 鉴权主KEY
                        OssEndpoint:'', //
                        oss_bucket:'', //
                        time_interval:'', //
                        cdn:'', //
                    },
                    xcx:{
                        shop_name       : '', //适用于小程序的商城名称
                        app_id          : '',
                        app_secret      : '',
                        mch_id          : '',
                        mch_key         : '',
                        location_key    : '',
                        float_nav_show   : '0',
                    }
                }
            }
        },
        methods:{

            get_settings:function(){

                var url = this.config.web_url + '&r=setting.index';
                var t = this;

                axios.get( url ).then(function( res ){
                    var list = res.data;
                    for( var i = 0; i < list.length; i ++ ){
                        if( ['config','live_ali','xcx'].indexOf( list[i].code) >-1 ){
                            t.settings[list[i].code][list[i].key] = list[i].value;
                        }
                    }
                    console.log( t.settings );
                });

            },

            get_customer_groups:function(){

                var url = this.config.web_url + '&r=customerGroup.index';
                var t = this;

                axios.get( url ).then(function( res ){
                    t.group_list = res.data;
                });

            },

            do_edit:function(){

                var url = this.config.web_url + '&r=setting.edit';
                var t = this;

                var data = "";

                var index = 0;
                for(var p in this.settings ){
                    var code = this.settings[p];

                    for(var q in code ){
                        data += "&settings[" + index + "][code]="  + p;
                        data += "&settings[" + index + "][key]="   + q;
                        data += "&settings[" + index + "][value]=" + code[q];

                        // data += "&settings[" + i + "][serialized]=0";

                        index ++;
                    }
                }


                axios.post( url, data ).then(function( res ){

                    t.init();
                });

            },


            init:function(){

                this.get_settings();
                this.get_customer_groups();
            }
        },

        created:function(){

            this.init();
        }


    });
</script>