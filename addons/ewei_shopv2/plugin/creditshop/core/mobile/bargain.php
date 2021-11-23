<?php
/*珍贵资源 请勿转卖*/

if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Bargain_EweiShopV2Page extends PluginMobileLoginPage {

    public function main(){
        global $_W, $_GPC;

        $id = $_GPC['id'];

        $ajax = $_GPC['ajax'];


        $myMid = (int)m('member')->getMid();
        $mid = (int)$_GPC['mid'];
        if ($mid!==$myMid){
            echo "<script>window.location.href='".mobileUrl('creditshop/bargain',array('mid'=>$myMid,'id'=>$id))."'</script>";die();
        }

        if ($id == NULL) {

            $this->message('未找到订单!', mobileUrl('creditshop/log'));

        }

        //获取订单信息
        $res = pdo_fetch("SELECT l.*,m.nickname,m.avatar FROM ". tablename('ewei_shop_creditshop_log') ." as l join ".tablename('ewei_shop_member'). " as m on m.openid=l.openid WHERE l.id = :id",array(':id'=>$id));



        $res['avatar'] = empty($res['avatar'])?'../addons/ewei_shopv2/static/images/noface.png':$res['avatar'];

        //获取商品信息
        $res2 = pdo_fetch("SELECT * FROM ". tablename('ewei_shop_creditshop_goods') ." WHERE id = :id AND status='1'",array(':id'=>$res['goodsid']));

        if(empty($res2)){

            $this->message('商品不存在或已下架!', mobileUrl('creditshop/log'));

        }

        @session_start();
        $_GPC['bargain_id'] = $id;

        $res2['title2'] = '砍价';
        $res2['start_price'] = $res2['credit'];

        $res2['sold'] = $res2['joins'];
        $res2['stock'] = $res2['total'];
        $res2['images'] = array($res2['thumb']);
        $ewei_detail['content'] = m('ui')->lazy($res2['goodsdetail']);
        $res2['content'] = $ewei_detail['content'];
        $res2['detail'] = m('ui')->lazy($res2['detail']);
        if(substr($res['bargain_price'],-3,3) == '.00'){
            $res['bargain_price'] = intval($res['bargain_price']);
        }
        if(substr($res['now_price'],-3,3) == '.00'){
            $res['now_price'] = intval($res['now_price']);
        }
        if ($_W['openid'] === $res['openid']) {

            $swi = 111;

        }else{

            //消费者
            $order_user = pdo_get('ewei_shop_member',array('openid'=>$res['openid']),array('id'));

            $get_user = pdo_get('ewei_shop_member',array('openid'=>$_W['openid']));

            $chain = explode(',',$get_user['chain']);

            rsort($chain);

            $key_num = array_search($order_user['id'],$chain);

            if($key_num < 2){

                $swi = 222;

            }

        }

        $res2['end_time'] = date('Y-m-d H:i:s',$res['createtime'] + ($res['bargain_day']*24*60*60));

        $time2 = strtotime($res2['end_time']);

        $time3 = $time2 - time();

        $twi_score = bcsub($res['total_score'],$res['get_score'],2);

        $start_time = strtotime($res2['start_time']) - time();

        $year = substr($res2['end_time'],0,4);

        $month = substr($res2['end_time'],5,2);

        $day = substr($res2['end_time'],8,2);

        $hour = substr($res2['end_time'],11,2);

        $minute = substr($res2['end_time'],14,2);

        $second = substr($res2['end_time'],17,2);

        $status = 3;

        //亲友团
        $res3 = pdo_fetchall('select m.avatar,m.nickname,cb.* from '.tablename('ewei_shop_creditshop_bargain')." as cb join ".tablename('ewei_shop_member')." as m on m.openid=cb.openid where cb.oid=:oid order by cb.createtime desc ",array(':oid'=>$res['id']));

        foreach($res3 as $ks=>$vs){

            $res3[$ks]['avatar'] = !empty($vs['avatar'])?$vs['avatar']:'../addons/ewei_shopv2/static/images/noface.png';

            $res3[$ks]['createtime'] = date('Y-m-d H:i:s',$vs['createtime']);
        }

        if ($ajax == 151) {

            echo $this->cut($res,$time3,$swi,$key_num,$res2);
            die();

        }

        include $this->template();

    }



    function join(){

        global $_W,$_GPC;
        $user_info = m('member')->getMember($_W['openid']);
        if(empty($user_info)){
            die('身份验证失败');
        }
        $goods_id = (int)$_GPC['goods'];
        $res = pdo_fetch("SELECT * FROM ". tablename('ewei_shop_bargain_goods') ." WHERE id = :id",array(':id'=>$goods_id));
        if ($res['act_times']>=$res['maximum']){
            $this->message('活动次数已到达上限,不能发起砍价',mobileUrl('bargain/detail',array('id'=>$goods_id)));
        }
        if(!empty($res['initiate'])){
            $count = pdo_get('ewei_shop_bargain_actor',array('goods_id'=>$goods_id,'openid'=>$_W['openid'],'status'=>0,'order'=>0),'id');
            if(!empty($count['id'])){
                echo "<script>window.location.href = '".mobileUrl('bargain/bargain',array('id'=>$count['id']))."'</script>";                die();
            }
        }
        $goods_detail = pdo_fetch("SELECT * FROM ". tablename('ewei_shop_goods') ." WHERE id = :id AND status='1'",array(':id'=>$res['goods_id']));

        if ($goods_detail['total']<=0) {
            $this->message('库存不足,不能发起砍价',mobileUrl('bargain/detail',array('id'=>$goods_id)));
        }elseif(strtotime($res['end_time'])<time()){
            $this->message('活动时间已经结束',mobileUrl('bargain/detail',array('id'=>$goods_id)));
        }elseif (strtotime($res['start_time'])>time()) {
            $this->message('活动时间尚未开始',mobileUrl('bargain/detail',array('id'=>$goods_id)));
        }elseif ($goods_detail['status'] != 1){
            $this->message('商品已下架',mobileUrl('bargain/detail',array('id'=>$goods_id)));
        }
        $time = date("Y-m-d H:i:s",time());
        $data = array('goods_id' => $goods_id, 'now_price' => $goods_detail['marketprice'], 'created_time' => $time, 'update_time' => $time, 'bargain_times' => 0, 'openid' =>$user_info['openid'], 'nickname' => $user_info['nickname'], 'head_image' =>$user_info['avatar'], 'bargain_price' => 0,'status' => 0,'account_id' => $_W['uniacid']);
        if(!empty($user_info['openid'])){
            $if = pdo_insert('ewei_shop_bargain_actor',$data);
            $id = pdo_insertid();
            pdo_query("UPDATE ". tablename('ewei_shop_bargain_goods') ." SET act_times=act_times+1 WHERE id= :id",array(':id'=>$goods_id));
        }else{die('拒绝访问');}


        if ($id) {
            $url = mobileUrl('bargain/bargain',array('id'=>$id),true);
            header("Location:".$url);
            return;
        }else{
            echo "不允许跳转";
        }

    }

    /**
     * @param $res2 订单
     * @param $end_time 剩余时间
     * @param $swi 砍价权限
     * @param $layer 会员层数
     * @param $goods 商品信息
     * @return string
     */
    public function cut($res2,$end_time,$swi,$layer,$goods){
        global $_GPC,$_W;

        $sum = 1;

        $info = m('member')->getMember($_W['openid']);

        $record_where = '';

        if($info['effective'] == 0){

            $state = 0;

            $record_where = " and state = 0 ";


        }else if($info['effective'] ==1 && $layer < 1){

            //判断是否首次砍过该商品，首次没看过砍价无效
            $is_record = pdo_getcolumn('ewei_shop_creditshop_bargain',['oid'=>$res2['id'],'state'=>0,'openid'=>$_W['openid']],['count(id)']);

            if(empty($is_record)){

                return '您无砍价权限';

            }

            $record_where = " and state = 1 ";

            $state = 1;

        }else{

            return '砍价机会已用完！';

        }

        $record_res = pdo_fetchcolumn("SELECT count(1) FROM ". tablename('ewei_shop_creditshop_bargain') ." WHERE oid=:oid AND openid= :openid ".$record_where,array(':oid'=>$res2['id'],':openid'=>$_W['openid']));

        if (empty($res2) || $swi != 222) {

            return "砍价失败！";

        } elseif ($end_time <= 0) {

            return "砍价已结束！";

        } elseif($record_res >= $sum ){

            return "砍价机会已用完";

        }

        if ($res2['total_score'] <= 0) {

            return "积分已经看到底啦,该套餐砍价结束了哟";

        }

        if($layer == 0){
            //直推砍价
            $bargain_price = $goods['one_score'];

        }else if($layer == 1){
            //间推砍价
            $bargain_price = $goods['three_score'];

        }else{

            return '无砍价资格';

        }

        $bargain_data = [
            'uniacid'   => $_W['uniacid'],
            'openid'    => $_W['openid'],
            'oid'       => $res2['id'],
            'score'     => $bargain_price,
            'createtime'=> TIMESTAMP,
            'state'     => $state
        ];

        $res_id = pdo_insert('ewei_shop_creditshop_bargain',$bargain_data);

        $total_score = bcsub($res2['total_score'],$bargain_price,2);

        $get_score = bcadd($res2['get_score'],$bargain_price,2);

        //更新砍价
        $upd_id = pdo_update('ewei_shop_creditshop_log',['total_score'=>$total_score,'get_score'=>$get_score],array('id'=>$res2['id']));

        //赠送购物券
        $coupon = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_coupon') . ' WHERE id=:id and uniacid=:uniacid and merchid=0', array(':id' => 1, ':uniacid' => $_W['uniacid']));

        //增加优惠券日志
        $log = array(
            'uniacid' => $_W['uniacid'],
            'merchid' => $coupon['merchid'],
            'openid' => $_W['openid'],
            'logno' => m('common')->createNO('coupon_log', 'logno', 'CC'),
            'couponid' => $coupon['id'],
            'status' => 1,
            'paystatus' => -1,
            'creditstatus' => -1,
            'createtime' => TIMESTAMP,
            'getfrom' => 0
        );
        pdo_insert('ewei_shop_coupon_log', $log);

        $logid = pdo_insertid();

        $data = array(
            'uniacid' => $_W['uniacid'],
            'merchid' => $coupon['merchid'],
            'openid' => $_W['openid'],
            'couponid' => $coupon['id'],
            'gettype' => 0,
            'gettime' => TIMESTAMP,
            'senduid' => $_W['uid']
        );
        $cid = pdo_insert('ewei_shop_coupon_data', $data);

        if(!empty($res_id) && $upd_id && $logid && $cid){

            return "成功砍掉{$bargain_price}积分,并赠予您购物券！";

        }else{

            return "砍价失败";

        }


    }

    function rule(){
        global $_W,$_GPC;
        $id = $_GPC['id'];
        $myMid = (int)m('member')->getMid();
        $mid = (int)$_GPC['mid'];
        if ($mid!==$myMid){
            echo "<script>window.location.href='".mobileUrl('bargain/rule',array('mid'=>$myMid))."'</script>";die();
        }
        $rule = pdo_get('ewei_shop_bargain_goods',array('id'=>$id,'account_id'=>$_W['uniacid']),array('rule'));
        if (empty($rule['rule'])) {
            $rule = pdo_get('ewei_shop_bargain_account', array('id' => $_W['uniacid']), array('rule'));
        }

        include $this->template();
    }


    private function sendBargainResult($openid, $cut_price, $now_price, $nickname, $iORr, $sORf ,$last = 0) {
        global $_W, $_GPC;
        $time = date('Y-m-d H:i',time());
        $datas[] = array('name' => '砍价金额', 'value' => $cut_price);
        $datas[] = array('name' => '当前金额', 'value' =>  $now_price);
        $datas[] = array('name' => '砍价时间', 'value' => $time);
        $datas[] = array('name' => '砍价人昵称', 'value' => $nickname);
        $datas[] = array('name' => '砍掉或增加', 'value' => $iORr);
        $datas[] = array('name' => '成功或失败', 'value' => $sORf);
        $url = mobileUrl('bargain/bargain',array('id'=>$_GPC['id']),1);
        $remark  =  "\n<a href='{$url}'>点击查看详情</a>";

        if ($last == 1){
            $tag = 'bargain_fprice';
            $text = "砍到底价通知：\n\n[砍价人昵称]帮您砍到底价了，\n砍价结果：[砍掉或增加]了[砍价金额]元\n砍价时间：[砍价时间]\n当前成交价：[当前金额]元\n\n<a href='{$url}'>点击查看详情</a>";
            $message = array(
                'first' => array('value' => "砍到底价通知\n", "color" => "#000000"),
                'keyword1' => array('title' => '业务类型', 'value' => "砍到底价通知", "color" => "#000000"),
                'keyword2' => array('title' => '业务内容', 'value' => "{$nickname}帮你砍到底价", "color" => "#000000"),
                'keyword3' => array('title' => '处理结果', 'value' => "砍到底价", "color" => "#000000"),
                'keyword4' => array('title' => '操作时间', 'value' => date('Y-m-d H:i:s', time()), "color" => "#000000"),
                'remark' => array('value' => "砍价金额：{$iORr}了{$cut_price}元\n砍价时间：{$time}\n当前价格：{$now_price}元\n\n点击立即下单", "color" => "#000000")
            );
        }else{
            $tag = 'bargain_message';
            $text = "砍价成功通知：\n\n{$nickname}帮您砍价{$sORf}，\n砍价结果：{$iORr}了{$cut_price}元\n砍价时间：".$time."\n当前成交价：{$now_price}元\n".$remark;
            $message = array(
                'first' => array('value' => "砍价{$sORf}通知\n", "color" => "#000000"),
                'keyword1' => array('title' => '业务类型', 'value' => "砍价{$sORf}通知", "color" => "#000000"),
                'keyword2' => array('title' => '业务内容', 'value' => "{$nickname}帮你砍价{$sORf}", "color" => "#000000"),
                'keyword3' => array('title' => '处理结果', 'value' => '砍价成功', "color" => "#000000"),
                'keyword4' => array('title' => '操作时间', 'value' => date('Y-m-d H:i:s',time()), "color" => "#000000"),
                'remark' => array('value' => "砍价金额：{$iORr}了{$sORf}元\n砍价时间：{$time}\n当前价格：{$now_price}元\n\n点击立即下单", "color" => "#000000")
            );
        }

        $this->sendNotice(array(
            "openid" => $openid,
            'tag' => $tag,
            'default' => $message,
            'cusdefault' => $text,
            'url' => $url,
            'datas' => $datas,
        ));
    }

    public function sendNotice(array $params) {
        global $_W, $_GPC;

        $tag = isset($params['tag']) ? $params['tag'] : '';
        $touser = isset($params['openid']) ? $params['openid'] : '';
        if (empty($touser)) {
            return;
        }
        $tm = $_W['shopset']['notice'];
        if(empty($tm)) {
            $tm = m('common')->getSysset('notice');
        }

        $tm_temp = $tm[$tag . "_template"];

        $templateid = $tm_temp;
        $datas = isset($params['datas']) ? $params['datas'] : array();

        $default_message = isset($params['default']) ? $params['default'] : array();
        $cusdefault_message = $this->replaceTemplate( isset($params['cusdefault']) ? $params['cusdefault'] :'', $datas);;

        $url = isset($params['url']) ? $params['url'] : '';
        $account = isset($params['account']) ? $params['account'] : m('common')->getAccount();


        if(!empty($tm[$tag.'_close_advanced'])){
            return;
        }

        if (!empty($templateid)) {
            $template = pdo_fetch('select * from ' . tablename('ewei_shop_member_message_template') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $templateid, ':uniacid' => $_W['uniacid']));
            if (!empty($template)) {
                $template_message = array(
                    'first' => array('value' => $this->replaceTemplate($template['first'], $datas), 'color' => $template['firstcolor']),
                    'remark' => array('value' => $this->replaceTemplate($template['remark'], $datas), 'color' => $template['remarkcolor'])
                );
                $data = iunserializer($template['data']);

                foreach ($data as $d) {
                    $template_message[$d['keywords']] = array('value' => $this->replaceTemplate($d['value'], $datas), 'color' => $d['color']);
                }

                $Custom_message = $this->replaceTemplate($template['send_desc'], $datas);

                $messagetype = $template['messagetype'];

                if(empty($messagetype))
                {
                    $ret = m('message')->sendTexts($touser, $Custom_message, $url, $account);
                    if (is_error($ret)) {
                        $ret = m('message')->sendTplNotice($touser, $template['template_id'], $template_message, $url, $account);
                    }
                }
                else if($messagetype==1)
                {
                    $ret = m('message')->sendTplNotice($touser, $template['template_id'], $template_message, $url, $account);

                }
                else if($messagetype==2)
                {
                    $ret = m('message')->sendTexts($touser, $Custom_message, $url, $account);
                }
            } else {
                $ret = m('message')->sendTexts($touser, $cusdefault_message, '', $account);

                if (is_error($ret)) {
                    $templatetype = pdo_fetch('select templateid  from ' . tablename('ewei_shop_member_message_template_type') . ' where typecode=:typecode  limit 1', array(':typecode' => $tag));

                    if(!empty($templatetype['templateid']))
                    {
                        $ret = m('message')->sendTplNotice($touser, $templatetype['templateid'], $default_message, $url, $account);
                    }
                }
            }
        } else {

            $ret = m('message')->sendTexts($touser, $cusdefault_message, '', $account);
            if (is_error($ret)) {
                $templatetype = pdo_fetch('select templateid  from ' . tablename('ewei_shop_member_message_template_type') . ' where typecode=:typecode  limit 1', array(':typecode' => $tag));

                if(!empty($templatetype['templateid']))
                {
                    $ret = m('message')->sendTplNotice($touser, $templatetype['templateid'], $default_message, $url, $account);
                }
            }
        }
    }

    protected function replaceTemplate($str, $datas = array()) {
        foreach ($datas as $d) {
            $str = str_replace("[" . $d['name'] . "]", $d['value'], $str);
        }
        return $str;
    }


}