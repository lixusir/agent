<?php

namespace sm_shop\admin\model;
use sm_shop\model;
class commentModel extends model
{

    public static function get_list( $filter = [] ){

        global $_W;
        $where = [];
        $where[] = " gc.uniacid=" . $_W['uniacid'];
        if( !empty($filter['author']) ){
            $where[] = "gc.`author` like '%" . $filter['author'] . "%'";
        }

        if( !empty($filter['goods_id']) ){
            $where[] = "gc.`goods_id` = '" . $filter['goods_id'] . "' ";
        }

        if( !empty($filter['goods_name']) ){
            $where[] = "g.`name` like '%" . $filter['goods_name'] . "%' ";
        }

        if( !empty($filter['date']) ){
            $where[] = "gc.`date` like '%" . $filter['date'] . "%' ";
        }

        if( isset($filter['status']) ){
            $where[] = "gc.`status` = '" . $filter['status'] . "' ";
        }else{
            $where[] = "gc.`status` >= 0 " ;
        }

        $sql = 'SELECT gc.*, g.name as goods_name, g.image as goods_image FROM sh_goods_comment gc '  ;
        $sql .= ' left join sh_goods g on gc.goods_id=g.id ';


        if( !empty( $where ) ){
            $sql .= ' where ' . implode(' and ', $where );
        }

        $sql .= ' order by gc.date desc';

        return pdo_fetchall( $sql );
    }

    public static function edit( $comment_id, $data ){

        global $_W;
        $date = Date('Y-m-d');
        $now = Date('Y-m-d H:i:s');
        if( !empty( $comment_id ) ){

            $sql = "update sh_goods_comment SET
                    `author`='"   . $data['author'] . "',
                    `avatar`='"   . $data['avatar'] . "',
                    customer_id='"   . $data['customer_id'] . "',
                    goods_id='"    . $data['goods_id'] . "',
                    content='"  . $data['content'] . "',
                    `date`='"   . $data['date'] . "',
                    score='"   . $data['score'] . "',
                    images='"    . $data['images'] . "',
                    `status`="  . $data['status'] .", 
                    `updated_at`='"  . $now ."' 
                    where id=" . $comment_id ;
        }else{
            $sql = "INSERT INTO sh_goods_comment SET
                    `author`='"   . $data['author'] . "',
                    `avatar`='"   . $data['avatar'] . "',
                    customer_id='"   . $data['customer_id'] . "',
                    goods_id='"    . $data['goods_id'] . "',
                    uniacid='"    . $_W['uniacid'] . "',
                    content='"  . $data['content'] . "',
                    `date`='"   . $data['date'] . "',
                    score='"   . $data['score'] . "',
                    images='"    . $data['images'] . "',
                    `updated_at`='"  . $now ."', 
                    `created_at`='"  . $now ."', 
                    `status`="  . $data['status'];
        }
        $execute = pdo_query( $sql );
        return $execute;

    }

    public function single( $comment_id ){
        $sql = 'select * from sh_goods_comment where id=' . $comment_id ;
        return pdo_fetch( $sql );
    }

    public static function remove( $ids ){

        $ids = is_array( $ids ) ? implode(',', $ids ) : $ids;
        $sql = 'update sh_goods_comment set `status`= -1 where id in(' . $ids . ') ';

        return pdo_query( $sql );

    }
}