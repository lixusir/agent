<?php
namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\categoryModel;
class category extends controller{


    public function tree(){


        $tree = categoryModel::tree();

        echo json_encode( $tree );
    }


}
