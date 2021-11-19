<?php defined('IN_IA') or exit('Access Denied');?><div id="file-manager" class="hide">

    <div>
        <el-dialog
                title="图片管理器"

                :visible.sync="fileManagerVisible"
                width="900px"
                :before-close="close"
                center>
            <el-container style="height: 500px; border: 1px solid #eee">

                <el-aside width="200px" style="background-color: rgb(238, 241, 246)">

                    <el-button type="text" class="add-dir-button" @click="add_new_dir()">添加目录</el-button>
                    <el-menu class="p-t40" @open="openMenu" @select="selectMenu">

                        <template v-for="(item,index) in dir">
                            <el-menu-item v-if="!item.child"
                                          :index="item.name" >
                                <i class="el-icon-message"></i>
                                {{item.name}}
                                <!--                            <i class="el-icon-delete" class="fr"></i>-->
                                <span class="dir-delete"  @click.stop="dir_delete( item.name )">删除</span>
                            </el-menu-item>
                            <el-submenu v-if="item.child" :index="item.name" >

                                <template slot="title"><i class="el-icon-message"></i>{{item.name}}</template>

                                <template v-for="(child,index) in item.child">
                                    <el-menu-item v-if="!child.child"
                                                  :index="item.name+'-'+child.name"
                                    >{{child.name}}
                                        <span class="dir-delete" @click.stop="dir_delete( item.name+'/'+child.name )">删除</span>
                                    </el-menu-item>
                                    <el-submenu v-if="child.child"
                                                :index="item.name+'-'+child.name">
                                        <template slot="title">
                                        <span>
                                            {{child.name}}
                                        </span>
                                        </template>

                                        <template v-if="child.child!=[]">
                                            <el-menu-item v-for="(child_child,index) in child.child"
                                                          :index="item.name+'-'+child.name + '-' + child_child.name">
                                                {{child_child.name}}
                                                <span class="dir-delete" @click.stop="dir_delete( item.name+'/'+child.name + '/' + child_child.name )">删除</span>
                                            </el-menu-item>
                                        </template>

                                    </el-submenu>
                                </template>




                            </el-submenu>
                        </template>

                    </el-menu>
                </el-aside>

                <el-container>
                    <el-header style="text-align: right; font-size: 12px;display:none">
                        <el-dropdown>
                            <i class="el-icon-setting" style="margin-right: 15px"></i>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item>查看</el-dropdown-item>
                                <el-dropdown-item>新增</el-dropdown-item>
                                <el-dropdown-item>删除</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </el-header>

                    <el-main>
                        <div class="image-content">

                            <div class="item">
                                <el-upload
                                        class="avatar-uploader"
                                        :action="url_pre+'&r=fileManager.file_add'+'&path='+current_dir"
                                        :show-file-list="false"
                                        :on-success="uploadSuccess"
                                        :before-upload="beforeUpload">
                                    <img v-if="upload_image" :src="upload_image" class="avatar">
                                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                </el-upload>
                            </div>

                            <div v-for="(item, index) in images"
                                 class="item">
                                <el-tooltip :content="item.name" placement="top">
                                    <img :src="item.url" alt="">
                                </el-tooltip>
                                <div class="name">{{item.name}}</div>
                                <div class="tool">
                                    <i class="el-icon-delete" @click="image_remove( item )"></i>
                                    <i class="el-icon-check" @click="image_check( item )"></i>
                                </div>

                            </div>


                        </div>
                    </el-main>
                </el-container>

            </el-container>
            <div slot="footer" class="dialog-footer">
                <el-button @click="close()">取 消</el-button>
                <el-button type="primary" @click="close()">确 定</el-button>
            </div>

        </el-dialog>
        <el-dialog title="添加目录"
                   width="400px"
                   :visible.sync="new_dir.visible">
            <el-form  >
                <el-form-item label="活动名称" label-width="200">
                    <el-input v-model="new_dir.name" autocomplete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="cancel_new_dir()">取 消</el-button>
                <el-button type="primary" @click="dir_add()">确 定</el-button>
            </div>
        </el-dialog>
    </div>


