<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>

    <div id="content" v-on:click="clear_dropdown()">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button v-if="brand_id" type="submit"  data-toggle="tooltip" title="" class="btn btn-primary"
                            v-on:click="do_edit()"
                            data-original-title="保存"><i class="fa fa-save"></i></button>
                    <button v-if="!brand_id" type="submit"  data-toggle="tooltip" title="" class="btn btn-primary"
                            v-on:click="do_create()"
                            data-original-title="保存"><i class="fa fa-save"></i></button>
                    <a v-bind:href="config.web_url + '&r=brand.page_list'"
                       data-toggle="tooltip" title="" class="btn btn-default" data-original-title="取消"><i
                            class="fa fa-reply"></i></a></div>
                <h1>商品品牌</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="">首页</a>
                    </li>
                    <li>
                        <a href="">商品品牌</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑品牌</h3>
                </div>
                <div class="panel-body">

                    <form action="" class="form-horizontal">
                        <!--<ul class="nav nav-tabs">-->
                        <!--<li class="active"><a href="#tab-general" data-toggle="tab">基本信息</a></li>-->
                        <!--<li><a href="#tab-data" data-toggle="tab">数据</a></li>-->
                        <!--<li><a href="#tab-seo" data-toggle="tab">SEO</a></li>-->
                        <!--<li><a href="#tab-design" data-toggle="tab">设计</a></li>-->
                        <!--</ul>-->

                        <div class="tab-content">
                            <!--<div class="tab-pane" id="tab-general" >1</div>-->

                            <div class="tab-pane active" id="tab-data">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-parent">品牌名称</label>
                                    <div class="col-sm-10">
                                        <input type="text" v-model="info.name"  placeholder="品牌名称" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-letter_pre">首字母</label>
                                    <div class="col-sm-10">
                                        <input type="text" v-model="info.letter_pre"  class="form-control"  >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-status">状态</label>
                                    <div class="col-sm-10">
                                        <select v-model="info.status" id="input-status" class="form-control">
                                            <option value="0">禁用</option>
                                            <option value="1" >启用</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left">品牌logo</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-left" id="brand-image"></td>
                                    </tr>
                                    </tbody>
                                </table>
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

                config:{

                    web_url:'<?php echo $this->url_pre;?>',
                },
                brand_id:0,

                search_list_show : 0,
                search_list:[],
                info:{
                    name:'',
                    sort_order:0,
                    status:0,
                    path:'',
                    parent_id:0,
                    image:'',
                },

                image:{
                    url:''
                }
            }

        },

        methods:{

            clear_dropdown:function(){

                this.search_list_show = 0;

            },

            search:function(){

                var t = this;
                var url = this.config.web_url + '&r=brand.search';
                url += '&filter=' + this.info.path;
                this.search_list_show = 0;
                axios.get( url ).then(function( res ){

                    t.search_list = res.data;
                    t.search_list_show = 1;

                });
            },

            do_edit:function(){

                var t = this;
                var url = this.config.web_url + '&r=brand.edit';
                url += '&brand_id=' + this.brand_id;

                var data = {
                    name:this.info.name,
                    letter_pre:this.info.letter_pre,
                    status:this.info.status,
                    image : $('input[name="image"]').val(),
                };

                var data_str = '';
                for( var p in data ){
                    data_str += p + '=' + data[p] + '&';
                }

                axios.post( url, data_str ).then(function( res ){

                    if( res.data.status == 0 ){
                        location.href = t.config.web_url + '&r=brand.page_list';
                    }

                });


            },

            do_create:function(){

                var t = this;

                var url = this.config.web_url + '&r=brand.create';
                var data = {
                    name:this.info.name,
                    letter_pre:this.info.letter_pre,
                    image : $('input[name="image"]').val(),
                    status:this.info.status
                };

                var data_str = '';
                for( var p in data ){
                    data_str += p + '=' + data[p] + '&';
                }

                axios.post( url, data_str ).then(function( res ){

                    if( res.data.status == 0 ){

                        location.href = t.config.web_url + '&r=brand.page_list';
                    }

                });
            },

            single:function(){

                var t = this;

                var url = this.config.web_url + '&r=brand.single';
                url += '&id=' + this.brand_id;
                axios.get( url ).then(function( res ){

                    t.info = res.data;
                    t.image.url = t.info.image;
                    get_field_image({
                        append_dom:'#brand-image',
                        name:'image',
                        value:t.info.image
                    });
                });
            },

        },

        created:function(){

            var brand_id = getQueryString( 'brand_id' );
            if( !brand_id ){
                this.brand_id = 0;
                get_field_image({
                    append_dom:'#brand-image',
                    name:'image',
                });
            }else{
                this.brand_id = brand_id;
                this.single();
            }


        }

    });
</script>
