<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH)?>

<style>

    img.avatar{

        width:100px;
        border-radius: 10px;
    }

</style>
<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH)?>

    <div id="content" >

        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button  class="btn btn-primary" v-on:click="save()" data-original-title="保存">
                        <i class="fa fa-save"></i>
                    </button>
                    <!--<button type="button" id="save-variant" title="" class="btn btn-success" >
                        <i class="fa fa-save"></i>
                    </button>-->
                    <a v-bind:href="url_pre + '&r=customer.page_list'" title=""
                       class="btn btn-default" >
                        <i class="fa fa-reply"></i>
                    </a>
                </div>
                <h1>群组管理</h1>
                <ul class="breadcrumb">
                    <li>首页</li>
                    <li>群组管理</li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> 群组管理</h3>
                </div>

                <div class="panel-body">

                    <div class="form-horizontal">

                        <div class="tab-content">

                            <div class="tab-pane active" >


                                <div class="form-group required">
                                    <label class="col-md-2 control-label" for="input-name">
                                        名称
                                    </label>
                                    <div class="col-md-10">
                                        <input type="text"  v-model="customerGroup.name" value=""
                                               placeholder="名称"
                                               id="input-name" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-md-2 control-label" for="input-description">
                                        描述
                                    </label>
                                    <div class="col-md-10">
                                        <textarea type="text" 
                                                  v-model="customerGroup.description"
                                                  value=""
                                               placeholder="描述"
                                               id="input-description" class="form-control">

                                        </textarea>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-md-2 control-label" for="input-is_default">
                                        默认
                                    </label>
                                    <div class="col-md-10">
                                        <select v-model="customerGroup.is_default"
                                                class="form-control"
                                                id="input-is_default">
                                            <option value="0">否</option>
                                            <option value="1">是</option>
                                        </select>

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

<script>

    var vue = new Vue({
        el: '#container',
        name: "customer",

        data:function () {

            return{

                customer_group_id:0,
                customerGroup:{
                    id:0,
                    name:'',
                    description:'',
                    is_default:0
                },

                url_pre:'<?php echo $this->url_pre;?>',
            }

        },

        methods:{

            save:function(){

                var t = this;
                var url = this.url_pre + '&r=customerGroup.edit';
                url += '&id=' + this.customer_group_id;

                var data = '';

                for( var p in this.customerGroup ){
                    data += p + '=' + this.customerGroup[p] + '&';
                }

                axios.post( url, data ).then(function( res ){

                    if( res.data.status==0 ){

                        //todo 跳转到列表
                        location.href = t.url_pre + '&r=customerGroup.page_list';
                    }else{

                        alert( res.data.description );

                    }


                });

            },

            get_customer_group:function(){

                var t = this;
                var url = this.url_pre + '&r=customerGroup.single';

                url += '&id=' + this.customer_group_id;

                axios.get( url ).then(function( res ){

                    t.customerGroup = res.data;

                });

            }

        },

        created:function(){

            var customer_group_id = getQueryString( 'id' );
            if( customer_group_id ){
                this.customer_group_id = customer_group_id;
                this.get_customer_group();
            }


        }

    });
</script>