</div>
<script>

    Vue.component('file-manager', {
        props: ['visible'],
        data: function () {
            const item = {
                date: '2016-05-02',
                name: '王小虎',
                address: '上海市普陀区金沙江路 1518 弄'
            };
            return {
                new_dir:{
                    visible:false,
                    name:'',
                },
                upload_image:'',
                dir:[],
                current_dir:'',
                file:[],
                url_pre:'/web/index.php?c=site&a=entry&do=web&m=sm_shop',
                tableData: Array(5).fill(item),
                fileManagerVisible: false,
                images:[]
            }
        },
        // computed:{
        //
        //     upload_url:function(){
        //
        //     }
        // },

        watch:{

            visible:function( a, b ){

                this.fileManagerVisible = a;

            }
        },
        methods: {
            init:function(){

                var t = this;
                var url = this.url_pre + '&r=fileManager.image_tree';


                axios.get( url ).then(function( res ){

                    t.dir = res.data;
                    // t.file = res.data.file;

                });
            },

            openMenu:function( key, menu ){

                // console.log( 'open' );
                // console.log( key );
                // console.log( menu );
                this.current_dir = key;
                this.getFileList( key );

            },

            selectMenu:function( key, menu ){
                // console.log( 'select:' );
                // console.log( key );
                // console.log( menu );
                this.current_dir = key;
                this.getFileList( );
            },

            getFileList:function( ){

                var t = this;
                var url = this.url_pre + '&r=fileManager.image_file';
                var dir = this.current_dir.replace(/\-/g,'/');
                url += '&dir=' + dir;

                axios.get( url ).then(function( res ){

                    t.images = res.data;

                });

            },
            image_check:function( image ){

                // console.log( image );
                // console.log( 'check' );
                var data = {
                    visible:0,
                    image:image
                };
                this.close( data );
            },

            image_remove:function( image ){

                var r = window.confirm('确认删除吗？');

                if( r ){
                    var t = this;
                    var url = this.url_pre + '&r=fileManager.file_remove';
                    var path = image.path;
                    url += '&path=' + path;
                    axios.get( url ).then(function( res ){
                        t.getFileList();
                    });
                }
            },
            uploadSuccess(res, file) {
                // console.log( res );

                if( res.status ){
                    alert( res.description );
                }else{
                    this.getFileList();
                }
            },
            beforeUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG) {
                    this.$message.error('上传头像图片只能是 JPG 或 PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                if( !this.current_dir ){
                    this.$message.error('上传目录不合法');
                    return false;
                }
                return (isJPG || isPNG) && isLt2M;
            },

            add_new_dir:function(){

                this.new_dir.visible = true;
                this.new_dir.name = '';
                // console.log( this.new_dir );
            },

            cancel_new_dir:function(){

                this.new_dir.visible = false;
            },

            dir_add:function(){

                var t = this;
                var url = this.url_pre + '&r=fileManager.dir_add';
                this.new_dir.name = this.new_dir.name.replace(/^(\s+)|(\s+)$/g,'');
                if( !this.new_dir.name ){
                    alert( '目录名称不能为空');
                    return;
                }
                var data = {
                    name:this.new_dir.name
                };

                axios.post( url, data ).then(function( res ){
                    if( res.data.status ){
                        alert(res.data.description);
                    }else{
                        t.new_dir.visible = false;
                        t.init();
                    }

                });
            },

            dir_delete:function( path ){

                var t = this;
                var url = this.url_pre + '&r=fileManager.dir_remove';

                var data = {
                    path:path
                    // path:'t9'
                };

                axios.post( url, data ).then(function( res ){
                    if( res.data.status ){
                        alert(res.data.description);
                    }else{
                        t.init();
                    }

                });

            },
            close:function ( close_data ) {

                this.fileManagerVisible = false;
                var data = {
                    visible:0
                };
                this.$emit('express', close_data?close_data:data );
            }
        },
        created: function () {

            this.init( );

        },
        template: document.getElementById('file-manager').innerHTML
    });
</script>