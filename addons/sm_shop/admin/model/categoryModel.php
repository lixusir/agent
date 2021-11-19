<?php
namespace sm_shop\admin\model;
use sm_shop\model;
class categoryModel extends model{

    public static function get_path_list( $filter = [] ){

        global $_W;
        $where_arr = [];
        $where_arr[] = ' c.uniacid=' . $_W['uniacid'];
        if( !empty($filter['name']) ){
            $where_arr[] = ' c.name like "%' . $filter['name'] . '%"';
        }

        if( isset($filter['status']) ){
            $where_arr[] = ' c.status = "' . $filter['status'] . '"';
        }

        if( !empty($filter['category_ids']) ){
            $category_ids = join( ',', $filter['category_ids'] );
            $where_arr[] = ' c.id in (' . $category_ids . ')';
        }

        $where_str = '';
        if( !empty( $where_arr ) ){
            $where_str = ' where ' . join(' and ', $where_arr );
        }

        $sql = 'SELECT 
  c.*,
  GROUP_CONCAT(
    cc.`name` 
    ORDER BY cp.`level` ASC SEPARATOR \'>\'
  ) AS cat_name 
FROM
  sh_category c 
  LEFT JOIN sh_category_path cp 
    ON c.`id` = cp.`category_id` 
  LEFT JOIN sh_category cc 
    ON cp.`path_id` = cc.`id` 
    ' . $where_str . '
GROUP BY c.`id` 
ORDER BY c.sort_order, c.id ASC,
  cp.`level` DESC';
//echo $sql;
//        $query = self::$db->query( $sql );
//
//        return $query->rows;

        return pdo_fetchall( $sql );

    }

    public static function single( $category_id ){

        $sql = "select * from sh_category where id='" . $category_id . "'";
        return pdo_fetch( $sql );
    }

    public static function edit( $category_id, $data ){

        $sql = "UPDATE  sh_category  SET
                  `name` = '" . $data['name'] . "',
                  sort_order = " . $data['sort_order'] . ",
                  `status` = " . $data['status'] . ",
                  parent_id = " . $data['parent_id'] . ", 
                  image = '" . $data['image'] . "' 
                WHERE id = '" . $category_id . "'";

//        $query = self::$db->query( $sql );
//
//
//        return $query;

        return pdo_query( $sql );

    }

    public static function create( $data ){

        global $_W;
        $sql = "insert into  sh_category  SET
                  `uniacid` = '" . $_W['uniacid'] . "',
                  `name` = '" . $data['name'] . "',
                  sort_order = " . $data['sort_order'] . ",
                  `status` = " . $data['status'] . ",
                  `image` = '" . $data['image'] . "',
                  parent_id = " . $data['parent_id'] ;

//        $query = self::$db->query( $sql );
//
//        return self::$db->getLastId();

        pdo_query( $sql );
        return pdo_insertid();
    }

    public static function set_category_path( $category_id, $parent_id ){

        global $_W;
        $path_list = self::get_category_path( $parent_id );

        $level = 0;
        foreach( $path_list as $path ){
//            $level = $path['level'];
            $sql = 'INSERT INTO sh_category_path SET category_id = "' . $category_id
                . '",path_id = "' . $path['path_id']
                . '",uniacid = "' . $_W['uniacid']
                . '",`level` = "' . $path['level']
                . '" ';
//            self::$db->query( $sql );
            pdo_query( $sql );
            $level ++;
        }

        $level ++;
        $sql = 'INSERT INTO sh_category_path SET category_id = "' . $category_id
            . '",path_id = "' . $category_id
            . '",uniacid = "' . $_W['uniacid']
            . '",`level` = "' . $level
            . '" ';
//        self::$db->query( $sql );

        pdo_query( $sql );
    }

    public static function get_category_path( $category_id ){

        $sql = 'SELECT * FROM sh_category_path cp
                inner join sh_category c on cp.path_id = c.id
                WHERE cp.category_id="' . $category_id . '" order by `level` ';

//        echo $sql;
        return pdo_fetchall( $sql );
//        $query = self::$db->query( $sql );
//
//        return $query->rows;

    }

    public static function remove_category_path( $category_id ){

        $sql = 'delete from sh_category_path where category_id="' . $category_id .'"';

//        $query = self::$db->query( $sql );
//
//        return $query;

        return pdo_fetch( $sql );

    }

    public static function hasChildCategory( $parent_id ){

        $sql = 'select * from sh_category where parent_id= ' . $parent_id;
//        $query = self::$db->query( $sql );
//
//
//
//        return $query->row;
        return pdo_fetch( $sql );
    }

//    public static function batch_remove( $cat_ids ){
//
//        $ids = implode(',', $cat_ids );
//
//        $sql = 'delete from sh_category where parent_id IN (' . $ids .  ')';
//        $query = self::$db->query( $sql );
//        self::remove_category_path();
//
//    }

    public static function remove( $cat_id ){


        $sql = 'delete from sh_category where id =' . $cat_id;
//        $query = self::$db->query( $sql );
//
//
//        return $query;
        return pdo_query( $sql );

    }

}