<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>
<link rel="stylesheet" href="/addons/sm_shop/admin/assets/css/goods.css?v=1" >
<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
<div id="content" v-on:click="clear_dropdown()" >
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
                <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑商品</h3>
            </div>

            <div class="panel-body">
                <div class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab">基本信息</a></li>
                        <li class="">
                            <a href="#tab-data" data-toggle="tab"  >数据</a></li>
                        <li class="">
                            <a href="#tab-image" data-toggle="tab"  >图片</a></li>
                        <li class="">
                            <a href="#tab-specification" data-toggle="tab"  >规格</a></li>

                        <li class="">
                            <a href="#tab-pindan" data-toggle="tab" >拼单</a></li>

                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="tab-general">
                            <div class="form-group required">
                                <label class="col-md-2 control-label" for="input-name2">商品名称</label>
                                <div class="col-md-10">
                                    <input type="text" v-model="info.name" value=""
                                           placeholder="商品名称" id="input-name2" class="form-control">
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-md-2 control-label" for="input-name2">商品型号</label>
                                <div class="col-md-10">
                                    <input type="text" v-model="info.model" value=""
                                           placeholder="商品型号" class="form-control">
                                </div>
                            </div>

                            <div class="form-group required">
                                <label class="col-md-2 control-label" for="input-name2">商品品牌</label>
                                <div class="col-md-10">
                                    <select class="form-control" v-model="info.brand_id" >
                                        <option v-for="brand in brand_list" v-bind:value="brand.id">{{brand.name}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label" for="input-category"><span data-toggle="tooltip" title="" data-original-title="自动完成">商品分类</span></label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></div>
                                        <input type="text" name="category"
                                               v-model="category_filter"
                                               v-on:keyup="search_category()"
                                               v-on="{kyup:search_category,mouseup:search_category}"
                                               placeholder="商品分类" id="input-category" class="form-control" autocomplete="off">
                                        <ul class="dropdown-menu"
                                            style="top: 36px; left: 40px; "
                                            v-bind:style="{display:category_list_show ? 'block':'none'}"
                                        >
                                            <li v-for="item in category_list">
                                                <a v-on:click="check_category( item )" >{{item.cat_name}}</a>
                                            </li>
                                            <!--
                                            <li data-value="59">
                                                <a href="#">760T套装系列</a>
                                            </li>
                                            -->
                                        </ul>
                                    </div>
                                    <div id="product-category" class="well well-sm" style="min-height: 150px; overflow: auto;">

                                        <div v-for="item in selected_category" >
                                            <i v-on:click="delete_selected_category( item )" class="fa fa-minus-circle"></i>{{item.cat_name}}
                                            <!--<input type="hidden" name="product_category[]" value="59">-->
                                        </div>

                                        <!--
                                        <div id="product-category59">
                                            <i class="fa fa-minus-circle"></i>760T套装系列
                                            <input type="hidden" name="product_category[]" value="59">
                                        </div>
                                        <div id="product-category60">
                                            <i class="fa fa-minus-circle"></i> 660系列
                                            <input type="hidden" name="product_category[]" value="60">
                                        </div>
                                        -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status">状态</label>
                                <div class="col-sm-10">
                                    <select v-model="info.status" id="input-status" class="form-control">
                                        <option value="0">下架</option>
                                        <option value="1" >上架</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="input-name2">描述</label>
                                <div class="col-md-10" id="description-field">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="tab-data" >
                            <div class="form-group" >
                                <label class="col-md-2 control-label"
                                       for="input-price">销售价格</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-addon">￥</div>
                                        <input v-model='info.price' type="text" name="number"  placeholder="销售价格" id="input-price" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" >
                                <label class="col-md-2 control-label"
                                       for="input-price">特殊价格</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-addon">￥</div>
                                        <input v-model='info.special' type="number" name="special"  placeholder="特殊价格" id="input-special" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" >
                                <label class="col-md-2 control-label"
                                       for="input-price">销售数量</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input v-model='info.sale' type="number" min="0" name="sale"
                                               placeholder="销售数量" id="input-sale" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" >
                                <label class="col-md-2 control-label"
                                       for="input-price">每日增长销售数量</label>
                                <div class="col-md-6">
                                    <div class="input-group">

                                        <input style="float:left" v-model='info.sale_add' type="number" min="0" name="sale_add"
                                               placeholder="销售增长数量" id="input-sale" class="form-control">
                                        <div class="input-group-addon">随机增长范围:0-{{info.sale_add}}</div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group" >
                                <label class="col-md-2 control-label"
                                       for="input-price">销售增长计划任务URL</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" readonly v-model="info.cron_goods" />
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab-image">

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left">主图</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td id="main-image"></td>
                                    </tr>
                                    <tr style="display:none">
                                        <td class="text-left">
                                            <a v-on:click="editImage(image)" id="thumb-image"
                                               data-toggle="image" class="img-thumbnail">
                                                <img style="width:100px" v-bind:src="config.web_url + image.url" alt="" title="" />
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table id="images" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left">附加图</td>
                                        <td class="text-right">排序</td>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item,index in goods_images" v-show="item.is_show" >
                                        <td class="text-left"
                                            v-html="item.field_image"
                                            v-bind:id="'goods_images-' + item.index">
                                            <!--
                                            <a  id="thumb-image0" data-toggle="image" class="img-thumbnail product-img">
                                                <img style='width:100px' v-bind:src="config.web_url + item.url" >
                                            </a>
                                            <input type="hidden"  >
                                            -->
                                        </td>
                                        <td class="text-right">
                                            <input type="text" v-model="item.sort_order" placeholder="排序" class="form-control">
                                        </td>
                                        <td class="text-left">
                                            <button type="button" class="btn btn-danger" title="删除"
                                                    v-on:click="removeImage( item )" >
                                                <i class="fa fa-minus-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" title="编辑"
                                                    v-on:click="editImage( item )" >
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td class="text-left">
                                            <button type="button" v-on:click="addImage()"
                                                    data-toggle="tooltip" title=""
                                                    class="btn btn-primary"
                                                    data-original-title="添加图片" >
                                                <i class="fa fa-plus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab-specification">

                            <div class="form-group ">


                                <label class="col-md-2 control-label" for="input-name2">规格列表</label>
                                <div class="col-md-10">
                                    <div v-for="spec in specification">

<!--                                        <input type="text" v-model="spec.name" >-->
                                        <el-row :gutter="20" class="mt-10">
                                            <el-col :span="6">
                                                <el-input v-model="spec.name" ></el-input>
                                            </el-col>

                                            <el-col :span="6">
                                                <el-tooltip placement="top" content="添加规格选项，例如“颜色”规格下的一项：白色">
                                                    <a class="btn btn-primary"
                                                       v-on:click="add_specification_value( spec )" >添加规格项
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </el-tooltip>
                                                <a class="btn btn-primary"
                                                   v-on:click="remove_specification_value( spec )"
                                                   data-original-title="删除规格选项" >
                                                    删除
                                                </a>
                                            </el-col>
                                        </el-row>






                                        <div class="specification-values">

                                            <el-row v-for="spec_val in spec.values" class="mt-10">
                                                <el-col :span="4">
<!--                                                    <input type="text" v-model="spec_val.name" >-->
                                                    <el-input v-model="spec_val.name"></el-input>

                                                </el-col>
                                                <el-col :span="4">
                                                    <span v-on:click="delete_spec_val( spec, spec_val )" class="ml-10 btn btn-primary">删除</span>
                                                </el-col>


                                            </el-row>

                                        </div>

                                    </div>

                                    <el-tooltip  placement="top" content="添加规格，例如：颜色" class="mt-10">
                                        <a class="btn btn-primary"
                                           v-on:click="add_specification()" >添加规格
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </el-tooltip>

                                </div>
                            </div>

                            <div class="form-group ">

                                <div class="col-md-12">

                                    <table class="spec-table"  >

                                        <thead>
                                        <tr>
                                            <td v-for="(item,index) in specification">
                                                <p>{{item.name}}</p>
                                            </td>
                                            <td class="price">
                                                <p>价格</p>
                                                <div style="position:relative">
                                                    <input type="text" v-model="spec_price_temp" >
                                                    <el-tooltip content="向下复制" placement="top">
                                                        <i @click="copy_spec_price" class="el-icon-bottom" style="color:#b3b1b1;cursor:pointer; position: absolute; right:5px; top:6px;"></i>
                                                    </el-tooltip>
                                                </div>

                                            </td>
                                            <td class="price">
                                                <p>拼单价格</p>
                                                <div style="position:relative">
                                                    <input type="text" v-model="spec_price_pindan_temp" >
                                                    <el-tooltip content="向下复制" placement="top">
                                                        <i @click="copy_spec_price_pindan" class="el-icon-bottom" style="color:#b3b1b1;cursor:pointer; position: absolute; right:5px; top:6px;"></i>
                                                    </el-tooltip>

                                                </div>
                                            </td>
                                            <td class="stock">
                                                <p>库存</p>
                                                <div style="position:relative">

                                                    <input type="text" v-model="spec_stock_temp">
                                                    <el-tooltip content="向下复制" placement="top">
                                                        <i @click="copy_spec_stock" class="el-icon-bottom" style="color:#b3b1b1;cursor:pointer; position: absolute; right:5px; top:6px;"></i>
                                                    </el-tooltip>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        <tr v-for="row in spec_combination_count">


                                            <template v-for="(item,col) in specification">
                                                <td v-spec:td="spec_test(specification, col, row-1)"></td>
                                            </template>

                                            <td class="price"><input type="text" v-model="spec_price[row-1]"></td>
                                            <td class="price"><input type="text" v-model="spec_price_pindan[row-1]"></td>
                                            <td class="stock"><input type="text" v-model="spec_stock[row-1]"></td>
                                        </tr>
                                        </tbody>


                                    </table>
                                    <a v-on:click="spec_save()" class="btn btn-primary"> 保存 </a>
                                </div>


                            </div>

                        </div>

                        <div class="tab-pane" id="tab-pindan" >

                            <div class="form-group" >
                                <label class="col-md-2 control-label"
                                       for="input-price">开启拼单</label>
                                <div class="col-sm-6">
                                    <select id="input-pindan"
                                            v-model="pindan.status"
                                            class="form-control">
                                        <option value="0">禁用</option>
                                        <option value="1" >启用</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-md-2 control-label"
                                       for="input-price">拼单价格</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-addon">￥</div>
                                        <input  type="text"
                                                v-model="pindan.price"
                                               placeholder="拼单价格"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group hide" >
                                <label class="col-md-2 control-label"
                                       for="input-price">拼单人数</label>
                                <div class="col-md-6">
                                    <input  type="number"
                                            v-model="pindan.number"
                                            placeholder="最少拼单人数"
                                            min="2"
                                            class="form-control">
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-md-2 control-label"
                                       for="input-price">有效时间</label>
                                <div class="col-md-6">
                                    <input  type="number"
                                            v-model="pindan.validate_time"
                                            placeholder="拼单有效时间，0代表永久有效"
                                            min="0"
                                            class="form-control">
                                </div>
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

    Vue.directive('spec', {
        bind: function (el, binding, vnode) {
            console.log( 'bind');
            var spec = binding.value.spec;
            var spec_value = binding.value.spec_value;
            el.innerHTML = spec_value.name;
            el.style.width = binding.value.col_width + '%';
        },

        inserted:function( el, binding, vnode ){

            console.log( 'inserted');
            console.log( arguments );

        },

        update:function( el, binding, vnode ){
            console.log( 'update');
            console.log( arguments );

            var spec = binding.value.spec;
            var spec_value = binding.value.spec_value;
            el.innerHTML = spec_value.name;
        }
    });

    var vue = new Vue({
        el:'#content',
        data:function(){

            return {

                goods_id:0,
                config:{
                    web_url:'<?php echo $this->url_pre;?>',
                },
                info:{
                    name:'',
                    brand_id:0,
                    model:'',
                    sale:0,
                    sale_add:0,
                    price:0,
                    special:0,
                    description:'',
                    image:'',
                    status:1,
                    cron_goods:''

                },
                field_image:'',
                image:{
                    url:''
                },
                brand_list:[],
                goods_images:[],
                goods_images_index:0,
                category_filter:'',
                category_list:[],     // 搜索到的 分类列表
                category_list_show:0,
                selected_category:[], // 选中的分类列表

                specification:[  ],

                spec_price:[ ],
                spec_price_pindan:[ ],
                spec_stock:[ ],
                spec_price_temp:'',
                spec_price_pindan_temp:'',
                spec_stock_temp:'',
                pindan:{
                    price:'',
                    status:0,
                    number:2,
                    validate_time:0
                }
            }
        },

        methods:{

            clear_dropdown:function(){

                this.category_list_show = 0;

            },

            get_product_info:function(){

                var t = this;
                // var url = this.config.web_url + '/admin.php?r=goods/info';
                var url = this.config.web_url + '&r=goods.info';
                url += '&goods_id=' + this.goods_id;



                axios.get( url ).then(function( res ){

                    if( !res.data.status ){

                        t.info = res.data.product;
                        t.image.url = t.info.image;
                        if( t.info.pindan ){
                            t.pindan = t.info.pindan;
                        }

                        if( t.info.goods_images ){

                            t.info.goods_images.forEach(function( item, index ){
                                item.index = index;
                                if( t.goods_images_index <= index ){
                                    t.goods_images_index = index + 1 ;
                                }
                                t.addImage( item );
                            });

                        }

                        t.selected_category = t.info.category;
                        t.get_field_editor({
                            append_dom:'#description-field',
                            name:'description',
                            value: t.info.description,
                        });

                        t.get_field_image({
                            append_dom:'#main-image',
                            name:'image',
                            value:t.info.image
                        });



                    }else{

                        alert( res.data.description );
                    }

                });

            },

            get_brand_list:function(){


                var t = this;
                var url = this.config.web_url + '&r=brand.index';
                axios.get( url ).then(function( res ) {

                    t.brand_list = res.data;
                });
            },

            do_edit:function(){

                var t = this;
                // var url = this.config.web_url + '/admin.php?r=goods/edit';
                var url = this.config.web_url + '&r=goods.edit';
                url += '&goods_id=' + this.goods_id;

                var data = this.info;

                // data.description = $('#product_description_ifr').contents().find('body').html();
                data.description = UE.getEditor('description').getContent();;

                // this.info.image = this.image.url;
                this.info.image = $('input[name="image"]').val();


                var post_goods_image = [];
                this.goods_images.forEach(function( item ){



                    if( item.is_show ){
                        console.log( $( 'input[name="goods_images-'+ item.index + '"]' ).val() );
                        post_goods_image.push( {
                            sort_order:item.sort_order,
                            url:$( 'input[name="goods_images-'+ item.index + '"]' ).val()
                        } );
                    }

                });

                data.goods_images = JSON.stringify( post_goods_image );

                var data_str = '';
                for( var p in data ){
                    data_str +=  p + '=' + encodeURIComponent( data[p] ) + '&';
                }

                for( var i = 0; i < t.selected_category.length; i ++ ){

                    data_str += 'category[]=' + t.selected_category[i].id + '&';

                }

                for(var p in t.pindan ){
                    data_str +='pindan[' + p + ']=' + t.pindan[p] + '&';
                }
                axios.post( url, data_str ).then(function( res ){

                    if( !res.data.status ){

                        location.href = t.config.web_url + '&r=goods.page_list';

                    }else{

                        alert( res.data.description );
                    }

                });

            },

            editor_init:function () {
                tinymce.init({
                    selector: 'textarea',
                    plugins: "code",
                    language:'zh_CN',

                    toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | code',
                    menubar: "tools",
                    removeformat : [
                        {selector : 'span', attributes : ['style', 'class'], split : true, expand : false, deep : true}
                    ]
                });
            }

            ,addImage:function( params ){
                var new_image = {
                    url: params?params.url:'',
                    sort_order:params?params.sort_order:0,
                    index:params?params.index:this.goods_images_index++,
                    field_image:'',
                    is_show:1,
                };
                this.goods_images.push(new_image);

                var params = {

                    append_dom:'#goods_images-' + new_image.index,
                    name:'goods_images-' + new_image.index,
                    value: new_image.url?new_image.url:'',
                };
                this.get_field_image( params,function( res ){

                    new_image.field_image = res.data;
                    console.log( new_image );
                } );


            },

            editImage:function( image_obj ){

                this.ckfinder_open( image_obj );

            },

            // 非vue 元素无法保存，智能采取隐藏的方式
            removeImage:function( item ){

                /*var index = this.goods_images.indexOf( item );
                console.log( index );
                console.log( this.goods_images );
                this.goods_images.splice( index, 1 );*/

                item.is_show = 0;

            },

            search_category:function(){

                var t = this;
                // var url = this.config.web_url + '/admin.php?r=category/search';
                var url = this.config.web_url + '&r=category.search';
                url += '&filter=' + this.category_filter;
                this.category_list_show = 0;
                axios.get( url ).then(function( res ){

                    t.category_list = res.data;
                    t.category_list_show = 1;

                });

            },


            check_category:function( item ){



                this.selected_category.push( item );

            },

            delete_selected_category:function( category ){

                var index = this.selected_category.indexOf( category );
                this.selected_category.splice( index, 1 );


            },

            ckfinder_open:function( image_obj ){


                var t = this;
                CKFinder.modal( {
                    width: 600,
                    height: 400,
                    chooseFiles: true,
                    onInit: function( finder ) {
                        finder.on( 'files:choose', function( evt ) {
                            console.log('files:choose');
                            var file = evt.data.files.first();
                            console.log(file.getUrl());
                            var url =  file.getUrl();
                            image_obj.url = url;
                            // console.log( image_obj );
                            // t.setImage(image_obj, url  );
                        } );
                        // finder.on( 'file:choose:resizedImage', function( evt ) {
                        //     console.log('files:choose:resizedImage');
                        //     document.getElementById( 'url' ).value = evt.data.resizedUrl;
                        // } );
                    }
                } );
            },

            get_field_editor:function( params ){

                var url = this.config.web_url + '&r=tool.field_editor';

                url += '&name=' + params.name;
                // if(params.value){
                    // url += '&value=' + params.value;
                // }

                axios.get( url ).then(function( res ){


                    if( params.append_dom ){
                        $( params.append_dom ).append( res.data );
                        $("textarea[name='description']").val( params.value );

                    }


                    typeof callback == 'function'?callback( res ):'';

                });
            },

            get_field_image:function( params, callback ){

                var url = this.config.web_url + '&r=tool.field_image';

                url += '&name=' + params.name;
                if(params.value){
                    url += '&value=' + params.value;
                }

                axios.get( url ).then(function( res ){


                    if( params.append_dom ){
                        $( params.append_dom ).append( res.data );
                    }


                    typeof callback == 'function'?callback( res ):'';

                });

            },

            get_spec_obj:function( spec_object ){

                var t = this;
                console.log( 'get_spec_obj' );
                spec_object.forEach( function( obj ){

                    var attr = obj.attr;
                    var arr = attr.split(',');
                    var row = 0;
                    arr.forEach(function( a  ){

                        var name = a.split(':')[0];
                        var value = a.split(':')[1];


                        t.specification.forEach(function( spec, index ){

                            if( name == spec.name ){
                                spec.values.forEach(function( val, val_index ){

                                    if( value == val.name ){
                                        row += spec.row_span * val_index;
                                    }

                                });
                            }

                        });

                    });


                    Vue.set(t.spec_price, row, obj.price);
                    Vue.set(t.spec_price_pindan, row, obj.price_pindan);
                    Vue.set(t.spec_stock, row, obj.stock);

                });
                console.log( t.spec_price );
                console.log( t.spec_price_pindan );
                console.log( t.spec_stock );
            },
            // todo 获取 商品规格
            get_specification:function(){

                var t = this;

                var url = this.config.web_url + '&r=goods.specification';
                url += '&goods_id=' + this.goods_id ;

                axios.get( url ).then(function( res ){

                    t.specification = [];
                    if( !res.data.status ){

                        for( var p in res.data.specification ) {
                            t.specification.push( res.data.specification[p] );
                        }


                        setTimeout(function(){
                            t.get_spec_obj( res.data.spec_object );
                        },3000);



                    }

                    console.log( t.specification );

                });
            },

            //todo 添加商品规格
            add_specification:function(){

                var new_spec = {
                    name:'',
                    values:[],
                    row_span:0 // 该规格在表格里的行跨度(每一个选项占几行)
                };

                this.specification.push( new_spec );



            },

            add_specification_value:function( spec ){

                var spec_value = {
                    name:''
                };

                spec.values.push( spec_value );


            },

            remove_specification_value:function( spec ){


                var index = this.specification.indexOf(spec);
                this.specification.splice(index, 1 );


            },

            delete_spec_val:function( spec, spec_val ){

                var index = spec.values.indexOf( spec_val );
                spec.values.splice( index, 1 );

            },

            spec_save:function(){

                //todo 保存规格数据

                var spec_empty = 0;
                //todo 检测 规格名称和选项数据是否为空

                this.specification.forEach(function( item ){

                    item.name = item.name.replace(/^(\s+)|(\s+)$/g,'');
                    if( !item.name ){
                        // alert('请填写商品规格名称');
                        // return 0;
                        spec_empty = 1;
                    }

                    item.values.forEach(function( val_item ){

                        val_item.name = val_item.name.replace(/^(\s+)|(\s+)$/g,'');
                        if( !val_item.name ){
                            // alert('请填写商品规格选项名称');
                            // return 0;
                            spec_empty = 1;
                        }
                    });

                });

                if( spec_empty ){
                    alert('请填写商品规格名称');
                    return 0;
                }

                // todo 整理数据， 提交

                var spec_obj_arr = [];
                for( var row = 0; row < this.spec_combination_count; row ++ ){

                    var spec_obj = {

                        spec_arr:[],
                        price: this.spec_price[row],
                        price_pindan: this.spec_price_pindan[row],
                        stock: this.spec_stock[row],

                    };

                    this.specification.forEach(function( item ){

                        var turn = parseInt( row / item.row_span );
                        turn = turn % item.values.length;
                        // var spec = {
                        //     name: item.name,
                        //     value: item.values[turn],
                        // };
                        var spec = item.name + ':' + item.values[turn].name;
                        spec_obj.spec_arr.push( spec );

                    });

                    spec_obj_arr.push( spec_obj );
                }

                // console.log( this.specification );
                // console.log( spec_obj_arr );



                // todo

                var t = this;

                var url = this.config.web_url + '&r=goods.save_specification';
                url += '&goods_id=' + this.goods_id ;


                var data = 'specification=' + JSON.stringify( this.specification );
                data += '&spec_obj=' + JSON.stringify( spec_obj_arr );

                axios.post( url, data ).then(function( res ){

                    if( !res.data.status ){
                        alert('商品规格参数保存成功');
                    }

                });



            },

            spec_test:function( spec, col, row ){

                console.log( this.spec_price );
                console.log( this.spec_price_pindan );
                console.log( this.spec_stock );

                var row_span = this.spec_combination_count;
                spec.forEach(function( item ){

                    row_span = row_span / item.values.length;
                    item.row_span = row_span;

                });


                var turn = parseInt( row / spec[col].row_span );

                turn = turn % spec[col].values.length;

                return {
                    col_width: parseInt( 100 / (spec.length+2) ),
                    col:col,
                    row:row,
                    spec       : spec[col],
                    spec_value : spec[col].values[turn]
                };
            },

            copy_spec_price:function(){

                var t = this;
                this.spec_price = [];
                for( var i = 0;i < this.spec_combination_count; i ++ ){
                    t.spec_price.push( t.spec_price_temp );
                }
            },
            copy_spec_price_pindan:function(){
                var t = this;
                this.spec_price_pindan = [];
                for( var i = 0;i < this.spec_combination_count; i ++ ){
                    t.spec_price_pindan.push( t.spec_price_pindan_temp );
                }
            },
            copy_spec_stock:function(){
                var t = this;
                this.spec_stock = [];
                for( var i = 0;i < this.spec_combination_count; i ++ ){
                    t.spec_stock.push( t.spec_stock_temp );
                }
            },

        },

        computed:{

            spec_combination_count:function(){

                var count = 1;
                for( var i = 0; i < this.specification.length; i ++ ){
                    count *= this.specification[i].values.length;
                }

                console.log(count);
                return count;

            },

        },

        watched:{

            spec_price:function( a, b ){

                console.log('watch spec_price:');
                console.log(a);
                console.log(b);
            }
        },

        created:function(){

            var t = this;

            // var params = this.$route.params;
            var params = {};


            var goods_id = getQueryString('goods_id');


            if( goods_id ) {
                this.goods_id = goods_id;
                this.get_product_info();
                this.get_specification();
            }else{

                this.get_field_image({
                    append_dom:'#main-image',
                    name:'image',
                });
                this.get_field_editor({
                    append_dom:'#description-field',
                    name:'description',
                });
            }

            this.get_brand_list();

            // setTimeout(function(){
                // t.editor_init();
            // },1000);


            // this.ckfinder_open();
        }

    });

</script>
