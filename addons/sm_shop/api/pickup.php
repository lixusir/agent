<?php
namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\pickupModel;
class pickup extends controller{


    public function get_list(){


        $filter = [];
        $list = pickupModel::get_list( $filter );

        echo json_encode( $list );

    }

}