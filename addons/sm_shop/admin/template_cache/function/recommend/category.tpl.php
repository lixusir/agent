<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>
<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
    <div id="content" v-on:click="clear_dropdown()">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <a data-toggle="tooltip"
                       v-on:click="save()"
                       class="btn btn-primary"
                       title="保存">
                        <i class="fa fa-save"></i></a>
                    <a href="#" data-toggle="tooltip" title=""
                       class="btn btn-default" data-original-title="取消">
                        <i class="fa fa-reply"></i></a>
                </div>
                <h1>推荐分类</h1>
                <ul class="breadcrumb">
                    <li><a href="#">首页</a></li>
                    <li><a href="#">模块管理</a></li>
                    <li><a href="#">推荐分类</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑推荐模块</h3>
                </div>
                <div class="panel-body">
                    <form  method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">


                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-category">显示标题</label>
                            <div class="col-sm-10">
                                <input type="text"  v-model="recommend.title"  placeholder="标题" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-category">推荐分类</label>
                            <div class="col-sm-10">
                                <input type="text"
                                       v-model="category_filter"
                                       v-on:keyup="category_search()"
                                       placeholder="推荐分类" id="input-category" class="form-control"
                                       autocomplete="off">
                                <ul class="dropdown-menu"

                                    v-bind:style="{display:search_list_show==1?'block':'none'}"
                                    style="top: 36px; left: 15px; max-height: 300px; overflow: auto;">
                                    <li v-on:click="check_category( item )"
                                        v-for="item in search_list">
                                        <a >{{item.name}}</a>
                                    </li>


                                </ul>
                                <div id="featured-category" class="well well-sm mb-5 ui-sortable" style="height: 150px; overflow: auto;">
                                    <div v-for="item in category_list" class="ui-sortable-handle">
                                        <i v-on:click="delete_category( item )" class="fa fa-minus-circle"></i>
                                        {{ item.name }}

                                    </div>
                                </div>
                                <span class="help-block">(自动完成)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status">状态</label>
                            <div class="col-sm-10">
                                <select v-model="recommend.status" id="input-status" class="form-control">
                                    <option value="1" >启用</option>
                                    <option value="0">禁用</option>
                                </select>
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
    var vue = new Vue( {
        el:'#container',
        data:function(){
            return {


                config:{
                    web_url:'<?php echo $this->url_pre;?>'
                },
                category_filter:'',
                search_list:[],
                category_list:[],
                search_list_show:0,
                recommend:{
                    title:'',
                    status:'0',
                    category_list:[],
                }
            }
        },

        methods:{

            clear_dropdown:function(){

                this.search_list_show = 0;

            },

            category_search:function(){

                var t = this;
                var url = this.config.web_url + '&r=category.search';
                url += '&filter=' + this.category_filter;
                this.search_list_show = 0;
                axios.get( url ).then(function( res ){

                    t.search_list = res.data;
                    t.search_list_show = 1;

                });

            },

            delete_category:function( item ){

                var index = this.category_list.indexOf( item );
                this.category_list.splice( index, 1 );

            },

            check_category:function( item ){

                var existed = 0;
                for(var i = 0; i < this.category_list.length; i ++  ){
                    if( this.category_list[i].id == item.id ){
                        existed = 1;
                        break;
                    }
                }

                if( !existed ){
                    this.category_list.push( item );
                }


            },

            get_home_category:function(){

                var t = this;
                var url = this.config.web_url + '&r=recommend.home_category';

                axios.get( url ).then(function( res ){

                    t.recommend = res.data;

                    t.category_list = res.data.list;

                });

            },

            save:function(){

                var t = this;
                var url = this.config.web_url + '&r=recommend.category_save';

                var data = '';
                data += '&title=' + this.recommend.title;
                data += '&status=' + this.recommend.status;

                var category_list_str = '';
                for(var i = 0; i < this.category_list.length; i ++ ){
                    category_list_str += this.category_list[i].id + ',';
                }
                category_list_str = category_list_str.replace(/^([\s,]+)|([\s,]+)$/, '');
                data += '&list=' + category_list_str;

                axios.post( url, data ).then(function( res ){


                    t.get_home_category();

                });

            }

        },

        created:function(){

            this.get_home_category();
        }

    });
</script>

<style scoped>

</style>