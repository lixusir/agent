<?php

/**
 * Class Levelrate_EweiShopV2Model
 * 业务处理model
 */
class Levelrate_EweiShopV2Model{

    /**
     * 会员升级
     */
    public function level_upd($openid){

        global $_W;

        //本人消费触发上级升级条件
        $user = pdo_get('ewei_shop_member',array('openid'=>$openid),array('id','openid','chain'));

        $chain_user = pdo_fetchall('select id,openid,agentid,level from '.tablename('ewei_shop_member')." where id IN (".$user['chain']." ) ");

        rsort($chain_user);

        if(!empty($chain_user)){

            foreach($chain_user as $k=>$v){

                $level_info = $this->set_level($v);

                if($level_info['level_id'] > $v['level']){

                    /**升级成功**/
                    pdo_update('ewei_shop_member', ['level'=> $level_info['level_id'],'level_log'=>$level_info['log']],['id'=>$v['id']]);

                }
            }
        }

    }

    /**
     * 获取团队有效会员
     */
    public function team_user($uid,&$data){

        $user_data = pdo_getall('ewei_shop_member',array('agentid'=>$uid),array('id','agentid','openid','effective'));

        if(empty($user_data)){

            return $data;

        }
        foreach($user_data as $team){

            if($team['effective'] == 1){

                $data[] = "'".$team['openid']."'";

            }

            $this->team_user($team['id'],$data);

        }
    }

    /**
     * 等级升级逻辑处理
     */
    public function set_level($v){

        global $_W;

        //自费次数
        $u_number = intval(pdo_getcolumn('ewei_shop_creditshop_log',array('openid'=>$v['openid']),array('count(id)')));

        //直推有效会员+业绩
        $push_user = pdo_getall('ewei_shop_member',array('agentid'=>$v['id'],'effective'=>1),array('openid'));

        //人数
        $push_num = !empty($push_user)?count($push_user):0;

        $push_openids = '';

        foreach($push_user as $c){

            $push_openids .= "'".$c['openid']."',";

        }

        //业绩
        if(!empty($push_openids)){

            $push_price = pdo_fetchcolumn('select sum(credit) from '.tablename('ewei_shop_creditshop_log')." where openid in (".trim($push_openids,',').") ");

        }else{

            $push_price = 0;

        }

        //团队有效会员+业绩
        $this->team_user($v['id'],$team_data);

        $team_num = !empty($team_data)?count($team_data):0;unset($team_data);

        if(!empty($team_data)){

            $team_openids = implode(',',$team_data);

            $team_price = pdo_fetchcolumn('select sum(credit) from '.tablename('ewei_shop_creditshop_log')." where openid in ($team_openids) ");

        }

        $level_info = [];

        //条件1
        $level_1 = pdo_fetchcolumn('select id from '.tablename('ewei_shop_member_level')." where u_number<=:u_number and push_num <=:push_num and team_num<=:team_num order by id desc ",array(':u_number'=>$u_number,':push_num'=>$push_num,':team_num'=>$team_num));

        //条件2
        $level_2 = pdo_fetchcolumn('select id from '.tablename('ewei_shop_member_level')." where push_price<=:push_price and team_price <=:team_price order by id desc ",array(':push_price'=>$push_price,':team_price'=>$team_price));

        $level_id = max($level_1,$level_2);

        $level_info['level_id'] = $level_id;

        $level_info['log'] = serialize([
            '自费'    => $u_number,
            '直推有效' => $push_num,
            '直推业绩' => $push_price,
            '团队有效' => $team_num,
            '团队业绩' => $team_price
        ]);

        return $level_info;
    }

