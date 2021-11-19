<?php
namespace sm_shop\admin\api;
use sm_shop\controller;

use sm_shop\admin\model\settingModel;

class setting extends controller{

    public function page_list(){

        $this->template( 'system/setting/page_list' );

    }

    public function index(){

        $settings = settingModel::get_all();


        echo json_encode( $settings );

    }


    public function edit(){

        $res = [
            'status'=>0
        ];

        $data = $_POST['settings'];

        $res['num_rows'] = settingModel::set_all( $data );

        echo json_encode( $res );

    }


}

