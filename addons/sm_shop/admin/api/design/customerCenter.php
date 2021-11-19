<?php
namespace sm_shop\admin\api\design;
use sm_shop\controller;
use sm_shop\admin\model\settingModel;

class customerCenter extends controller{

    public function page_list(){

        $this->template( 'design/customerCenter' );

    }
    public function index(){

        $info = [
            'bg_img'=>''
        ];
        $list = settingModel::getByCode('customer_center');
        foreach( $list as $item ){

            if( $item['key'] == 'bg_img' ){
                $info['bg_img'] = $item['value'];
            }
        }

        echo json_encode( $info );

    }


    public function edit(){


        $bg_img = $_POST['bg_img'] ? $_POST['bg_img'] : '';
        $ret = 0;

        if( $bg_img ){
            $ret = settingModel::set( 'customer_center','bg_img', $bg_img );
        }

        $res = [
            'status'=>0,
            'ret'=>$ret
        ];

        echo json_encode( $res );

    }


}