    /**
     * 级差+同级奖
     */
    public function level_rate($order=array()){

        global $_W;

        if(empty($order)){

            return false;

        }

        //获取上级
        $user = pdo_get('ewei_shop_member',array('openid'=>$order['openid']),array('id','openid','chain'));

        $chain_user = pdo_fetchall('select m.id,m.openid,l.level_rate,l.flat_rate,m.level from '.tablename('ewei_shop_member')." as m left join ".tablename('ewei_shop_member_level'). " as l on l.id=m.level  where m.id IN (".$user['chain']." ) ");

        rsort($chain_user);

        //已分润
        $notRate = 0;

        //同级奖励
        $flat_id = 0;

        //分润值
        $is_fenrun = 0;

        foreach($chain_user as $k=>$v){

            if($v['level'] <= 0){

                continue;

            }

            if($flat_id == $v['level']){

                $flat_fenrun = bcmul($is_fenrun,$v['flat_rate']/100,2);

                m('member')->setCredit($v['openid'],'credit2',$flat_fenrun,[$_W['uid'],'订单:'.$order['logno'].'-同级奖'],'200');

                //销毁同级日志
                $flat_id = 0;

                $is_fenrun = 0;

            }else if($v['level_rate']>0){

                $rate_fenrun = bcsub($v['level_rate'] , $notRate,2);

                if($rate_fenrun > 0){

                    $res_fenrun = bcmul($order['credit'],$rate_fenrun/100,2);

                    $notRate = $v['level_rate'];

                    m('member')->setCredit($v['openid'],'credit2',$res_fenrun,[$_W['uid'],'订单:'.$order['logno'].'-级差奖'],'100');

                    $flat_id = $v['level'];

                    $is_fenrun = $res_fenrun;

                }

            }
        }
    }


    /**
     * 累计奖金池
     * @param $price
     */
    public function set_pool($price){

        global $_W;


        $pool = pdo_get('ewei_shop_member_pool',array('state'=>0));

        if(empty($pool)){

            $cycle = pdo_fetchcolumn('select cycle from '.tablename('ewei_shop_member_pool')." order by cycle desc ");

            $data = [
                'price'         => $price,
                'cycle'         => bcadd($cycle,1),
                'createtime'    => TIMESTAMP,
                'state'         => 0
            ];

            pdo_insert('ewei_shop_member_pool',$data);

        }else{

            pdo_update('ewei_shop_member_pool',['price'=>bcadd($price,$pool['price'],2)],['id'=>$pool['id']]);

        }

    }



    /**
     * 奖金池分红
     */
    public function get_pool(){

        global $_W;

        $pool = pdo_get('ewei_shop_member_pool',array('state'=>0));

        //获取会员等级
        $level_list = pdo_getall('ewei_shop_member_level',array('uniacid'=>$_W['uniacid']),array('id','pool_rate'));

        foreach($level_list as $v){

            $level_user = pdo_getall('ewei_shop_member',array('level'=>$v['id']),array('id','openid'));

            if(empty($level_user)){

                continue;

            }

            //等级人数平分
            $level_num = count($level_user);

            //加权分红奖励
            $level_pool_price = bcdiv(bcmul($pool['price'],$v['pool_rate']/100,2),$level_num,2);

            if($level_pool_price > 0){

               foreach($level_user as $u){

                   m('member')->setCredit($u['openid'],'credit2',$level_pool_price,[$_W['uid'],'奖金池分红'],300);

               }
            }
        }

        $config = m('common')->getSysset('shopset');

        //获取股东身份的人
        $share_user = pdo_getall('ewei_shop_member',array('share_level'=>1),array('id','openid'));

        if(!empty($share_user)){

            $share_total = count($share_user);

            $share_price = bcdiv(bcmul($pool['price'],$config['pool_rate']/100,2),$share_total,2);

            if($share_price > 0){

                foreach($share_user as $u){

                    m('member')->setCredit($u['openid'],'credit2',$share_price,[$_W['uid'],'股东分红'],301);

                }
            }
        }

        pdo_update('ewei_shop_member_pool',['state'=>1,'get_time'=>TIMESTAMP],['id'=>$pool['id']]);

        echo 'ok';
    }

    /**
     * 清空数据表
     */
    public function del($type = 0){

        global $_W;

        if($type == 0){

            return '1111';

        }

        //会员
        pdo_delete('ewei_shop_member',array('uniacid'=>$_W['uniacid']));

        //微信会员
        pdo_delete('mc_members',array('uniacid'=>$_W['uniacid']));

        //关注
        pdo_delete('mc_mapping_fans',array('uniacid'=>$_W['uniacid']));

        //订单
        pdo_delete('ewei_shop_order',array('uniacid'=>$_W['uniacid']));

        //订单列表
        pdo_delete('ewei_shop_order_goods',array('uniacid'=>$_W['uniacid']));

        //充值记录
        pdo_delete('ewei_shop_member_log',array('uniacid'=>$_W['uniacid']));

        //流水记录
        pdo_delete('ewei_shop_member_credit_record',array('uniacid'=>$_W['uniacid']));

        pdo_delete('mc_credits_record',array('uniacid'=>$_W['uniacid']));

        //推广区消费
        pdo_delete('ewei_shop_creditshop_log',array('uniacid'=>$_W['uniacid']));

        //奖金池
        pdo_delete('ewei_shop_member_pool',array('uniacid'=>$_W['uniacid']));


    }

