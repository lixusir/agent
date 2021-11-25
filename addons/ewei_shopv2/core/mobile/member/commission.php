<?php
/**
 * 分销佣金分红
 */

class Commission_EweiShopV2Page extends MobileLoginPage{

    public function main(){

        global $_W;

        $change_type = "600,700";

        header('Location:'.mobileUrl('member/log_credit',['type'=>$change_type]));
        die;
    }
}