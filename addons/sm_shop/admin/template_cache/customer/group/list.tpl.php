<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>
<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <!--
                    <button type="button" data-toggle="tooltip" title=""  class="btn btn-default hidden-md hidden-lg" data-original-title="筛选">
                        <i class="fa fa-filter"></i></button>
                    <button type="submit" id="button-shipping" form="form-order"
                             formtarget="_blank" data-toggle="tooltip" title="" class="btn btn-info" disabled="" data-original-title="打印发货单">
                        <i class="fa fa-truck"></i></button>
                    <button type="submit" id="button-invoice" form="form-order"
                            formtarget="_blank" data-toggle="tooltip" title="" class="btn btn-info" disabled="" data-original-title="打印发票">
                        <i class="fa fa-print"></i></button>
                    -->
                    <a title="添加" href="/web/index.php?c=site&a=entry&m=sm_shop&do=web&r=customerGroup.page_edit" class="btn btn-primary" >
                        <i class="fa fa-plus"></i>
                    </a>

                    </div>

                <h1>客户群组管理</h1>
                <ul class="breadcrumb">
                    <li><a >首页</a></li>
                    <li><a >群组管理</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
            <div id="filter-order" class="col-md-3 col-md-push-9 col-sm-12 hidden-sm hidden-xs">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-filter"></i> 筛选</h3>
                    </div>
                    <div class="panel-body">


                        <div class="form-group">
                            <label class="control-label" for="input-customer">名称</label>
                            <input type="text" name="filter_customer"
                                   v-model="filter.name"
                                   placeholder="名称"
                                   id="input-customer" class="form-control"
                                   autocomplete="off">

                        </div>

                        <div class="form-group text-right">
                            <button type="button" id="button-filter"
                                    v-on:click="customer_group_filter()"
                                    class="btn btn-default">
                                <i class="fa fa-filter"></i> 筛选</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-md-pull-3 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> 群组列表</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="" enctype="multipart/form-data" id="form-order">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td style="width: 1px;" class="text-center">
                                            <input type="checkbox" onclick="$('input[name*=\'selected\']').trigger('click');"></td>
                                        <td class="text-right">
                                            <a  class="desc">群组ID</a> </td>
                                        <td class="text-right">
                                            <a >名称</a>
                                        </td>
                                        <td class="text-right">
                                            <a >是否为默认群组</a>
                                        </td>
                                        <td class="text-right">管理</td>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr v-if="customer_list.length>0" v-for="item,index in customer_list"
                                        v-show="index>=(page.current-1)*page.item_num&&index<page.current*page.item_num"
                                    >
                                        <td class="text-center">
                                            <input type="checkbox" name="selected[]"  v-bind:value="item.id">
                                        </td>
                                        <td class="text-left">{{item.id}}</td>

                                        <td class="text-right">{{item.name}}</td>
                                        <td class="text-right">{{item.is_default==1?"是":"否"}}</td>
                                        <td class="text-right">
                                            <a v-bind:href=" url_pre + '&r=customerGroup.page_edit&id=' + item.id "
                                               data-toggle="tooltip" title="管理"
                                               class="btn btn-primary" >
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>

                                    </tr>

                                    <tr v-if="customer_list.length==0">
                                        <td class="text-center" colspan="8">没有结果！</td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </form>
                        <div class="row" v-show="page.total">

                            <div class="col-sm-6 text-left">
                                <ul class="pagination" >
                                    <li v-for="item in page.total"
                                        v-show="item>=page.current-1 && item <= page.current+1"
                                        v-bind:class="{'active':item == page.current?1:0}"
                                        v-on:click="goto_page( item )"
                                    >
                                        <span>{{item }}</span>
                                    </li>
                                    <li v-on:click="goto_page(page.current+1)"><a>&gt;</a></li>
                                    <li v-on:click="goto_page(page.total)"><a>&gt;|</a></li>
                                </ul></div>
                            <div class="col-sm-6 text-right">
                                显示{{(page.current-1)*page.item_num+1}} - {{page.current*page.item_num}}
                                / 合计 {{page.item_total}}（共 {{page.total}} 页）
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<script>

    var vue = new Vue({
        el: '#container',
        name: "customer-list",

        data:function () {

            return{

                filter:{

                    name:'',
                },
                customer_list:[],

                page:{

                    total:0,
                    item_total:0,//总数量
                    item_num:20, //每页数量
                    current:1
                },

                url_pre:'<?php echo $this->url_pre;?>',
            }

        },

        methods:{

            get_list:function(){

                var t = this;
                var url = this.url_pre + '&r=customerGroup.index';

                if( this.filter.name ){
                    url += '&name=' + this.filter.name;
                }


                axios.get( url ).then(function( res ){

                    t.customer_list = res.data;
                    t.page.item_total = res.data.length;
                    t.page.total = parseInt( t.page.item_total / t.page.item_num );
                    t.page.total += res.data.length % t.page.item_num?1:0;
                });

            },

            customer_group_filter:function(){

                this.get_list();

            }

            ,goto_page:function( current ){

                current = current<=this.page.total?current:this.page.total;
                this.page.current = current;

            }

        },

        created:function(){

            this.get_list();

        }

    });
</script>