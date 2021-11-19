<?php

namespace sm_shop\model;
use sm_shop\model;
class categoryModel extends model{

    public static function tree( ){

        global $_W;
        $sql = "SELECT 
                  c.*,
                  GROUP_CONCAT(cp.path_id 
                    ORDER BY cp.`level` ASC) AS path,
                  MAX(cp.`level`) AS cat_level 
                FROM
                  sh_category_path cp 
                  LEFT JOIN sh_category c 
                    ON cp.`category_id` = c.id 
                WHERE c.status = 1 and cp.uniacid=" . $_W['uniacid'] . "
                GROUP BY cp.category_id ";


//        $query = self::$db->query( $sql );
//
//        $cats = $query->rows;

        $cats = pdo_fetchall( $sql );
        $tree = [];

        foreach( $cats as $cat ){


            $path_arr = explode(',',$cat['path'] );

            self::setChild( $tree, $path_arr, $cat );

//            print_r( $tree );
//            if( empty( $cat['parent_id'] ) ){
//
//                $tree[ $cat['id'] ] = $cat;
//
//            }else{
//
//
//            }


        }

        return $tree;

    }

    public static function setChild( &$jiedian, $path, $cat ){

        $cat_id = array_shift( $path );

        if( !empty( $jiedian[ $cat_id ] ) ){

            if( empty( $jiedian[ $cat_id ]['child'] ) ){

                $jiedian[ $cat_id ]['child'] = [];

            }

            self::setChild( $jiedian[ $cat_id ]['child'], $path, $cat );

        }else if( $cat_id == $cat['id'] ){

            $jiedian[ $cat_id ] = $cat;

        }

//        return;
//        print_r( $jedian );

    }

    public static function single( $category_id ){

        global $_W;
        $sql = "select * from sh_category where id='" . $category_id
            . "' and uniacid=" . $_W['uniacid'];
        return pdo_fetch( $sql );
    }
}

