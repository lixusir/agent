<?php
/**
 * 团队分红
 */

class Team_rate_EweiShopV2Page extends MobileLoginPage{

    public function main(){

        global $_W;

        $change_type = '100,200';

        header('Location:'.mobileUrl('member/log_credit',['type'=>$change_type]));
        die;
    }
}