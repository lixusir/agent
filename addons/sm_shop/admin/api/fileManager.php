<?php
namespace sm_shop\admin\api;
use sm_shop\controller;
use sm_shop\model\tool\imageModel;
class fileManager extends controller{

    private $pathname = IA_ROOT . '/attachment';
    private $pattern = '*';
    private $fileFlag = '';

    public function dir(){

        $fileArray = array();

        $pathname = rtrim($this->pathname,'/') . '/';
        $list   =   glob($pathname.$this->pattern);
        foreach ($list  as $i => $file) {
            switch ($this->fileFlag) {
                case 0:
                    if (is_dir($file)) {
                        $fileArray['dir'][]=basename($file);
//                        $fileArray['dir'][]= $file;
                    }
                    if (is_file($file)) {
                        $fileArray['file'][]=basename($file);
                    }
                    break;
                case 1:
                    if (is_dir($file)) {
                        $fileArray[]=basename($file);
                    }
                    break;

                case 2:
                    if (is_file($file)) {
                        $fileArray[]=basename($file);
                    }
                    break;

                default:
                    break;
            }
        }

        if(empty($fileArray)) $fileArray = NULL;
        echo json_encode( $fileArray );

    }

    public function child_dir( $path ){
        $pathname   = rtrim($path,'/') . '/';
        $list       = glob($pathname.$this->pattern);
        $fileArray = [];

        foreach ($list  as $i => $file) {
            if (is_dir($file)) {
                $basename = basename($file);

                $fileArray[$basename]['name']= $basename;
//                $fileArray[$basename]['path']= $file;
                $fileArray[$basename]['child']= $this->child_dir( $file );


            }
        }
        return !empty($fileArray)?$fileArray:'';
    }
    public function image_tree(){

        global $_W;
        $fileArray = array();

        $pathname = rtrim($this->pathname,'/') . '/';
        $pathname .= 'images/' . $_W['uniacid'] . '/';
        $list   =   glob($pathname.$this->pattern);

        foreach ($list  as $i => $file) {
            if (is_dir($file)) {
                $basename = basename($file);
//                $fileArray[]= $file;
                $fileArray[$basename]['name']= $basename;
//                $fileArray[$basename]['path']= $file;
                $fileArray[$basename]['child']= $this->child_dir( $file );

            }
        }

        if(empty($fileArray)) $fileArray = NULL;
        echo json_encode( $fileArray );
    }

    public function dir_add(){

        global $_GPC, $_W;

        $res = [
            'status' => 0
        ];
        if( $_GPC['__input']){
            $post = $_GPC['__input'];
        }else{
            $post = $_POST;
        }
        $name = $post['name'];


        $dir = IA_ROOT . '/attachment/images/' . $_W['uniacid'] .'/' . $name;

        $res['dir'] = $dir;


        if( file_exists( $dir) && is_dir($dir) ){

            $res['status'] = 1;
            $res['description'] = '目录已经存在';

        }else{

            $dir = IA_ROOT . '/attachment/images/' . $_W['uniacid'] . '/' . $name;
            $res['ret'] = mkdir( $dir );
        }
        echo json_encode( $res );
    }

    public function dir_remove(){
        global $_GPC,$_W;

        $res = [
            'status' => 0
        ];
        if( $_GPC['__input']){
            $post = $_GPC['__input'];
        }else{
            $post = $_POST;
        }
        $path = $post['path'];
        $path = rtrim($path,'/');
        $dir = IA_ROOT . '/attachment/images/'. $_W['uniacid'] .'/' . $path;

        $res['dir'] = $dir;


        if( file_exists( $dir) && is_dir($dir) ){

            $child = glob( $dir . '/*');
            if( empty( $child ) ){
                $res['ret'] = rmdir( $dir );
            }else{
                $res['status'] = 1;
                $res['description'] = '目录非空，无法删除';
            }
        }else{
            $res['status'] = 1;
            $res['description'] = '目录不存在';
        }
        echo json_encode( $res );
    }

    public function image_file(){

        global $_GPC, $_W;

        $dir = $_GPC['dir'];

        $fileArray = array();

        $path = 'images/' . $_W['uniacid'] .'/' . $dir . '/';
        $pathname = rtrim($this->pathname,'/') . '/';
        $pathname .=  $path;
        $list   =   glob($pathname.$this->pattern);
//        echo $pathname . $this->pattern;
        foreach ($list  as $i => $file) {
            if (is_file($file)) {
                $fileArray[] = [
                    'name'  =>  basename($file),
                    'path'  =>  explode('attachment/', $file)[1],
                    'url'   =>  imageModel::resize2( $path . basename($file) ),
                ];
            }
        }

        if(empty($fileArray)) $fileArray = NULL;
        echo json_encode( $fileArray );

    }

    public function file_add(){

        global $_W;
        $res = [
            'status'=>0
        ];

        if( empty( $_GET['path'] ) ){
            $res['status'] = 1;
            $res['description'] = '上传不合法';
            echo json_encode( $res );
            die();
        }

        if( empty( $_FILES['file'] ) ){
            $res['status'] = 1;
            $res['description'] = '上传文件不能为空';
            echo json_encode( $res );
            die();
        }

        $path = $_GET['path'];
        $path = str_replace('-','/', $path);
        $path = rtrim($path,'/') . '/';
        $destination = IA_ROOT . '/attachment/images/'. $_W['uniacid'] .'/' . $path;
        $pathinfo = pathinfo($_FILES["file"]["name"]);
        $image = date('Ymdhis') . rand(6) . '.' . $pathinfo['extension'];
        $destination .= $image;
        $ret = move_uploaded_file($_FILES["file"]["tmp_name"], $destination );
        if( !$ret ){
            $res['status'] = 1;
            $res['ret'] = $ret;
            $res['description'] = '上传失败';
        }

        echo json_encode( $res );

    }

    public function file_remove(){

        global $_GPC;
        $path_file = IA_ROOT . '/attachment/' .$_GPC['path'];
//        echo json_encode( $path_file );
//        echo json_encode( file_exists($path_file) );
//        echo json_encode( is_file($path_file) );
//        die();

        $res = [
            'status'=>1
        ];
        if( file_exists( $path_file )
            && is_file( $path_file ) ){
            if( unlink( $path_file )){
                $res['status'] = 0;
            }
//            $handle = opendir($path);
//            while ( false !==$file = readdir($handle) ){
//                if ( $file !='.' && $file != '..' ){
//                    $file_fullpath = $path . "/" . $file;
//
//                    if ( !is_dir( $file_fullpath ) ){
//                        unlink( $file_fullpath );
//                    }else{
//                        rmdir( $file_fullpath );
//                    }
//                }
//            }
        }
        echo json_encode( $res );

    }

}