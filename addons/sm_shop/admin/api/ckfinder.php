<?php
namespace sm_shop\admin\api;
use sm_shop\controller;

use CKSource\CKFinder\CKFinder;

class ckfinder extends controller{


    public function index(){
        if( !empty( $_SERVER['HTTP_ORIGIN']) ){
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        }

        require_once __DIR__ . '/vendor/autoload.php';



        $ckfinder = new CKFinder(__DIR__ . '/../../../config.php');

        $ckfinder->run();


    }

}