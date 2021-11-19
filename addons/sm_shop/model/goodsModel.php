<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\7\17 0017
 * Time: 11:29
 */
//namespace model;
//use model;

namespace sm_shop\model;
use sm_shop\model;

class goodsModel extends model{


    public static function search( $params ){

        global $_W;
        $sql = "select g.*,cp.* from sh_goods g";

        $where = ' g.uniacid=' . $_W['uniacid'] . ' and g.status=1 ';
        $sql .= " left join sh_goods_to_category g2c on g.id=g2c.goods_id  
            left join sh_category_path cp on g2c.category_id = cp.category_id";
        if( !empty($params['category_id'] ) ) {
            $where .= " and cp.path_id=" . $params['category_id'];
        }

        if( !empty($params['brand_id'] ) ) {
            $where .= " and g.brand_id=" . $params['brand_id'];
        }

        if( !empty( $params['key'] ) ){
//            $where .= !empty($where)? " and " : '';
            $where .= " and g.`name` like '%" . $params['key'] . "%' ";
        }

        if( !empty( $where ) ){

            $sql.= " where " . $where;
        }

        $sql .= " GROUP BY g.id";

//        $result = self::$db->query( $sql );
//
//        return $result->rows;

        return pdo_fetchall( $sql );

    }

    public static function detail( $goods_id ){

        $sql = "select * from sh_goods g 
                  left join sh_goods_description gd on g.id=gd.goods_id
                  where g.status=1 and g.id=" . $goods_id;
//        $result = self::$db->query( $sql );
//        return $result->row;

        $result = pdo_fetch( $sql );

        return $result;

    }

    public static function image_list( $goods_id ){

        $sql = "select * from sh_goods_img 
                  where goods_id=" . $goods_id;

//        $result = self::$db->query( $sql );
//        return $result->rows;
        $result = pdo_fetchall( $sql );

        return $result;
    }

    public static function getImgUrl( $goods_id ){

//        $sql = 'select url from sh_goods where is_default=1 and goods_id= ' . $goods_id . ' limit 0,1';
        $sql = 'select image from sh_goods where id= ' . $goods_id;
//        $result = self::$db->query( $sql );
//        return !empty( $result->row ) ? $result->row['url']:'';

        $result = pdo_fetch( $sql );

        return !empty( $result ) ? $result['image'] : '';
    }

    /**
     * @param $data
     * @return string
     * 测试， 之后删掉
     */
    public static function add( $name, $price ){

        global $_W;
        $sql = 'insert into sh_goods set price=' . $price
            .', uniacid="'. $_W['uniacid'] . '"'
            .', name="'.$name . '"';
//        $result = self::$db->query( $sql );
//        return $result;

        $result = pdo_query( $sql );
        return $result;
    }

    /**
     * @param $data
     * @return string
     * 测试， 之后删掉
     */
    public static function delete( $product_id ){

        $sql = 'delete from sh_goods where id=' . $product_id;
//        $result = self::$db->query( $sql );
//        return $result;

        $result = pdo_query( $sql );
        return $result;
    }

    public static function get_goods_specification( $goods_id ){

        $sql = 'SELECT 
                  ga.`name`,
                  gav.`value` 
                FROM
                  sh_goods_attr ga 
                  LEFT JOIN sh_goods_attr_value gav 
                    ON ga.`id` = gav.`attr_id` 
                WHERE ga.`goods_id` = ' . $goods_id;

        return pdo_fetchall( $sql );

    }

    public static function get_goods_spec_obj( $goods_id ){

        $sql = 'SELECT * FROM sh_goods_attr_object WHERE goods_id = ' . $goods_id;

        return pdo_fetchall( $sql );
    }

    public static function get_goods_spec_obj_by_id( $option_id ){

        $sql = 'SELECT * FROM sh_goods_attr_object WHERE id = ' . $option_id;

        return pdo_fetch( $sql );

    }

    public static function get_pindan_info( $goods_id ){

        $sql = ' select * from sh_goods_pindan where goods_id=' . $goods_id;

        return pdo_fetch( $sql );
    }

    // 从后台移植过来的方法， 计划任务用
    public static function get_list( $filter = [] ){

        global $_W;
        $where = ' uniaicd='. $_W['uniacid'];
        if( !empty($filter['name']) ){
            $where .= " and g.`name` like '%" . $filter['name'] . "%'";
        }

        if( !empty($filter['min-price']) ){
            $where .= " and g.`price` >= " . $filter['min-price'] . "";
        }

        if( !empty($filter['max-price']) ){
            $where .= " and g.`price` <= " . $filter['max-price'] . "";
        }

        if( isset($filter['status']) ){
            $where .= " and g.`status` = '" . $filter['status'] . "'";
        }else{
            $where .= " and g.`status` >= 0 " ;
        }

        if( !empty($filter['category_name']) ){
            $where .= " and c.`name` like '%" . $filter['category_name'] . "%'";
        }



        $sql = 'SELECT g.*, group_concat(c.name) as category_name FROM sh_goods g '  ;
        $sql .= ' left join sh_goods_to_category g2c on g2c.goods_id=g.id ';
        $sql .= ' left join sh_category c on g2c.category_id= c.id';


        if( !empty( $where ) ){
            $sql .= ' where ' . $where;
        }

        $sql .= ' group by g.id';

//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );
    }

    // 从后台移植过来的方法， 计划任务用
    public static function changeSaleNumber( $goods_id, $saleNumber ){

        $sql = 'update sh_goods set `sale`= ' . $saleNumber
            . ' where id= ' . $goods_id ;

        return pdo_query( $sql );

    }
}