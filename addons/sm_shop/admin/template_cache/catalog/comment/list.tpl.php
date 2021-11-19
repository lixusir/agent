<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
    <div id="content" >
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">

                    <a title="添加" href="/web/index.php?c=site&a=entry&m=sm_shop&do=web&r=goodsComment.page_edit" class="btn btn-primary" data-original-title="添加">
                        <i class="fa fa-plus"></i>
                    </a>
                    <!--
                    <button title="复制" class="btn btn-default" data-original-title="复制">
                        <i class="fa fa-copy"></i>
                    </button>
                    -->
                    <button title="删除" class="btn btn-danger"
                            v-on:click="delete_comments()"
                            data-original-title="删除">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
                <h1>商品评论管理</h1>
                <ul class="breadcrumb">
                    <li><a href="">首页</a></li>
                    <li><a href="">商品评论管理</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">        <div class="row">
            <div id="filter-product" class="col-sm-12 hidden-sm hidden-xs">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-filter"></i> 筛选</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="input-name">商品名称</label>
                                    <input type="text" name="filter_name" value=""
                                           v-model="filter.goods_name"
                                           placeholder="商品名称" id="input-name" class="form-control"
                                           autocomplete="off">
                                    <ul class="dropdown-menu"></ul>
                                </div>

                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="input-price">作者</label>
                                    <input type="text" name="filter_author"
                                           v-model="filter.author"
                                           value=""  id="input-author" class="form-control">
                                </div>

                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="input-status">状态</label>
                                    <select name="filter_status"
                                            v-model="filter.status"
                                            id="input-status" class="form-control">
                                        <option value="">全部</option>
                                        <option value="1">启用</option>
                                        <option value="0">禁用</option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="input-date">日期</label>
                                    <div class="input-group date">
                                        <input type="text" name="filter_date_added" value=""
                                               placeholder="评论日期"
                                               data-date-format="YYYY-MM-DD"
                                               id="input-date-added" class="form-control">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <div class="btn-group">
                                        <button disabled=""
                                                class="dropdown-toggle btn btn-success hidden"
                                                id="batch-edit" type="button"
                                                data-toggle="dropdown">批量修改
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu batch-edit-item" role="menu">
                                            <li>
                                                <a href="javascript:void(0)" data-type="price">商品<span>价格</span></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" data-type="quantity">商品<span>库存</span></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" data-type="status">商品<span>下架</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <button type="button" id="button-filter"
                                            v-on:click="get_list()"
                                            class="btn btn-default">
                                        <i class="fa fa-filter"></i> 筛选</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> 评论列表</h3>
                    </div>
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center">
                                        <input type="checkbox"
                                               v-on:click="check_all( $event )"
                                               id="all-selected">
                                    </td>
                                    <td class="text-left">
                                        <a href="">商品名称</a> </td>
                                    <td class="text-left">
                                        <a href="">作者</a> </td>
                                    <td class="text-right">
                                        <a href="">评分</a> </td>
                                    <td class="text-right">
                                        <a href="">状态</a> </td>
                                    <td class="text-right">
                                        <a href="">评论日期</a> </td>
                                    <td class="text-right">管理</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item,index in comment_list"
                                    v-show="index>=(page.current-1)*page.item_num&&index<page.current*page.item_num">
                                    <td class="text-center selected-item">
                                        <input type="checkbox"
                                               v-model="checked_goods"
                                               v-bind:value="item.id"  >
                                    </td>
                                    <td class="text-left">
                                        <img v-bind:src="item.goods_image" alt="" class="img-thumbnail">
                                        <span>{{item.goods_name}}</span>
                                    </td>
                                    <td class="text-left name-editor">
                                        <span>{{item.author}}</span>
                                    </td>
                                    <td class="text-left model-editor" data-item="model">
                                        <span>{{item.score}}</span>

                                    </td>
                                    <td class="text-right price-editor" data-item="status">
                                        <span v-if="item.status==1">启用</span>
                                        <span v-if="item.status==0">禁用</span>
                                    </td>
                                    <td class="text-right quantity-editor" data-item="date">
                                        <span class="label label-success">{{item.date}}</span>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group" >
                                            <a v-bind:href="'<?php echo $this->url_pre;?>&r=goodsComment.page_edit&comment_id='+item.id"
                                               data-toggle="tooltip" title="" class="btn btn-primary"
                                               data-original-title="编辑">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 text-left">
                                <ul class="pagination" v-if="page.total">
                                    <li v-for="item in page.total"
                                        v-show="item>=page.current-1 && item <= page.current+1"
                                        v-bind:class="{'active':item == page.current?1:0}"
                                        v-on:click="goto_page( item )"
                                    >
                                        <span>{{item }}</span>
                                    </li>
<!--                                    <li><a href="">2</a></li>-->
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

<script>
    setTimeout(function(){

        var vue = new Vue({
            el:'#container',
            data:function(){
                return {

                    checked_goods:[],
                    filter:{

                        author      : '',
                        goods_name  : '',
                        date        : '',
                        status      : ''
                    },

                    page:{

                        total:0,
                        item_total:0,//总数量
                        item_num:10, //每页数量
                        current:1
                    },
                    config:{

                        web_url:'<?php echo $this->url_pre;?>',
                    },
                    search:{

                    },
                    comment_list:[]
                }
            },

            methods:{


                get_list:function(){

                    var t = this;

                    var url = this.config.web_url + '&r=goodsComment.get_list';

                    if( this.filter.author ){
                        url += '&author=' + this.filter.author;
                    }

                    if( this.filter.status!= '' ) {
                        url += '&status=' + this.filter.status;
                    }

                    if( this.filter.goods_name ){
                        url += '&goods_name=' + this.filter.goods_name;
                    }

                    this.filter.date = $('#input-date-added').val();
                    this.filter.date = this.filter.date.replace(/^(\s+)|(\s+)$/g,'');
                    if( this.filter.date ){
                        url += '&date=' + this.filter.date;
                    }

                    axios.get( url ).then(function( res ){

                        t.comment_list = res.data;
                        t.page.item_total = res.data.length;
                        t.page.total = parseInt( t.page.item_total / t.page.item_num );
                        t.page.total += res.data.length % t.page.item_num?1:0;

                    });

                },

                goto_page:function( current ){

                    current = current<=this.page.total?current:this.page.total;
                    this.page.current = current;

                },

                check_all:function( e ){

                    var t = this;
                    this.checked_goods = [];
                    if( e.srcElement.checked ){
                        this.comment_list.forEach(function( item ){
                            t.checked_goods.push( item.id );
                        });
                    }

                },

                delete_comments:function(){

                    var t = this;
                    if( this.checked_goods.length == 0 ){
                        alert('请选中要删除的评论');
                        return;
                    }

                    var url = this.config.web_url + '&r=goodsComment.remove';

                    var data = 'ids=';

                    this.checked_goods.forEach(function( item ){
                        data += item + ',';
                    });
                    data = data.replace(/,$/,'');
                    axios.post(url, data ).then(function( res ){

                        location.href = t.config.web_url + '&r=goodsComment.page_list';
                    });


                }


            },

            created:function(){

                this.get_list();
            }
        });

    },2000);


</script>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH, "sm_shop")?>