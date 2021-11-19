<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
    <div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $this->url_pre . '&r=brand.page_edit';?>"
                   data-toggle="tooltip" title="" class="btn btn-primary"
                   data-original-title="添加">
                    <i class="fa fa-plus"></i>
                </a>
                <a href="<?php echo $this->url_pre . '&r=brand.page_list';?>"
                   data-toggle="tooltip" title=""
                   class="btn btn-default" data-original-title="重建">
                    <i class="fa fa-refresh"></i>
                </a>
                <!--<button type="button"-->
                <!--v-on:click="brand_remove()"-->
                <!--class="btn btn-danger" data-original-title="删除">-->
                <!--<i class="fa fa-trash-o"></i>-->
                <!--</button>-->
            </div>
            <h1>商品品牌</h1>
            <ul class="breadcrumb">
                <li><a href="">首页</a></li>
                <li><a href="">商品品牌</a></li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div id="filter-product" class="col-md-3 col-md-push-9 col-sm-12 hidden-sm hidden-xs">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-filter"></i> 筛选过滤</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label" for="input-name">品牌名称</label>
                            <input type="text" name="filter_name"  v-model="filter.name" value="" placeholder="品牌名称" id="input-name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="input-status">状态</label>
                            <select name="filter_status" v-model="filter.status" id="input-status" class="form-control">
                            <option value=""></option>
                            <option value="1">启用</option>
                            <option value="0">禁用</option>
                        </select>
                        </div>
                        <div class="form-group text-right">
                            <button type="button" id="button-filter"
                                    v-on:click="search()"
                                    class="btn btn-default">
                                <i class="fa fa-filter"></i> 筛选</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-md-pull-3 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> 品牌列表</h3>
                    </div>
                    <div class="panel-body">
                        <form action="" method="post" enctype="multipart/form-data" id="form-brand">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td style="width: 1px;" class="text-center">
                                            <input type="checkbox" onclick="$('input[name*=\'selected\']').trigger('click');"></td>
                                        <td class="text-left">
                                            <a href="" class="asc">品牌名称</a>
                                        </td>
                                        <td class="text-right">
                                            <a href="">字母排序</a>
                                        </td>
                                        <td class="text-right">状态</td>
                                        <td class="text-right">编辑品牌</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item,index in brand_list"
                                        v-show="index>=(page.current-1)*page.item_num&&index<page.current*page.item_num"
                                    >
                                        <td class="text-center">
                                            <input type="checkbox"  v-model="selected_brand" name="selected[]" v-bind:value="item.id">
                                        </td>
                                        <td class="text-left">{{item.name}}</td>
                                        <td class="text-right">{{item.letter_pre}}</td>
                                        <td class="text-right">
                                            <span v-show="item.status==1">启用</span>
                                            <span v-show="item.status==0">禁用</span>
                                        </td>
                                        <td class="text-right">
                                            <a v-bind:href="url_pre + '&r=brand.page_edit&brand_id=' + item.id "
                                               data-toggle="tooltip" title="" class="btn btn-primary"
                                               data-original-title="编辑">
                                                <i class="fa fa-pencil"></i></a>
                                            <!--<a href="" target="_blank" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="查看">-->
                                            <!--<i class="fa fa-search"></i></a>-->
                                            <a  v-on:click="brand_remove( item )"
                                                class="btn btn-danger" data-original-title="删除">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
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
        el:'#container',
        name: "brand",

        data:function(){

            return {
                filter:{

                    name:'',
                    status:'',
                },
                page:{

                    total:0,
                    item_total:0,//总数量
                    item_num:10, //每页数量
                    current:1
                },
                url_pre:'<?php echo $this->url_pre;?>',
                selected_brand:[],

                brand_list:[]

            }


        },

        methods:{

            search:function(){


                this.get_list();
            },

            get_list:function(){

                var t = this;
                var url = this.url_pre + '&r=brand.index';
                if( this.filter.name ){
                    url += '&name=' + this.filter.name;
                }
                if( this.filter.status != '' ){
                    url += '&status=' + this.filter.status;
                }
                axios.get( url ).then(function( res ){

                    t.brand_list = res.data;

                    t.page.item_total = res.data.length;
                    t.page.total = parseInt( t.page.item_total / t.page.item_num );
                    t.page.total += res.data.length % t.page.item_num?1:0;
                });

            },

            brand_remove : function( brand ){

                var t = this;

                var url = this.url_pre + '&r=brand.remove';

                var data = 'brand_id=' + brand.id;

                axios.post( url, data ).then(function( res ){

                    if( res.data.status == 0 ){
                        t.get_list();
                    }else{
                        alert( res.data.description );
                    }

                });

            },

            goto_page:function( current ){

                current = current<=this.page.total?current:this.page.total;
                this.page.current = current;

            }

        },

        created:function(){
            this.get_list();
        }

    });
</script>