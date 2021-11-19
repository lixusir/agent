<?php

namespace sm_shop\admin\model;
use sm_shop\model;
class goodsModel extends model{

    public static function get_list( $filter = [] ){

        global $_W;
        $where = ' g.uniacid= ' . $_W['uniacid'];
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
        if( !empty($filter['brand_name']) ){
            $where .= " and b.`name` like '%" . $filter['brand_name'] . "%'";
        }



        $sql = 'SELECT g.*,b.name as brand_name, group_concat(c.name) as category_name FROM sh_goods g '  ;
        $sql .= ' left join sh_goods_to_category g2c on g2c.goods_id=g.id ';
        $sql .= ' left join sh_category c on g2c.category_id= c.id';
        $sql .= ' left join sh_brand b on g.brand_id= b.id';


        if( !empty( $where ) ){
            $sql .= ' where ' . $where;
        }

        $sql .= ' group by g.id';

//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );
    }

    public static function search( $filter = [] ){

        global $_W;
        $filter['name'] = !empty( $filter['name'] )? $filter['name'] : '';
        $sql = 'SELECT 
                  g.*
                FROM
                  sh_goods g 
                WHERE g.name like "%' . $filter['name'] .'%"'
                .' and g.uniacid = '.$_W['uniacid']
                .' and g.status>=0 '
                .' limit 0, 20';

//        $query = self::$db->query( $sql );
//
//        return $query->rows;

        return pdo_fetchall( $sql );
    }

    public static function info( $goods_id ){

        $sql = 'SELECT 
  g.*,
  gd.`description` 
FROM
  sh_goods g 
  LEFT JOIN sh_goods_description gd 
    ON g.`id` = gd.`goods_id` 
WHERE g.id = ' . $goods_id;
//        $query = self::$db->query( $sql );
//
//        return $query->row;
        return pdo_fetch( $sql );
    }

    public static function edit( $goods_id, $data ){

        global $_W;
        if( !empty( $goods_id ) ){
            $sql = "update sh_goods SET
                    `name`='"       . $data['name'] . "',
                    `brand_id`='"   . $data['brand_id'] . "',
                    price='"        . $data['price'] . "',
                    special='"      . $data['special'] . "',
                    `sale`='"       . $data['sale'] . "',
                    `sale_add`='"   . $data['sale_add'] . "',
                    model='"        . $data['model'] . "',
                    image='"        . $data['image'] . "',
                    `status`="      . $data['status'] ." where id=" . $goods_id ;
        }else{
            $sql = "INSERT INTO sh_goods SET
                    `name`='"       . $data['name'] . "',
                    `uniacid`='"    . $_W['uniacid'] . "',
                    `brand_id`='"   . $data['brand_id'] . "',
                    price='"        . $data['price'] . "',
                    special='"      . $data['special'] . "',
                    `sale`='"       . $data['sale'] . "',
                    `sale_add`='"   . $data['sale_add'] . "',
                    model='"        . $data['model'] . "',
                    image='"        . $data['image'] . "',
                    `status`="      . $data['status'] ;
        }

        $execute = pdo_query( $sql );
        if( empty( $goods_id ) ){
            $goods_id = pdo_insertid();
        }

        if( !empty( $goods_id ) ){

            $data_description = [
                'description'=>$data['description']
            ];
            self::edit_goods_description($goods_id, $data_description);

            $data_images = $data['goods_images'];
            self::edit_goods_img( $goods_id, $data_images );

            $categories = $data['category'];
            self::edit_goods_category( $goods_id, $categories );

            // todo 存储拼单模块
            self::edit_goods_pindan( $goods_id, $data['pindan'] );

        }

        return $execute;

    }

    public static function get_goods_description( $goods_id ){

        $sql = "select * from sh_goods_description where goods_id=" . $goods_id;
        $query = self::$db->query( $sql );

//        return $query->row;

        return pdo_fetch( $sql );

    }

    public static function get_goods_pindan( $goods_id ){

        $sql = 'select * from sh_goods_pindan where goods_id=' . $goods_id;
        return pdo_fetch( $sql );

    }

    public static function edit_goods_pindan( $goods_id, $data ){

        global $_W;
        //todo 删除
        $delete_sql = "DELETE FROM sh_goods_pindan WHERE goods_id=" . $goods_id;
        pdo_query( $delete_sql );

        //todo 添加
        $sql = "INSERT INTO sh_goods_pindan SET 
                  goods_id='" . $goods_id . "',
                  uniacid='" . $_W['uniacid'] . "',
                  status='" . $data['status'] . "',
                  price='" . $data['price'] . "',
                  validate_time='" . $data['validate_time'] . "',
                  `number`='" . $data['number'] . "'";

        return pdo_query( $sql );


    }

    public static function edit_goods_description( $goods_id, $data ){

        //todo 删除
        $delete_sql = "DELETE FROM sh_goods_description WHERE goods_id=" . $goods_id;
//        $query = self::$db->query( $delete_sql );
        pdo_query( $delete_sql );

        //todo 添加

        $sql = "INSERT INTO sh_goods_description SET 
                  goods_id='" . $goods_id . "',
                  description='"  . $data['description'] . "'" ;

//        $query = self::$db->query( $sql );

        return pdo_query( $sql );
    }

