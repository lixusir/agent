<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH)?>
<link href="/addons/sm_shop/admin/assets/css/jquery.contextMenu.css" rel="stylesheet">
<script src="/addons/sm_shop/admin/assets/js/designer.js?v=0.0.1"></script>
<script src="/addons/sm_shop/admin/assets/js/jquery.contextMenu.js?v=0.0.1"></script>
<script charset="utf-8" src="https://map.qq.com/api/gljs?v=1.exp&key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77"></script>
<!--<script charset="utf-8" src="https://map.qq.com/api/gljs?v=1.exp&key=UQSBZ-NWHWU-36AVM-4DM5O-MOEEF-2CFLA"></script>-->
<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH)?>

    <div id="content" >
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button v-on:click="do_edit()" class="btn btn-primary" data-original-title="保存">
                        <i class="fa fa-save"></i>
                    </button>
                    <!--<button type="button" id="save-variant" title="" class="btn btn-success" >
                        <i class="fa fa-save"></i>
                    </button>-->
                    <a v-bind:href="config.web_url + '&r=pickup.page_list'" title="" class="btn btn-default" >
                        <i class="fa fa-reply"></i>
                    </a>
                </div>
                <h1>订单自提点</h1>
                <ul class="breadcrumb">
                    <li><a href="#">模块管理</a></li>
                    <li><a href="#">订单自提点</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> 编辑自提点</h3>
                </div>

                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="tab-content">

                            <div class="form-group">
                                <label class="col-md-2 control-label" >名称</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.name"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" >电话</label>
                                <div class="col-md-10">
                                    <input type="number"  v-model="info.phone"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" >省</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.province"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" >市</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.city"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" >区，县</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.area"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" >街道</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.street"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" >详细地址</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.detail"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" >纬度</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.latitude"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" >经度</label>
                                <div class="col-md-10">
                                    <input type="text"  v-model="info.longitude"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" >状态</label>
                                <div class="col-md-10">
                                    <select v-model="info.status" id="input-status" class="form-control">
                                        <option value="0">禁用</option>
                                        <option value="1" >启用</option>
                                    </select>


                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- 地图开始 -->
    <div class="map-field" style="margin-left:235px;">
        <div class="container-fluid">
            <div class="panel">
                <div id="map-container" style="width:100%; height:600px;"></div>
            </div>

        </div>
    </div>
</div>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH)?>

<script>

    var vue = new Vue({
        el:'#content',
        data:function () {

            return{

                pickup_id:0,
                info:{
                    name:'',
                    phone:'',
                    province:'',
                    city:'',
                    area:'',
                    street:'',
                    detail:'',
                    latitude:'',
                    longitude:'',
                    status:'',
                },

                config:{
                    web_url:'<?php echo $this->url_pre;?>',
                },

            }
        },

        methods:{

            pick_up_info:function(){

                var t = this;
                var url = this.config.web_url + '&r=pickup.info';
                url += '&id=' + this.pickup_id;
                axios.get( url ).then(function( res ){

                    t.info = res.data;
                    t.map_init();
                });
            },

            do_edit:function () {

                var t = this;
                var url = this.config.web_url + '&r=pickup.edit';

                var data = '';
                for(var p in this.info ){

                    data += '&' + p + '=' + this.info[p]

                }

                axios.post( url, data ).then(function( res ){

                    if( res.data.status == 0 ){
                        location.href = t.config.web_url + '&r=pickup.page_list';
                    }else{
                        alert( res.data.description );
                    }


                });


            },

            map_init:function(  ) {

                var center = new TMap.LatLng(39.984104, 116.307503);//设置中心点坐标
                if( this.info.latitude && this.info.longitude ){
                    center = new TMap.LatLng( this.info.latitude, this.info.longitude);//设置中心点坐标
                }

                var t = this;


                //初始化地图
                var map = new TMap.Map("map-container", {
                    center: center
                });

                //初始化marker图层
                var markerLayer = new TMap.MultiMarker({
                    id: 'marker-layer',
                    map: map
                });

                if( this.info.latitude && this.info.longitude ){
                    markerLayer.add({
                        position: {
                            lat:this.info.latitude,
                            lng:this.info.longitude,
                        }
                    });
                }
                //监听点击事件添加marker
                map.on("click", ( evt ) => {
                    markerLayer.setGeometries([]);
                    markerLayer.add({
                        position: evt.latLng
                    });

                    console.log( evt );
                    t.info.latitude = evt.latLng.lat.toFixed(6);
                    t.info.longitude = evt.latLng.lng.toFixed(6);
                    t.fenxiLocation( evt.latLng );
                });

            },

            fenxiLocation:function( latlng ){

                var t = this;
                var url = this.config.web_url + '&r=pickup.latlng_to_addr';

                url += '&lat=' + latlng.lat;
                url += '&lng=' + latlng.lng;
                axios.get( url ).then(function( res ){

                    if( !res.data.status ){
                        var addr = res.data.result;
                        t.info.city = addr.address_component.city;
                        t.info.area = addr.address_component.district;
                        t.info.province = addr.address_component.province;
                        t.info.street = addr.address_component.street;
                        // t.info.detail = addr.street_number;
                        t.info.detail = addr.formatted_addresses.recommend;
                    }

                });

            },

        },

        created:function(){

            this.pickup_id = getQueryString( 'id' );
            if( this.pickup_id ){
                this.pick_up_info();
            }else{
                this.map_init();
            }


        }
    });
</script>

<script>





</script>


