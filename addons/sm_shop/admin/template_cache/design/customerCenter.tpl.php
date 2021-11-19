<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
    <div id="content" class="cye-lm-tag">
        <div class="page-header cye-lm-tag">
            <div class="container-fluid cye-lm-tag">
                <div class="pull-right">
                    <a
                            v-on:click="save()"
                            title="保存"
                            class="btn btn-primary"
                    >
                        <i class="fa fa-save"></i>
                    </a>
                    <a v-bind:href="config.web_url+'&r=design.customerCenter.page_list'"
                       data-toggle="tooltip" title=""
                       class="btn btn-default" data-original-title="取消">
                        <i class="fa fa-reply"></i>
                    </a>
                </div>
                <h1>用户中心设置</h1>
                <ul class="breadcrumb">
                    <li><a href="#">首页</a></li>
                    <li><a href="#">用户中心</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid cye-lm-tag">
            <div class="panel panel-default cye-lm-tag">
                <div class="panel-heading cye-lm-tag">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> 用户中心编辑</h3>
                </div>
                <div class="panel-body cye-lm-tag">
                    <form  method="post"  id="form-customerCenter" class="form-horizontal cye-lm-tag">
                        <div class="form-group required cye-lm-tag">
                            <label class="col-sm-2 control-label cye-lm-tag" >背景图片</label>
                            <div class="col-sm-10 cye-lm-tag">
                                <div id="bg-img"></div>
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
    // import gallery from '../../assets/backend/js/gallery'
    var vue = new Vue({
        el:'#container',
        name: "banner_edit",
        data:function(){

            return {
                config:{
                    web_url:'<?php echo $this->url_pre?>'
                },
                info:{
                    bg_img:''
                }
            }
        },

        methods:{


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

            init:function(){

                //todo 获取数据
                var t = this;
                var url = this.config.web_url + '&r=design.customerCenter.index';

                axios.get( url ).then(function( res ){

                    t.info.bg_img = res.data.bg_img;
                    t.get_field_image({
                        append_dom:'#bg-img',
                        name:'bg_img',
                        value:t.info.bg_img
                    });
                });


            },

            save:function(){

                var t = this;
                var url = this.config.web_url + '&r=design.customerCenter.edit';
                this.info.bg_img = $('input[name="bg_img"]').val();
                var data = 'bg_img=' + this.info.bg_img;
                axios.post( url, data ).then(function( res ){

                    if( !res.data.status ){
                        alert( res.data.description?res.data.description:'保存成功');
                    }
                    //todo 提示框， 或者刷新页面
                    window.location.reload();


                });
            }

        },

        computed:{



        },

        created:function(){

            this.init();




        }
    });
</script>

<style scoped>

</style>