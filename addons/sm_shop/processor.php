<?php
/**
 * 
 *
 * @author feenix
 */
defined('IN_IA') or exit('Access Denied');

require_once IA_ROOT . '/addons/sm_shop/lib/utf8.php';
require_once IA_ROOT . '/addons/sm_shop/model.php';
function loadClass( $class_name ){

    $split = explode('\\', $class_name );
    $class_path = IA_ROOT . '/addons/' .  str_replace( '\\','/', $class_name ) . '.php';
    if( file_exists( $class_path ) ){
        require_once( $class_path );
    }
}
spl_autoload_register('loadClass' );
use sm_shop\model\posterModel;
class sm_shopModuleProcessor extends WeModuleProcessor {
	public function respond() {
	    global $_W;
		$content = $this->message['content'];

        $result = '';
        //todo 查找到对应海报
        // todo 生成海报
        // todo 上传海报
        // todo 返回海报对应的mediaid

        load()->model('reply');
        $params = [];
        $condition = " uniacid = :uniacid AND module = 'sm_shop' AND content = :content ";
        $params['uniacid'] = $_W['uniacid'];
        $params['content'] = $content;
        $reply_keywords_list = reply_keywords_search( $condition, $params );

        if( !empty( $reply_keywords_list ) ){
            $reply_keywords = $reply_keywords_list[0];
            $rid = $reply_keywords['rid'];
            $sql = "select * from ims_rule where uniacid = ". $_W['uniacid']
                . " AND module = 'sm_shop' AND id = '" . $rid . "' ";
            $rule = pdo_fetch( $sql );

            if( !empty($rule) ){

                $poster_id = str_replace( 'poster_', '',$rule['name'] );

//                $poster_sql = "select * from sh_poster where id= " . $poster_id;
//                $poster = pdo_fetch( $poster_sql );
//                $poster_img = $poster['bg_img'];
                $poster_img = posterModel::makePoster( $poster_id );

                $account_api = WeAccount::create();
                $media = $account_api->uploadMedia(ATTACHMENT_ROOT . $poster_img, 'images');

            }
        }

        if( !empty( $media ) && !empty($media['media_id'])  ){
            return $this->respImage($media['media_id']);
        }else{
            if( empty($fans) ){
                $fans = mc_fansinfo( $_W['member']['uid'] );
            }
            $avatar = !empty($fans['avatar'])?$fans['avatar']:'';
            return $this->respText('海报不存在' . $avatar);
        }


	}
}