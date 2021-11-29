<?php 
if( !defined("IN_IA") ) 
{
    exit( "Access Denied" );
}


class Team_EweiShopV2Page extends PluginWebPage
{
    public function main($status)
    {
        global $_W;
        global $_GPC;
        $otherorder = false;

        $pindex = max(1, intval($_GPC["page"]));

        $psize = 20;

        $condition = " o.uniacid=:uniacid ";
        if( $status == 0 )
        {
            $condition .= " and o.release_status = 0 ";

        }
        if( $status == 1 )
        {
            $condition .= " and o.release_status = 1 ";

        }

        $params = array( ":uniacid" => $_W["uniacid"] );

        if( empty($starttime) || empty($endtime) ) 
        {
            $starttime = strtotime("-1 month");
            $endtime = time();
        }

        $searchtime = $_GPC["searchtime"];
        if( $searchtime == "starttime" ) 
        {
            $starttime = strtotime($_GPC["time"]["start"]);
            $endtime = strtotime($_GPC["time"]["end"]);
            $condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
            $params[":starttime"] = $starttime;
            $params[":endtime"] = $endtime;
        }

        if( !empty($_GPC["keyword"]) ) 
        {
            if( $_GPC["searchfield"] == "orderno" ) 
            {
                $condition .= " and o.orderno like :orderno ";
                $params[":orderno"] = "%" . $_GPC["keyword"] . "%";
            }

            if( $_GPC["searchfield"] == "teamid" )
            {
                $condition .= " AND (m.nickname like :keyword or m.realname like :keyword or m.mobile like :keyword)";

                $params[":keyword"] = "%" . $_GPC["keyword"] . "%";
            }

        }

        $total = pdo_fetchcolumn('select count(1) from '.tablename('ewei_shop_creditshop_log')." as o join ".tablename('ewei_shop_member')." as m on o.openid=m.openid join ".tablename('ewei_shop_creditshop_goods'). " as g on g.id=o.goodsid where ".$condition,$params);

        $list = pdo_fetchall('select o.*,m.realname,m.nickname,m.avatar,m.mobile,g.title,g.thumb from '.tablename('ewei_shop_creditshop_log')." as o join ".tablename('ewei_shop_member')." as m on o.openid=m.openid join ".tablename('ewei_shop_creditshop_goods'). " as g on g.id=o.goodsid where ".$condition." order by o.id desc limit ".($pindex-1)*$psize.','.$psize,$params);

        foreach($list as $k=>$v){

            $list[$k]['tobe_score'] = bcadd($v['set_score'],$v['get_score'],2);

            $list[$k]['thumb']      = tomedia($v['thumb']);

            $list[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);

            $list[$k]['end_time']   = date('Y-m-d H:i:s',$v['end_time']);

        }

        $pager = pagination2($total, $pindex, $psize);
        include($this->template('creditshop/team/index'));
    }

    public function multi_array_sort($multi_array, $sort_key, $sort = SORT_DESC)
    {
        if( is_array($multi_array) ) 
        {
            foreach( $multi_array as $row_array ) 
            {
                if( is_array($row_array) ) 
                {
                    $key_array[] = $row_array[$sort_key];
                }
                else
                {
                    return false;
                }

            }
            if( empty($multi_array) ) 
            {
                return false;
            }

            array_multisort($key_array, $sort, $multi_array);
            return $multi_array;
        }
        else
        {
            return false;
        }

    }

    public function detail()
    {
        global $_W;
        global $_GPC;
        $oid = $_GPC["id"];

        $goods = pdo_fetch('select g.thumb,g.credit,g.id,g.title,g.set_score from '.tablename('ewei_shop_creditshop_log')." as l join ".tablename('ewei_shop_creditshop_goods')." as g on l.goodsid=g.id where l.id=:id",array(':id'=>$oid));

        $credit_list = pdo_fetchall('select o.*,m.avatar,m.realname,m.nickname,m.mobile from '.tablename('ewei_shop_creditshop_bargain')." as o left join  ".tablename('ewei_shop_member')." as m on m.openid=o.openid where o.oid=:oid ",array(':oid'=>$oid));


        include($this->template());
    }

    public function release_1(){

        $this->main(0);

    }

    public function release_2(){

        $this->main(1);

    }

}
?>