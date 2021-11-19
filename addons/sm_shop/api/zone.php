<?php
namespace sm_shop\api;
use sm_shop\controller;
use sm_shop\model\zoneModel;
use sm_shop\model\cityModel;

class zone extends controller{

    /**
     * 省份
     */
    public function index(){

        $zone = zoneModel::get_list();

        echo json_encode( $zone );

    }

    /**
     * 城市
     */
    public function city(){

        $province_id = $_GET['province_id'];
        $city = cityModel::get_city_list( $province_id );

        echo json_encode( $city );

    }

    /**
     *  区/县
     */
    public function district(){
        $city_id = $_GET['city_id'];
        $city = cityModel::get_district_list( $city_id );

        echo json_encode( $city );
    }


}