<?php
namespace sm_shop\api\cron;
use sm_shop\controller;
use sm_shop\model\goodsModel;

class goods extends controller{

    // 随机更改商品销量数值，计划任务每日一执行
    public function addSaleNumber(){

        global $_GPC;
        $list = goodsModel::get_list( );

//        $min = $_GPC['min']?:1;
//        $min = intval( $min );
//        $max = $_GPC['max']?: $min + 4;
//        $max = intval( $max );
        $json = [];
        foreach( $list as $goods ){

            $sale_add = $goods['sale_add']?:0;
            if( $sale_add ){
                $saleNumber = intval( $goods['sale'] ) +  mt_rand( 0, $sale_add );
                $json[$goods['id']] = goodsModel::changeSaleNumber( $goods['id'], $saleNumber );
            }

        }

        echo json_encode( $json );

    }

}