    /**
     * 资金流水类型
     */
    public function assets_type(){

        return  [
            ['change_type' => 0,'change_name'=>'商城消费'],
            ['change_type' => 100,'change_name'=>'级别奖'],
            ['change_type' => 200,'change_name'=>'同级奖'],
            ['change_type' => 300,'change_name'=>'奖金池分红'],
            ['change_type' => 301,'change_name'=>'股东分红'],
            ['change_type' => 400,'change_name'=>'购买代理'],
            ['change_type' => 401,'change_name'=>'区分红'],
            ['change_type' => 402,'change_name'=>'市分红'],
            ['change_type' => 403,'change_name'=>'省分红'],
            ['change_type' => 500,'change_name'=>'积分释放'],
            ['change_type' => 600,'change_name'=>'直推奖励'],
            ['change_type' => 700,'change_name'=>'间推奖励'],
        ];
    }

    /**
     * 区域分红
     */
    public function abonus_rate($order=array(),$credit_goods=array()){

        global $_W;

        //区域分红奖励
        $abonus_rate = unserialize($credit_goods['abonus_rate']);

        //获取商品地址
        $address = unserialize($order['address']);

        //获取区域代理商
        $abonus_user = pdo_getall('ewei_shop_member',array('aagentlevel >'=>0),array('openid','aagentlevel','aagentprovinces','aagentcitys','aagentareas'));

        foreach($abonus_user as $u){

            $abonus_price = 0;

            //省
            if(strstr($u['aagentprovinces'],$address['province'])){

                $abonus_price = $abonus_rate[$u['aagentlevel']];

                $change_type = 403;

            }

            //市
            if(strstr($u['aagentcitys'],$address['province'].$address['city'])){

                $abonus_price = $abonus_rate[$u['aagentlevel']];

                $change_type = 402;

            }

            //区
            if(strstr($u['aagentareas'],$address['province'].$address['city'].$address['area'])){

                $abonus_price = $abonus_rate[$u['aagentlevel']];

                $change_type = 401;

            }

            if(!empty($abonus_price)){

                m('member')->setCredit($u['openid'],'credit2',$abonus_price,[$_W['uid'],'订单'.$order['logno'].'-代理奖'],$change_type);

            }

        }


    }

    /**
     * 兑换区两级分销
     */
    public function commission_rate($order=array()){

        global $_W;

        $user = pdo_get('ewei_shop_member',array('openid'=>$order['openid']),array('id','agentid'));

        $goods_list = pdo_getall('ewei_shop_order_goods',array('orderid'=>$order['id']),array('goodsid'));

        if(!empty($goods_list)){

            foreach($goods_list as $k=>$v){

                //获取直推和间推奖励
                $goods_info = pdo_get('ewei_shop_goods',array('id'=>$v['goodsid']),array('id','push_price','inter_price'));

                /**直推奖**/
                $push_user = pdo_get('ewei_shop_member',array('id'=>$user['agentid']),array('id','openid','agentid'));

                if(!empty($push_user) && $goods_info['push_price'] > 0){

                    m('member')->setCredit($push_user['openid'],'credit2',$goods_info['push_price'],[$_W['uid'],'订单:'.$order['ordersn'].'-直推奖'],600);echo 1;
                }

                /**间推奖**/
                $inter_user = pdo_get('ewei_shop_member',array('id'=>$push_user['agentid']),array('id','openid'));

                if(!empty($inter_user) && $goods_info['inter_price'] > 0 ){

                    m('member')->setCredit($inter_user['openid'],'credit2',$goods_info['inter_price'],[$_W['uid'],'订单:'.$order['ordersn'].'-间推奖'],700);echo 2;
                }
            }
        }
    }
}