    public static function get_goods_images( $goods_id ){

        $sql = "select * from sh_goods_img where goods_id=" . $goods_id;
//        $query = self::$db->query( $sql );

//        return $query->rows;
        return pdo_fetchall( $sql );
    }

    public static function edit_goods_img( $goods_id, $data_arr ){

        //todo 删除
        $delete_sql = "DELETE FROM sh_goods_img WHERE goods_id=" . $goods_id;
//        $query = self::$db->query( $delete_sql );
        pdo_query( $delete_sql );

        $values = '';
        if( !empty($data_arr) ){
            foreach( $data_arr as $data ){
                $values .= "(" . $goods_id . ",'" . $data['url'] . "'," . $data['sort_order'] . "),";
            }
            $values = trim( $values,',' );

            //todo 添加
            $sql = "INSERT INTO sh_goods_img (goods_id, url, sort_order) VALUES " . $values ;

//            $query = self::$db->query( $sql );
            pdo_query( $sql );
        }

    }

    public static function edit_goods_category( $goods_id, $categories ){

        $delete_sql = "delete from sh_goods_to_category where goods_id=" . $goods_id;
//        $query = self::$db->query( $delete_sql );
        pdo_query( $delete_sql );
        $values = '';
        if( !empty( $categories ) ){

            foreach( $categories as $category_id ){
                $values .= "(" . $goods_id . "," . $category_id . "),";
            }
            $values = trim( $values,',' );

            $insert_sql = "INSERT INTO sh_goods_to_category (goods_id, category_id) VALUES " . $values ;

//            $query = self::$db->query( $insert_sql );

            pdo_query( $insert_sql );
        }


    }

    public static function get_goods_category( $goods_id ){


        $sql = 'select * from sh_goods_to_category where goods_id=' . $goods_id;

//        $query = self::$db->query( $sql );
//
//        return $query->rows;
        return pdo_fetchall( $sql );

    }

    public static function save_specification( $goods_id, $spec_arr ){

        $sql = 'INSERT INTO sh_goods_attr(`goods_id`,`name`) VALUES ';

        $values = '';

        foreach( $spec_arr as $spec ){

            $values .= '(' . $goods_id . ',"' . $spec['name'] . '"),';

        }
        $values = trim( $values, ',');

        $sql .= $values;
        pdo_query( $sql );

    }

    public static function delete_spec( $goods_id  ){

        $sql_delete_spec = 'delete from sh_goods_attr where goods_id=' . $goods_id;
        $sql_delete_spec_value = 'delete from sh_goods_attr_value where goods_id=' . $goods_id;

        pdo_query( $sql_delete_spec );
        pdo_query( $sql_delete_spec_value );

    }

    public static function save_spec( $goods_id, $spec ){


        $sql = 'INSERT INTO sh_goods_attr(`goods_id`,`name`) VALUES ';


        $sql .= '(' . $goods_id . ',"' . $spec['name'] . '")';


        pdo_query( $sql );

        $id = pdo_insertid();

        $spec_value_sql = 'INSERT INTO sh_goods_attr_value(`goods_id`,`attr_id`,`value`) values ';

        $spec_values = '';
        foreach( $spec['values'] as  $spec_val ){

            $spec_values .= '(' . $goods_id .',' . $id . ',"' . $spec_val['name'] . '"),';
        }
        $spec_values = trim( $spec_values, ',' );
        $spec_value_sql .= $spec_values;

        pdo_query( $spec_value_sql );

    }

    public static function delete_spec_obj( $goods_id ){

        $sql = "delete from sh_goods_attr_object where goods_id=" . $goods_id;
        pdo_query( $sql );

    }

    public static function save_spec_obj( $goods_id, $obj_arr ){
        self::delete_spec_obj( $goods_id );

        $sql = 'INSERT INTO sh_goods_attr_object(`goods_id`, `attr`,`price`,`price_pindan`,`stock`) VALUES';

        $values = '';
        foreach( $obj_arr as $item ){
            if( $item['stock'] === '' ){
                $item['stock'] = -1;
            }
            $spec_arr = implode( ',', $item['spec_arr']);
            sort( $spec_arr );
            $values .= '(' . $goods_id . ',"' . $spec_arr . '",'. $item['price'] . ','. $item['price_pindan'] . ','.  $item['stock'] . '),';
        }
        $values = trim( $values, ',');
        $sql .= $values;

        return pdo_query( $sql );


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

    public static function remove( $ids ){

        $ids = is_array( $ids ) ? implode(',', $ids ) : $ids;
        $sql = 'update sh_goods set `status`= -1 where id in(' . $ids . ') ';

        return pdo_query( $sql );

    }


    public static function changeSaleNumber( $goods_id, $saleNumber ){

        $sql = 'update sh_goods set `sale`= ' . $saleNumber
            . ' where id= ' . $goods_id ;

        return pdo_query( $sql );

    }

}