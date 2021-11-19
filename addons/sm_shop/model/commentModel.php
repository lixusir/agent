<?php
namespace sm_shop\model;
use sm_shop\model;
class commentModel extends model{

    public static function get_list( $goods_id ){

        $sql = "select gc.*, c.headUrl as customer_avatar from sh_goods_comment gc "
            . " left join sh_customer c on gc.customer_id=c.id "
            . " where gc.goods_id='" . $goods_id
            . "' and gc.status=1 "
            . " order by gc.date desc, gc.created_at desc "
            ." limit 0,20;"
        ;

        return pdo_fetchall( $sql );
    }

}