<?php
namespace sm_shop\admin\api;
use sm_shop\controller;

class tool extends controller{

    public function field_image(){

        global $_GPC;

        $name = $_GPC['name'];
        if( empty( $name ) ){
            echo '';
            die();
        }

        $value = !empty( $_GPC['value'] ) ? $_GPC['value'] : '';
        $default = !empty( $_GPC['default'] ) ? $_GPC['default'] : '';
        $options = [];
        echo tpl_form_field_image( $name, $value, $default, $options );

    }

    public function field_editor(){

        global $_GPC;
        $id = $_GPC['name'];
        if( empty( $id ) ){
            echo '';
            die();
        }

        $value = !empty( $_GPC['value'] ) ? $_GPC['value'] : '';
        $options = [];

        echo tpl_ueditor( $id, $value, $options );
    }



}

