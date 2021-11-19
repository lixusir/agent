<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
    <div id="content" class="cye-lm-tag">
        <div class="page-header cye-lm-tag">
            <div class="container-fluid cye-lm-tag">
                <div class="pull-right">
                    <a
                            v-on:click="banner_save()"
                            title="保存"
                            class="btn btn-primary"
                    >
                        <i class="fa fa-save"></i>
                    </a>
                    <a v-bind:href="config.web_url+'&r=banner.page_list'"
                       data-toggle="tooltip" title=""
                       class="btn btn-default" data-original-title="取消">
                        <i class="fa fa-reply"></i>
                    </a>
                </div>
                <h1>横幅管理</h1>
                <ul class="breadcrumb">
                    <li><a href="#">首页</a></li>
                    <li><a href="#">横幅管理</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid cye-lm-tag">
            <div class="panel panel-default cye-lm-tag">
                <div class="panel-heading cye-lm-tag">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑横幅</h3>
                </div>
                <div class="panel-body cye-lm-tag">
                    <form action="http://opencart.cloud.com/admin/index.php?route=design/banner/edit&amp;user_token=91c2ab49f0ffc9edbbaaaa3810eb103c&amp;banner_id=7" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal cye-lm-tag">
                        <div class="form-group required cye-lm-tag">
                            <label class="col-sm-2 control-label cye-lm-tag" for="input-name">横幅位置</label>
                            <div class="col-sm-10 cye-lm-tag">
<!--                                <input type="text" name="name" v-model="banner_info.name"-->
<!--                                       placeholder="横幅名称" id="input-name" class="form-control cye-lm-tag">-->
                                <select v-model="banner_info.name" name="status"
                                        id="input-name" class="form-control cye-lm-tag">
                                    <option value="home" >首页</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group required cye-lm-tag">
                            <label class="col-sm-2 control-label cye-lm-tag" for="input-name">图片宽度</label>
                            <div class="col-sm-10 cye-lm-tag">
                                <input type="text" name="name" v-model="banner_info.image_width"
                                       placeholder="图片宽度" id="input-image_width" class="form-control cye-lm-tag">

                            </div>
                        </div>
                        <div class="form-group required cye-lm-tag">
                            <label class="col-sm-2 control-label cye-lm-tag" for="input-name">图片高度</label>
                            <div class="col-sm-10 cye-lm-tag">
                                <input type="text" name="name" v-model="banner_info.image_height"
                                       placeholder="图片高度" id="input-image_height" class="form-control cye-lm-tag">

                            </div>
                        </div>
                        <div class="form-group cye-lm-tag">
                            <label class="col-sm-2 control-label" for="input-status">状态</label>
                            <div class="col-sm-10 cye-lm-tag">
                                <select v-model="banner_info.status" name="status"
                                        id="input-status" class="form-control cye-lm-tag">
                                    <option value="1" >启用</option>
                                    <option value="0" >禁用</option>
                                </select>
                            </div>
                        </div>
                        <br>


                        <div class="tab-pane active" >
                            <table id="images1" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td class="text-left">标题</td>
                                    <td class="text-left">链接</td>
                                    <td class="text-center">图片</td>
                                    <td class="text-right">排序</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in banner_image_list"
                                    v-show="item.is_show"
                                    id="image-row2" >
                                    <td class="text-left">
                                        <input type="text" v-model="item.title"
                                               placeholder="标题"
                                               class="form-control">
                                    </td>
                                    <td class="text-left" style="width: 30%;">
                                        <input type="text"  v-model="item.link"
                                               placeholder="链接" class="form-control">
                                    </td>
                                    <td v-bind:id="'banner-image-'+item.index" class="text-center"  >



