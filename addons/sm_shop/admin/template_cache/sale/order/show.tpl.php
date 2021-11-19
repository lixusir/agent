<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH)?>
<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH)?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <!--
                    <a href="#" target="_blank" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="打印发票">
                        <i class="fa fa-print"></i></a>
                    <a href="#" target="_blank" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="打印发货单">
                        <i class="fa fa-truck"></i></a>
                    -->
                    <a v-bind:href="'#/sale/edit/'+order_id" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑">
                        <i class="fa fa-pencil"></i></a>
                    <a href="#" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="取消">
                        <i class="fa fa-reply"></i></a>
                </div>
                <h1>订单管理</h1>
                <ul class="breadcrumb">
                    <li><a href="#">首页</a></li>
                    <li><a href="#">订单管理</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> 订单详情</h3>
                        </div>
                        <table class="table">
                            <tbody>
                            <tr>
                                <td style="width: 1%;"><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="sm_shop 商城"><i class="fa fa-shopping-cart fa-fw"></i></button></td>
                                <td>订单号：{{ order_info.order_no}}</td>
                            </tr>
                            <tr>
                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="生成日期："><i class="fa fa-calendar fa-fw"></i></button></td>
                                <td>{{ order_info.create_time }}</td>
                            </tr>
                            <tr>
                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="支付方式："><i class="fa fa-credit-card fa-fw"></i></button></td>
                                <td>
                                    {{ order_info.payment_method_text }}

                                    <a v-show="order_info.is_payed==1" class="btn btn-default" >已支付</a>
                                    <a v-show="order_info.is_payed==2" class="btn btn-default" >已退款</a>
                                    <a v-show="order_info.is_payed==1 && order_info.apply_refund==1" class="btn btn-danger" >用户请求退款</a>
                                    <a v-show="order_info.is_payed==1"
                                       v-on:click="refund()"
                                       class="btn btn-primary" >退款</a>
                                </td>
                            </tr>
                            <tr>
                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="配送方式："><i class="fa fa-truck fa-fw"></i></button></td>
                                <td>
                                    {{ order_info.shipping_method_text }}

                                    <a class="btn btn-primary" v-show="order_info.shipping_method=='package' && order_info.is_payed && !order_info.package.length" v-on:click="express_show()" >确定发货</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-user"></i> 客户明细</h3>
                        </div>
                        <table class="table">
                            <tbody><tr>
                                <td style="width: 1%;"><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="客户名称："><i class="fa fa-user fa-fw"></i></button></td>
                                <td>
                                    <span v-if="order_info.customer_name" >{{order_info.customer_name}}</span>
                                    <span v-if="!order_info.customer_name" >客户ID:{{order_info.customer_id}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="客户组："><i class="fa fa-group fa-fw"></i></button></td>
                                <td>{{order_info.customer_group_name}}</td>
                            </tr>
                            <!--<tr>
                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="E-Mail："><i class="fa fa-envelope-o fa-fw"></i></button></td>
                                <td><a v-bind:href="'mailto:'+order_info.mail">{{order_info.mail}}</a></td>
                            </tr>-->
                            <tr>
                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="联系电话："><i class="fa fa-phone fa-fw"></i></button></td>
                                <td>{{order_info.telephone}}</td>
                            </tr>
                            </tbody></table>
                    </div>
                </div>
                <div class="col-md-4 hidden">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-cog"></i> 选项</h3>
                        </div>
                        <table class="table">
                            <tbody>
                            <tr>
                                <td>订单发票</td>
                                <td id="invoice" class="text-right"></td>
                                <td style="width: 1%;" class="text-center">
                                    <button id="button-invoice" data-loading-text="加载中..." data-toggle="tooltip" title="" class="btn btn-success btn-xs" data-original-title="生成"><i class="fa fa-cog"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>奖励积分</td>
                                <td class="text-right">0</td>
                                <td class="text-center">
                                    <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>联盟会员
                                </td>
                                <td class="text-right">￥0.00</td>
                                <td class="text-center">
                                    <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-info-circle"></i> 订单 </h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td style="width: 50%;" class="text-left">配送地址</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-left">
                                收货人姓名：{{order_info.address_name}}  收货人电话：{{order_info.address_phone}}<br>
                                <block v-if="order_info.shipping_method == 'package'">
                                    {{order_info.address_province}}{{order_info.address_city}}{{order_info.address_area}}<br>
                                    {{order_info.address_detail}}
                                </block>

                                <block v-if="order_info.shipping_method == 'pickup'">
                                    自提门店：{{order_info.pickup[0].pickup_name}} 门店电话：{{order_info.pickup[0].pickup_phone}}
                                </block>
                            </td>
                        </tr>
                        <tr v-for="item in order_info.package">
                            <td class="text-left">

                                快递类型：{{item.name?item.name:'其他快递'}}
                                <br>
                                快递单号：{{item.package_no}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td class="text-left">商品名称</td>
                            <td class="text-left">型号</td>
                            <td class="text-right">数量</td>
                            <td class="text-right">价格</td>
                            <td class="text-right">总计</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in order_info.goods">
                            <td class="text-left">
                                <img v-bind:src="item.image" alt="">
                                <a >{{item.goods_name}}</a>
                            </td>
                            <td class="text-left">
                                {{item.goods_option}}
                            </td>
                            <td class="text-right">{{item.goods_num}}</td>
                            <td class="text-right">￥{{item.goods_price}}</td>
                            <td class="text-right">￥{{item.goods_total}}</td>
                        </tr>
                        <tr v-for="tt in order_info.total">
                            <td colspan="4" class="text-right">{{tt.code_text}}</td>
                            <td class="text-right">￥{{tt.value}}</td>
                        </tr>
                        <!--
                        <tr>
                            <td colspan="4" class="text-right">免费配送</td>
                            <td class="text-right">￥0.00</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">订单总额</td>
                            <td class="text-right">￥10.00</td>
                        </tr>
                        -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-comment-o"></i> 添加订单记录</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-history" data-toggle="tab">历史</a></li>
                        <li class="hidden"><a href="#tab-additional" data-toggle="tab">附加</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-history">
                            <div id="history"><div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td class="text-left">生成日期</td>
                                        <td class="text-left">订单附言</td>
                                        <td class="text-left">状态</td>
                                        <td class="text-left">通知客户</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in order_info.history_list">
                                        <td class="text-left">{{item.create_time}}</td>
                                        <td class="text-left">{{item.comment}}</td>
                                        <td class="text-left">{{item.name}}</td>
                                        <td class="text-left">--</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                                <div class="row">
                                    <div class="col-sm-6 text-left"></div>
                                    <div class="col-sm-6 text-right">显示 1 - 1 / 合计 1（共 1 页）</div>
                                </div>
                            </div>
                            <br>
                            <fieldset>
                                <legend>添加订单历史</legend>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-order-status">状态</label>
                                        <div class="col-sm-10">
                                            <select v-model='order_history.order_status' class="form-control">
                                                <option v-for="item in order_all_status" v-bind:value="item.code">{{item.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-override">
                                            <span data-toggle="tooltip" title="" data-original-title="反欺诈扩展可能造成订单状态覆盖，导致订单状态更改失败。">重写</span></label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <input type="checkbox" name="override" value="1" id="input-override">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-notify">通知客户</label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <input type="checkbox" name="notify" value="1" id="input-notify">
                                            </div>
                                        </div>
                                    </div>
                                    -->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-comment">订单附言</label>
                                        <div class="col-sm-10">
                                            <textarea  v-model="order_history.comment" rows="8" id="input-comment" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                            <div class="text-right">
                                <button id="button-history" v-on:click="add_order_history()" data-loading-text="加载中..." class="btn btn-primary">
                                    <i class="fa fa-plus-circle"></i> 添加订单记录
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-additional">
                            <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td colspan="2">浏览器</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>IP 地址</td>
                                    <td>127.0.0.1</td>
                                </tr>
                                <tr>
                                    <td>操作系统</td>
                                    <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36</td>
                                </tr>
                                <tr>
                                    <td>系统语言</td>
                                    <td>zh-CN,zh;q=0.9,und;q=0.8,en;q=0.7</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-dialog express-modal " style="display: none" v-show="express_panel.show">
        <div class="modal-content">
            <div class="modal-header">
                <a v-on:click="express_cross()" class="close" >×</a>
                <h4 class="modal-title">订单发货</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">收 货 人</label>
                    <div class="col-sm-9 col-xs-12">
                        <div class="form-control-static">
                            联系人：{{order_info.address_name}} / {{order_info.address_phone}}<br>
                            地    址: {{order_info.address_province}}{{order_info.address_city}}{{order_info.address_area}}<br>
                            {{order_info.address_detail}}
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">快递公司</label>
                    <div class="col-sm-9 col-xs-12">
                        <select class="form-control" v-model="express_panel.express_code" name="express" id="express">
                            <option value=""  >其他快递</option>
                            <option v-for="item in express_list" v-bind:value="item.code" >{{item.name}}</option>
                        </select>
                        <input type="hidden" name="expresscom" id="expresscom" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label must">快递单号</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" v-model="express_panel.package_no" class="form-control" >
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" v-on:click="order_delivery()" >确认发货</a>
                <a class="btn btn-default" v-on:click="express_cross()" >取消</a>
            </div>
        </div>

    </div>
</div>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH)?>

<script>
    var vue = new Vue( {
        el:'#container',
        data:function(){

            return{
                config:{
                    web_url:'<?php echo $this->url_pre;?>'
                },
                order_id:0,
                order_info:{},
                order_all_status:[],
                order_history:{
                    comment:'',
                    order_status:''
                },

                express_panel:{
                    show:0,
                    express_code:'',
                    package_no:'',
                },
                express_list:[]

            }
        },

        methods:{

            get_order_info:function(){
                var url = this.config.web_url + '&r=order.info';
                url += '&order_id='+ this.order_id;
                var t = this;

                axios.get( url ).then(function( res ){

                    t.order_info = res.data;

                });
            },
            get_order_all_status:function(){

                var url = this.config.web_url + '&r=order.status_list';
                var t = this;

                axios.get( url ).then(function( res ){

                    t.order_all_status = res.data;

                });
            },

            add_order_history:function(){

                var url = this.config.web_url + '&r=order.add_history&order_id=' + this.order_id;
                var t = this;

                var data = "comment=" + this.order_history.comment;
                data += "&order_status=" + this.order_history.order_status;
                axios.post( url, data ).then(function( res ){

                    t.order_history.comment = '';
                    t.order_history.order_status = '';
                    t.get_order_info();

                });

            },

            get_express_list:function(){

                var url = this.config.web_url + '&r=express.index';
                var t = this;

                axios.get( url ).then(function( res ){
                    t.express_list = res.data;
                });
            },

            order_delivery:function(){

                var t = this;
                var url = this.config.web_url + '&r=order.set_order_package&order_id=' + this.order_id;
                var data = 'package_no=' + this.express_panel.package_no;
                data += '&express_code=' + this.express_panel.express_code;
                axios.post( url, data).then(function( res ){

                    if( !res.data.status ){
                        t.express_panel.show = 0;
                        t.get_order_info();
                    }


                });
            },

            express_cross:function(){

                this.express_panel.show = 0;
            },
            express_show:function(){
                this.express_panel.show = 1;
            },

            //todo 退款
            refund:function(){

                var t = this;
                var url = this.config.web_url + '&r=order.refund';

                var data = 'order_id=' + this.order_id;
                axios.post( url, data).then(function( res ){

                    if( !res.data.status ){
                        t.express_panel.show = 0;
                        t.get_order_info();
                    }


                });

            }

        },

        created:function(){

            var order_id = getQueryString('order_id');
            if( order_id == undefined ){
                this.order_id = 0;
            }else{
                this.order_id = order_id;
                this.get_order_info();

            }
            this.get_order_all_status();
            this.get_express_list();
        }
    });
</script>

<style scoped>

    .express-modal{

        width:720px;
        margin-left:-360px;
        top:25%;
        position:fixed;
    }
    .form-group{
        overflow: hidden;
    }

    .control-label{
        line-height: 34px;
    }

    .form-control-static{
        padding-top: 0;
        padding-bottom: 0;
    }



</style>