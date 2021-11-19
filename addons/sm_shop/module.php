<?php
class sm_shopModule extends WeModule{

    public $uniacid;
    public $weid;
    public $modulename;
    public $module;

    public function welcomeDisplay(){

        echo 'sm_shop welcomeDisplay methods ';

        $url = '/web/index.php?c=site&a=entry&m=sm_shop&do=web';

        Header("Location:" . $url);
    }
}