<!--                                        <a v-on:click="editImage( item )" id="thumb-image"
                                           data-toggle="image" class="img-thumbnail">
                                            <img v-show="item.url" style="width:100px" v-bind:src="config.web_url + item.image" alt="" title="" />
                                            <img v-show="!item.url" style="width:100px" src="/image/placeholder.png" alt="" title="" />
                                        </a>-->

                                    </td>
                                    <td class="text-right"
                                        style="width: 10%;">
                                        <input type="text" v-model="item.sort_order"
                                               value="1" placeholder="排序" class="form-control"></td>
                                    <td class="text-left">
                                        <button type="button" v-on:click="remove_image( item )"
                                                data-toggle="tooltip" title="" class="btn btn-danger"
                                                data-original-title="删除">
                                            <i class="fa fa-minus-circle"></i>
                                        </button>
                                    </td>
                                </tr>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-left">
                                        <button type="button" v-on:click="add_image()"
                                                data-toggle="tooltip" title="" class="btn btn-primary"
                                                data-original-title="添加横幅"><i class="fa fa-plus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<script>
    // import gallery from '../../assets/backend/js/gallery'
    var vue = new Vue({
        el:'#container',
        name: "banner_edit",
        data:function(){

            return {
                config:{
                    web_url:'<?php echo $this->url_pre?>'
                },
                banner_info:{
                    banner_id:0,
                    name:'',
                    image_width:0,
                    image_height:0,
                    status:0,
                    image_list:[]
                },
                banner_image_list:[],

                image_item:{
                    banner_id   : 0,
                    banner_image_id:0,
                    title       : '',
                    link        : '',
                    image       : '',
                    sort_order  : '',
                    url         : '',
                },
                image:{
                    url:''
                },
                image_index:0,
            }
        },

        methods:{

            get_banner:function(){

                var t = this;
                var url = this.config.web_url + '&r=banner.single';
                url +=  '&banner_id=' + this.banner_id;
                axios.get( url ).then(function( res ){

                    t.banner_info = res.data;


                    for(var i = 0;i < t.banner_info.image_list.length; i ++ ){
                        t.banner_info.image_list[i].url = t.banner_info.image_list[i].image;


                        t.add_image( t.banner_info.image_list[i] );
                    }
                    // t.banner_image_list = t.banner_info.image_list;
                });
            },

            add_image:function( img ){

                var new_image = {
                    banner_id   : 0,
                    banner_image_id:0,
                    title       : img?img.title:'',
                    link        : img?img.link:'',
                    image       : img?img.image:'',
                    sort_order  : img?img.sort_order:'',
                    url         : img?img.url:'',
                    index       : this.image_index++,
                    is_show     : 1

                };

                this.banner_image_list.push( new_image );

                //todo 添加图片
                get_field_image({
                    append_dom  : '#banner-image-' + new_image.index,
                    name        : 'banner-image-' + new_image.index,
                    value       : new_image.url,

                });

            },

            remove_image:function( image ){

                var index = this.banner_image_list.indexOf( image );
                // this.banner_image_list.splice( index, 1 );
                image.is_show = 0;

            },

            editImage:function( image ){

                gallery.open( image, function(){

                    image.image = image.url;
                } );

            },

            banner_save:function(  ){

                var t = this;
                var url = '';
                if( this.banner_id ){
                    url = this.config.web_url + '&r=banner.edit';
                    url +=  '&banner_id=' + this.banner_id;
                }else{
                    url = this.config.web_url + '&r=banner.create';
                }

                var banner_image_list = [];
                this.banner_image_list.forEach(function( item, index ){

                    if( item.is_show ){
                        item.image = $( 'input[name="banner-image-'+ item.index + '"]' ).val();
                        banner_image_list.push( item );
                    }



                });

                this.banner_info.image_list = this.banner_image_list;

                var data = '';
                data += 'name=' + this.banner_info.name;
                data += '&status=' + this.banner_info.status;
                data += '&image_width=' + this.banner_info.image_width;
                data += '&image_height=' + this.banner_info.image_height;
                // data += '&image_list=' + JSON.stringify( this.banner_image_list ) ;
                data += '&image_list=' + JSON.stringify( banner_image_list ) ;


                axios.post( url, data ).then(function( res ){

                    if( !res.data.status ){

                        // t.$router.push('/design/banner');
                        location.href = t.config.web_url + '&r=banner.page_list';
                    }else{

                        alert( res.data.description );
                    }

                });
            }

        },

        computed:{



        },

        created:function(){

            var banner_id = getQueryString('banner_id');
            if( banner_id ){
            // if( this.$route.params.banner_id ){
                this.banner_id = banner_id;
                this.get_banner();
            }else{
                this.banner_id = 0;
            }



        }
    });
</script>

<style scoped>

</style>