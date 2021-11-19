<?php
namespace sm_shop\admin\api\cron;
use sm_shop\controller;
use sm_shop\admin\model\goodsModel;

class goods extends controller{

    // 随机更改商品销量数值，计划任务每日一执行
    public function addSaleNumber(){

        $list = goodsModel::get_list( );

        $json = [];
        foreach( $list as $goods ){

            $saleNumber = intval( $goods['sale'] ) +  mt_rand(1,5);
            $json[$goods['id']] = goodsModel::changeSaleNumber( $goods['id'], $saleNumber );

        }

        echo json_encode( $json );

    }

}