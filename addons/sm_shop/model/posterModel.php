<?php
namespace sm_shop\model;
use sm_shop\model;
use sm_shop\model\tool\imageModel;
class posterModel extends model{


    public function makePoster( $poster_id ){

        // 获取背景图
        // 获取昵称
        // 获取二维码
        // 获取图片

        // 拼接零碎部件
        global $_W;
        $poster_sql = "select * from sh_poster where id= " . $poster_id;
        $poster = pdo_fetch( $poster_sql );
        $poster_img = $poster['bg_img'];
//        $bg_img = $poster['bg_img'];
        $design = json_decode( $poster['design'], true );
        foreach( $design as $ds ){

            if( $ds['type'] == 'img' ){

                $poster_img = imageModel::merge( $poster_img, $ds['src'], $ds['pos_left'], $ds['pos_top'] );

            }
            if( $ds['type'] == 'nickname' ){

                if( empty($fans) ){
                    $fans = mc_fansinfo( $_W['member']['uid'] );
                }
                $nickname = !empty($fans['nickname'])?$fans['nickname']:'';
                $color = str_replace( '#', '', $ds['color']);
                $size = str_replace( 'px', '', $ds['size']);
                $poster_img = imageModel::text( $poster_img, $nickname, $ds['pos_left'], $ds['pos_top'], $size, $color );

            }
            if( $ds['type'] == 'head' ){

                if( empty($fans) ){
                    $fans = mc_fansinfo( $_W['member']['uid'] );
                }
                if( !empty($fans['avatar']) ){
                    $avatar = imageModel::download( $fans['avatar'], 'images/' );
                    $poster_img = imageModel::merge( $poster_img, $avatar, $ds['pos_left'], $ds['pos_top'] );
                }
            }

            if( $ds['type'] == 'qr' ){

                global $_W;
                $qrcode = $_W['uniaccount']['qrcode'];

                if( !empty($qrcode) ){
                    $qrcode_img = explode( '?', $qrcode )[0];
                    $qrcode_img = explode( 'attachment/', $qrcode_img )[1];
                    $poster_img = imageModel::merge( $poster_img, $qrcode_img, $ds['pos_left'], $ds['pos_top'] );
                }
            }

        }

        return $poster_img;
    }
}