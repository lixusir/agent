<?php

namespace sm_shop\admin\model;
use sm_shop\model;
class posterModel extends model
{

    public static function get_list( $filter ){

        global $_W;
        $sql = "select * from sh_poster ";

        $where = [];
        $where[] = " uniacid=" . $_W['uniacid'];
        if( !empty($filter['name']) ){
            $where[]= " name like '%" .  $filter['name'] . "%'";
        }
        if( !empty($filter['status']) ){
            $where[]= " status = '" .  $filter['status'] . "'";
        }

        if( !empty( $where )){

            implode( ' and ', $where );
            $sql .= ' where ' . implode( ' and ', $where );;
        }

        return pdo_fetchall( $sql );



    }

    public static function get_info( $poster_id ){

        $sql = "select * from sh_poster where id= " . $poster_id;

        return pdo_fetch( $sql );

    }

    public static function edit( $poster_id, $data ){

        $sql = " update sh_poster set 
                `name` = '"         . $data['name'] . "',
                `reply` = '"        . $data['reply'] . "',
                `bg_img` = '"       . $data['bg_img'] . "',
                `design` = '"       . $data['design'] . "',
                `date_start` = '"   . $data['date_start'] . "',
                `date_end` = '"     . $data['date_end'] . "',
                `status` = '"       . $data['status'] . "' 
                 where id= "        . $poster_id;

        // todo 添加回复关键字
        self::addPosterReplyText( $poster_id, $data['reply'] );
        return pdo_query( $sql );
    }

    public static function add( $data ){

        global $_W;
        $sql = "INSERT INTO sh_poster SET 
                `name` = '"         . $data['name'] . "',
                `uniacid` = '"      . $_W['uniacid'] . "',
                `bg_img` = '"       . $data['bg_img'] . "',
                `design` = '"       . $data['design'] . "',
                `reply` = '"        . $data['reply'] . "',
                `date_start` = '"   . $data['date_start'] . "',
                `date_end` = '"     . $data['date_end'] . "',
                `status` = "        . $data['status'];

        // todo 添加回复关键字

        pdo_query( $sql );
        $poster_id = pdo_insertid();
        self::addPosterReplyText( $poster_id, $data['reply'] );
        return $poster_id;
    }

    public static function remove( $poster_id ){

        $sql = " delete from sh_poster where id=" . $poster_id;

        return pdo_query( $sql );
    }

    // todo 添加回复关键字
    public static function addPosterReplyText( $poster_id, $reply_text ){

        global $_W;

        pdo_delete('rule', array('name' => 'poster_' . $poster_id, 'uniacid' => $_W['uniacid']));
        $rule = array(
            'uniacid' => $_W['uniacid'],
            'name' => 'poster_' . $poster_id,
            'module' => 'sm_shop',
            'containtype' => 'images',
            'status' => 1,
            'displayorder' => 0,
        );
        $result = pdo_insert('rule', $rule);
        $rid = pdo_insertid();

        pdo_delete('rule_keyword', array('rid' => $rid, 'uniacid' => $_W['uniacid']));
        $krow = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'module' => 'sm_shop',
            'status' => 1,
            'displayorder' => 0,
            'type'=>1,
            'content'=>$reply_text,
        );
        pdo_insert('rule_keyword', $krow);

    }


    public static function download_avatar(){
        $url = 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJj5XZnLw9WU2YIpPHYal0hicj0uFibFXsuW54eEaGQSVWQ9vficgaV9DlAaxLv2ibic5fbr1h09Ts17tw/132';
        return model\tool\imageModel::download( $url, 'avatar/' );
    }
}