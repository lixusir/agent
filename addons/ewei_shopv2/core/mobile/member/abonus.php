<?php
/**
 * 区域分红
 */

class Abonus_EweiShopV2Page extends MobileLoginPage{

    public function main(){

        global $_W;

        $change_type = '401,402,403';

        $member = pdo_get('ewei_shop_member',array('openid'=>$_W['openid']),array('id','aagentlevel'));

        if(empty($member['aagentlevel'])){

            header('Location:'.mobileUrl('abonus/register'));die;
        }

        header('Location:'.mobileUrl('member/log_credit',['type'=>$change_type]));
        die;

    }
}