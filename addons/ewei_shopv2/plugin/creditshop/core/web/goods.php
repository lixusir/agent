<?php


if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Goods_EweiShopV2Page extends PluginWebPage {

	function main() {
		global $_W, $_GPC;
		//多商户
		$merch_plugin = p('merch');
		$merch_data = m('common')->getPluginset('merch');
		if ($merch_plugin && $merch_data['is_openmerch']) {
			$is_openmerch = 1;
		} else {
			$is_openmerch = 0;
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = ' uniacid = :uniacid ';
        $tab = !empty($_GPC['tab'])?trim($_GPC['tab']):'sell';
        if(empty($tab)||$tab=='sell'){    //出售中
            $condition .= ' and status = 1 and total > 0 and deleted = 0 ';
        }elseif($tab=='sellout'){          //已售罄
            $condition .= ' and status = 1 and total <= 0 and deleted = 0 ';
        }elseif($tab=='warehouse'){         //仓库中
            $condition .= ' and status = 0 and deleted = 0 ';
        }elseif($tab=='recycle'){           //回收站
            $condition .= ' and deleted = 1 ';
        }
		$params = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {
			$_GPC['keyword'] = trim($_GPC['keyword']);
			$condition .= ' AND title LIKE :title';
			$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
		}
        /*if ($_GPC['status'] != '') {
            $condition .= ' AND status = :status';
            $params[':status'] = intval($_GPC['status']);
        }*/
		if ($_GPC['cate'] != '') {
			$condition .= ' AND cate = :cate';
			$params[':cate'] = intval($_GPC['cate']);
		}
		$sql = 'SELECT * FROM ' . tablename('ewei_shop_creditshop_goods') . " where  1 and {$condition} ORDER BY displayorder DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $params);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_creditshop_goods') . " where 1 and {$condition}", $params);
		if($merch_plugin) {
			$merch_user = $merch_plugin->getListUser($list,'merch_user');
			if (!empty($list) && !empty($merch_user)) {
				foreach ($list as &$row) {
					$row['merchname'] = $merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name'];
				}
				unset($row);
			}
		}
		$pager = pagination2($total, $pindex, $psize);
		$category = pdo_fetchall("select id,`name`,thumb from " . tablename('ewei_shop_creditshop_category') . ' where uniacid=:uniacid order by displayorder desc', array(':uniacid' => $_W['uniacid']), 'id');
		include $this->template();
	}

	function add() {
		$this->post();
	}

	function edit() {
		$this->post();
	}

	protected function post() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);

		$item = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_creditshop_goods') . " WHERE id =:id and uniacid=:uniacid limit 1", array(':uniacid' => $_W['uniacid'], ':id' => $id));
		$item['abonus_rate'] = unserialize($item['abonus_rate']);

		$merchid = intval($_W['merchid']);
		if(!empty($item)){
			$url = mobileUrl('creditshop/detail',array('id'=>$item['id']), true);
			$qrcode = m('qrcode')->createQrcode($url);
		}
		//会员权限
		if ($item['showlevels'] != '') {
			$item['showlevels'] = explode(',', $item['showlevels']);
		}
		if ($item['buylevels'] != '') {
			$item['buylevels'] = explode(',', $item['buylevels']);
		}
		if ($item['showgroups'] != '') {
			$item['showgroups'] = explode(',', $item['showgroups']);
		}
		if ($item['buygroups'] != '') {
			$item['buygroups'] = explode(',', $item['buygroups']);
		}
		if(!empty($item['goodsdetail'])){
            $item['goodsdetail'] = m('common')->html_to_images($item['goodsdetail']);
        }
		//兑换门店
		$stores = array();
		if (!empty($item['storeids'])) {
			$stores = pdo_fetchall('select id,storename from ' . tablename('ewei_shop_store') . ' where id in (' . $item['storeids'] . ' ) and uniacid=' . $_W['uniacid']);
		}
		//单独通知人
		if (!empty($item['noticeopenid'])) {
			$saler = m('member')->getMember($item['noticeopenid']);
		}
		$endtime = empty($item['endtime']) ? date('Y-m-d H:i', time()) : date('Y-m-d H:i', $item['endtime']);
		$groups = m('member')->getGroups();
		$category = pdo_fetchall("select id,`name`,thumb from " . tablename('ewei_shop_creditshop_category') . ' where uniacid=:uniacid order by displayorder desc', array(':uniacid' => $_W['uniacid']));

		if(empty($item['goodstype']) || $item['goodstype']==0){
			$goods = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_goods') . " WHERE id =:id and status=1 and deleted=0 and uniacid=:uniacid limit 1", array(':uniacid' => $_W['uniacid'], ':id' => $item['goodsid']));
		}elseif($item['goodstype']==1){
			$coupon = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_coupon') . " WHERE id =:id and uniacid=:uniacid limit 1", array(':uniacid' => $_W['uniacid'], ':id' => $item['couponid']));
		}
		$goodstype = $item['goodstype'];
		$type = $item['type'];
		$isverify = $item['isverify'];
		$levels = m('member')->getLevels();
		foreach($levels as &$l){
			$l['key'] ='level'.$l['id'];
		}
		unset($l);
		$levels =array_merge(array(
			array(
				'id'=>0,
				'key'=>'default',
				'levelname'=>empty($_W['shopset']['shop']['levelname'])?'默认会员':$_W['shopset']['shop']['levelname']
			)
		),$levels);

		$allspecs = pdo_fetchall("select * from " . tablename('ewei_shop_creditshop_spec') . " where goodsid=:id order by displayorder asc", array(":id" => $id));
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall("select a.id,a.specid,a.title,a.thumb,a.show,a.displayorder,a.valueId,a.virtual,b.title as title2 from " . tablename('ewei_shop_creditshop_spec_item') . " a left join " . tablename('ewei_shop_virtual_type') . " b on b.id=a.virtual  where a.specid=:specid order by a.displayorder asc", array(":specid" => $s['id']));
		}
		unset($s);

		/**区域奖励**/
        $abonus_levels = p('abonus')->getLevels();


		//处理规格项
		$html = "";
		$discounts_html='';
		$commission_html='';
		$isdiscount_discounts_html='';
		$options = pdo_fetchall("select * from " . tablename('ewei_shop_creditshop_option') . " where goodsid=:id order by id asc", array(':id' => $id));
		//排序好的specs
		$specs = array();
		//找出数据库存储的排列顺序
		if (count($options) > 0) {
			$specitemids = explode("_", $options[0]['specs']);
			foreach ($specitemids as $itemid) {
				foreach ($allspecs as $ss) {
					$items = $ss['items'];
					foreach ($items as $it) {
						if ($it['id'] == $itemid) {
							$specs[] = $ss;
							break;
						}
					}
				}
			}
			$html = '';
			$html .= '<table class="table table-bordered table-condensed">';
			$html .= '<thead>';
			$html .= '<tr class="active">';
			$discounts_html .= '<table class="table table-bordered table-condensed">';
			$discounts_html .= '<thead>';
			$discounts_html .= '<tr class="active">';
			$commission_html .= '<table class="table table-bordered table-condensed">';
			$commission_html .= '<thead>';
			$commission_html .= '<tr class="active">';
			$isdiscount_discounts_html .= '<table class="table table-bordered table-condensed">';
			$isdiscount_discounts_html .= '<thead>';
			$isdiscount_discounts_html .= '<tr class="active">';
			$len = count($specs);
			$newlen = 1; //多少种组合
			$h = array(); //显示表格二维数组
			$rowspans = array(); //每个列的rowspan
			for ($i = 0; $i < $len; $i++) {
				//表头
				$html .= "<th>" . $specs[$i]['title'] . "</th>";
				$discounts_html .= "<th>" . $specs[$i]['title'] . "</th>";
				$commission_html .= "<th>" . $specs[$i]['title'] . "</th>";
				$isdiscount_discounts_html .= "<th>" . $specs[$i]['title'] . "</th>";
				//计算多种组合
				$itemlen = count($specs[$i]['items']);
				if ($itemlen <= 0) {
					$itemlen = 1;
				}
				$newlen *= $itemlen;
				//初始化 二维数组
				$h = array();
				for ($j = 0; $j < $newlen; $j++) {
					$h[$i][$j] = array();
				}
				//计算rowspan
				$l = count($specs[$i]['items']);
				$rowspans[$i] = 1;
				for ($j = $i + 1; $j < $len; $j++) {
					$rowspans[$i]*= count($specs[$j]['items']);
				}
			}
			$canedit = ce('goods',$item);
			if($canedit){
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">库存</div><div class="input-group"><input type="text" class="form-control input-sm option_total_all"  VALUE=""/><span class="input-group-addon" ><a href="javascript:;" class="fa fa-angle-double-down" title="批量设置" onclick="setCol(\'option_total\');"></a></span></div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">积分</div>
<div class="input-group"><input type="text" class="form-control  input-sm option_credit_all"  VALUE=""/><span class="input-group-addon">
<a href="javascript:;" class="fa fa-angle-double-down" title="批量设置" onclick="setCol(\'option_credit\');"></a></span></div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">金额</div>
<div class="input-group"><input type="text" class="form-control input-sm option_money_all"  VALUE=""/><span class="input-group-addon">
<a href="javascript:;" class="fa fa-angle-double-down" title="批量设置" onclick="setCol(\'option_money\');"></a></span></div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">编码</div><div class="input-group"><input type="text" class="form-control input-sm option_goodssn_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-angle-double-down" title="批量设置" onclick="setCol(\'option_goodssn\');"></a></span></div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">条码</div><div class="input-group"><input type="text" class="form-control input-sm option_productsn_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-angle-double-down" title="批量设置" onclick="setCol(\'option_productsn\');"></a></span></div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">重量（克）</div><div class="input-group"><input type="text" class="form-control input-sm option_weight_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-angle-double-down" title="批量设置" onclick="setCol(\'option_weight\');"></a></span></div></div></th>';
			}else {
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">库存</div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">积分</div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">金额</div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">商品编码</div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">商品条码</div></div></th>';
				$html .= '<th><div class=""><div style="padding-bottom:10px;text-align:center;">重量（克）</div></th>';
			}
			$html .= '</tr></thead>';
			$discounts_html .= '</tr></thead>';
			$isdiscount_discounts_html .= '</tr></thead>';
			$commission_html .= '</tr></thead>';

			for ($m = 0; $m < $len; $m++) {
				$k = 0;
				$kid = 0;
				$n = 0;
				for ($j = 0; $j < $newlen; $j++) {
					$rowspan = $rowspans[$m];
					if ($j % $rowspan == 0) {
						$h[$m][$j] = array("html" => "<td class='full' rowspan='" . $rowspan . "'>" . $specs[$m]['items'][$kid]['title'] . "</td>", "id" => $specs[$m]['items'][$kid]['id']);
					} else {
						$h[$m][$j] = array("html" => "", "id" => $specs[$m]['items'][$kid]['id']);
					}
					$n++;
					if ($n == $rowspan) {
						$kid++;
						if ($kid > count($specs[$m]['items']) - 1) {
							$kid = 0;
						}
						$n = 0;
					}
				}
			}
			$hh = "";
			$dd = "";
			$isdd = "";
			$cc = "";
			for ($i = 0; $i < $newlen; $i++) {
				$hh.="<tr>";
				$dd.="<tr>";
				$isdd.="<tr>";
				$cc.="<tr>";
				$ids = array();
				for ($j = 0; $j < $len; $j++) {
					$hh.=$h[$j][$i]['html'];
					$dd.=$h[$j][$i]['html'];
					$isdd.=$h[$j][$i]['html'];
					$cc.=$h[$j][$i]['html'];
					$ids[] = $h[$j][$i]['id'];
				}
				$ids = implode("_", $ids);
				$val = array("id" => "", "title" => "", "total" => "", "credit" => "", "money" => "", "weight" => "",'virtual'=>'');

				foreach ($options as $o) {
					if ($ids === $o['specs']) {
						$val = array(
							"id" => $o['id'],
							"title" => $o['title'],
							"total" => $o['total'],
							"credit" => $o['credit'],
							"money" => $o['money'],
							"goodssn"=>$o['goodssn'],
							"productsn"=>$o['productsn'],
							"weight" => $o['weight'],
							'virtual'=>$o['virtual']
						);
						$discount_val = array(
							"id" => $o['id'],
						);
						break;
					}
				}
				if($canedit){
					foreach ($levels as $level) {
						$dd .= '<td>';
						$isdd .= '<td>';
						if($level['key']=='default')
						{
							$dd .= '<input data-name="discount_level_'.$level['key'].'_' . $ids . '"  type="text" class="form-control discount_'.$level['key'].' discount_'.$level['key'].'_' . $ids . '" value="' . $discounts_val[$level['key']] . '"/> ';
							$isdd .= '<input data-name="isdiscount_discounts_level_'.$level['key'].'_' . $ids . '"  type="text" class="form-control isdiscount_discounts_'.$level['key'].' isdiscount_discounts_'.$level['key'].'_' . $ids . '" value="' . $isdiscounts_val[$level['key']] . '"/> ';
						}
						else
						{
							$dd .= '<input data-name="discount_level_'.$level['id'].'_' . $ids . '"  type="text" class="form-control discount_level'.$level['id'].' discount_level'.$level['id'].'_' . $ids . '" value="' . $discounts_val['level'.$level['id']] . '"/> ';
							$isdd .= '<input data-name="isdiscount_discounts_level_'.$level['id'].'_' . $ids . '"  type="text" class="form-control isdiscount_discounts_level'.$level['id'].' isdiscount_discounts_level'.$level['id'].'_' . $ids . '" value="' . $isdiscounts_val['level'.$level['id']] . '"/> ';
						}
						$dd .= '</td>';
						$isdd .= '</td>';
					}
					$dd .= '<input data-name="discount_id_' . $ids . '"  type="hidden" class="form-control discount_id discount_id_' . $ids . '" value="' . $discounts_val['id'] . '"/>';
					$dd .= '<input data-name="discount_ids"  type="hidden" class="form-control discount_ids discount_ids_' . $ids . '" value="' . $ids . '"/>';
					$dd .= '<input data-name="discount_title_' . $ids . '"  type="hidden" class="form-control discount_title discount_title_' . $ids . '" value="' . $discounts_val['title'] . '"/>';
					$dd .= '<input data-name="discount_virtual_' . $ids . '"  type="hidden" class="form-control discount_title discount_virtual_' . $ids . '" value="' . $discounts_val['virtual'] . '"/>';
					$dd .= "</tr>";

					$isdd .= '<input data-name="isdiscount_discounts_id_' . $ids . '"  type="hidden" class="form-control isdiscount_discounts_id isdiscount_discounts_id_' . $ids . '" value="' . $isdiscounts_val['id'] . '"/>';
					$isdd .= '<input data-name="isdiscount_discounts_ids"  type="hidden" class="form-control isdiscount_discounts_ids isdiscount_discounts_ids_' . $ids . '" value="' . $ids . '"/>';
					$isdd .= '<input data-name="isdiscount_discounts_title_' . $ids . '"  type="hidden" class="form-control isdiscount_discounts_title isdiscount_discounts_title_' . $ids . '" value="' . $isdiscounts_val['title'] . '"/>';
					$isdd .= '<input data-name="isdiscount_discounts_virtual_' . $ids . '"  type="hidden" class="form-control isdiscount_discounts_title isdiscount_discounts_virtual_' . $ids . '" value="' . $isdiscounts_val['virtual'] . '"/>';
					$isdd .= "</tr>";

					$cc .= '<input data-name="commission_id_' . $ids . '"  type="hidden" class="form-control commission_id commission_id_' . $ids . '" value="' . $commissions_val['id'] . '"/>';
					$cc .= '<input data-name="commission_ids"  type="hidden" class="form-control commission_ids commission_ids_' . $ids . '" value="' . $ids . '"/>';
					$cc .= '<input data-name="commission_title_' . $ids . '"  type="hidden" class="form-control commission_title commission_title_' . $ids . '" value="' . $commissions_val['title'] . '"/>';
					$cc .= '<input data-name="commission_virtual_' . $ids . '"  type="hidden" class="form-control commission_title commission_virtual_' . $ids . '" value="' . $commissions_val['virtual'] . '"/>';
					$cc .= "</tr>";
					$hh .= '<td>';
					$hh .= '<input data-name="option_total_' . $ids . '"  type="text" class="form-control option_total option_total_' . $ids . '" value="' . $val['total'] . '"/>';
					$hh .= '</td>';
					$hh .= '<input data-name="option_id_' . $ids . '"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
					$hh .= '<input data-name="option_ids"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
					$hh .= '<input data-name="option_title_' . $ids . '"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
					$hh .= '<input data-name="option_virtual_' . $ids . '"  type="hidden" class="form-control option_virtual option_virtual_' . $ids . '" value="' . $val['virtual'] . '"/>';
					$hh .= '<td><input data-name="option_credit_' . $ids . '" type="text" class="form-control option_credit option_credit_' . $ids . '" value="' . $val['credit'] . '"/></td>';
					$hh .= '<td><input data-name="option_money_' . $ids . '" type="text" class="form-control option_money option_money_' . $ids . '" " value="' . $val['money'] . '"/></td>';
					$hh .= '<td><input data-name="option_goodssn_' . $ids . '" type="text" class="form-control option_goodssn option_goodssn_' . $ids . '" " value="' . $val['goodssn'] . '"/></td>';
					$hh .= '<td><input data-name="option_productsn_' . $ids . '" type="text" class="form-control option_productsn option_productsn_' . $ids . '" " value="' . $val['productsn'] . '"/></td>';
					$hh .= '<td><input data-name="option_weight_' . $ids . '" type="text" class="form-control option_weight option_weight_' . $ids . '" " value="' . $val['weight'] . '"/></td>';
					$hh .= '</tr>';
				} else{
					$hh .= '<td>' . $val['total'] . '</td>';
					$hh .= '<td>' . $val['credit'] . '</td>';
					$hh .= '<td>' . $val['money'] . '</td>';
					$hh .= '<td>' . $val['goodssn'] . '</td>';
					$hh .= '<td>' . $val['productsn'] . '</td>';
					$hh .= '<td>' . $val['weight'] . '</td>';
					$hh .= '</tr>';
				}
			}
			$discounts_html .= $dd;
			$discounts_html .= "</table>";
			$isdiscount_discounts_html .= $isdd;
			$isdiscount_discounts_html .= "</table>";
			$html .= $hh;
			$html .= "</table>";
			$commission_html .= $cc;
			$commission_html .= "</table>";
		}
		//查询快递模板
		$dispatch_data = pdo_fetchall('select * from ' . tablename('ewei_shop_dispatch') . ' where uniacid=:uniacid and merchid=:merchid and enabled=1 order by displayorder desc', array(':uniacid' => $_W['uniacid'], ':merchid' =>$merchid));

		if ($_W['ispost']) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'displayorder' => intval($_GPC['displayorder']),
				'goodstype' => intval($_GPC['goodstype']),
				'goodsid' => intval($_GPC['goodsid']),			//商品
				'couponid' => intval($_GPC['couponid']),			//优惠券
				'grant1' => floatval($_GPC['grant1']),			//余额
				'grant2' => floatval($_GPC['grant2']),			//红包
				'packetmoney' => floatval($_GPC['packetmoney']),		//红包总金额
				'packetlimit' => floatval($_GPC['packetlimit']),		//红包限制金额
				'packettotal' => intval($_GPC['packettotal']),			//红包总数
				'packettype' => intval($_GPC['packettype']),			//红包类型 0 固定金额 1 随机金额
				'minpacketmoney' => floatval($_GPC['minpacketmoney']),	//随机金额最小金额
				'maxpacketmoney' => floatval($_GPC['maxpacketmoney']),	//随机金额最大金额
				'title' => trim($_GPC['title']),
				'cate' => intval($_GPC['cate']),
				'thumb' => save_media($_GPC['thumb']),
				'price' => floatval($_GPC['price']),
				'productprice' => floatval($_GPC['productprice']),
				'credit' => intval($_GPC['credit']),
				'money' => trim($_GPC['money']),
				'dispatchtype' => intval($_GPC['dispatchtype']),
				'dispatchid' => intval($_GPC['dispatchid']),
				'dispatch' => floatval($_GPC['dispatch']),
				'istop' => intval($_GPC['istop']),
				'isrecommand' => intval($_GPC['isrecommand']),
				'istime' => intval($_GPC['istime']),
				'timestart' => strtotime($_GPC['timestart']),
				'timeend' => strtotime($_GPC['timeend']),
				'goodsdetail' => m('common')->html_images($_GPC['goodsdetail']),

				'goodssn' => trim($_GPC['goodssn']),
				'productsn' => trim($_GPC['productsn']),
				'weight' => intval($_GPC['weight']),
				'total' => intval($_GPC['total']),
				'showtotal' => intval($_GPC['showtotal']),
				'totalcnf' => intval($_GPC['totalcnf']),
				'hasoption' => intval($_GPC['hasoption']),

				'status' => intval($_GPC['status']),
				'type' => intval($_GPC['type']),
				'area' => trim($_GPC['area']),
				'chanceday' => intval($_GPC['chanceday']),
				'chance' => intval($_GPC['chance']),
				'totalday' => intval($_GPC['totalday']),
				//'usecredit2' => intval($_GPC['usecredit2']),			//优先支付方式
				'rate1' => trim($_GPC['rate1']),
				'rate2' => trim($_GPC['rate2']),
				'isendtime' => intval($_GPC['isendtime']),
				'usetime' => intval($_GPC['usetime']) >= 0 ? intval($_GPC['usetime']) : 0 ,
				'endtime' => strtotime($_GPC['endtime']),
				'detailshow' => intval($_GPC['detailshow']),
				'noticedetailshow' => intval($_GPC['noticedetailshow']),
				'detail' => m('common')->html_images($_GPC['detail']),
				'noticedetail' => m('common')->html_images($_GPC['noticedetail']),

				'showlevels' => is_array($_GPC['showlevels']) ? implode(",", $_GPC['showlevels']) : '',
				'buylevels' => is_array($_GPC['buylevels']) ? implode(",", $_GPC['buylevels']) : '',
				'showgroups' => is_array($_GPC['showgroups']) ? implode(",", $_GPC['showgroups']) : '',
				'buygroups' => is_array($_GPC['buygroups']) ? implode(",", $_GPC['buygroups']) : '',

				'subtitle' => trim($_GPC['subtitle']),
				'subdetail' => m('common')->html_images($_GPC['subdetail']),
				'isverify' => intval($_GPC['isverify']),
				'verifytype' => intval($_GPC['verifytype']),
				'verifynum' => intval($_GPC['verifynum']),
				'storeids' => is_array($_GPC['storeids']) ? implode(',', $_GPC['storeids']) : '',
				'noticeopenid' => trim($_GPC['noticeopenid']),

				'followneed' => intval($_GPC['followneed']),
				'followtext' => trim($_GPC['followtext']),
				'share_title' => trim($_GPC['share_title']),
				'share_icon' => save_media($_GPC['share_icon']),
				'share_desc' => trim($_GPC['share_desc']),
                'total_score'   => floatval($_GPC['total_score']),
                'one_score_0'     => floatval($_GPC['one_score_0']),
                'three_score_0'   => floatval($_GPC['three_score_0']),
                'one_score_1'     => floatval($_GPC['one_score_1']),
                'three_score_1'   => floatval($_GPC['three_score_1']),
                'pool_price'   => floatval($_GPC['pool_price']),
                'bargain_day'   => intval($_GPC['bargain_day']),
                'abonus_rate'   => serialize($_GPC['abonus_rate']),
                'set_score'     => floatval($_GPC['set_score'])
			);

			if (isset($id) && $id > 0 )
            {
                unset($data['maxpacketmoney']);
            }
			if ($isverify) {
				//如果为线下兑换，自动取消运费
				$data['dispatch'] = 0;
				if($data['verifytype'] == 1 && $data['verifynum'] < 1){
					$data['verifynum'] = 1;
				}
				if($data['verifytype'] == 0){
					$data['verifynum'] = 1;
				}
			}
			if($data['credit']<=0){
				show_json(0,'请正确填写积分！');
			}
			if($data['money']<0){
				show_json(0,'请正确填写金额！');
			}
			if($data['goodstype']==2 && $data['grant1']<=0){
				show_json(0,'请正确填写余额！');
			}
			$data['vip'] = (!empty($data['showlevels']) || !empty($data['showgroups']) ) ? 1 : 0;

			if (!empty($id)) {
				$data['goodstype'] = $goodstype;
				$data['type'] = $type;
				$data['isverify'] = $isverify;
				//红包
				$data['packetmoney'] = $item['packetmoney'];
				$data['surplusmoney'] = $item['surplusmoney'];
				$data['packettotal'] = $item['packettotal'];
				$data['packetsurplus'] = $item['packetsurplus'];
				$data['grant2'] = $item['grant2'];
				$data['packettype'] = $item['packettype'];
				$data['minpacketmoney'] = $item['minpacketmoney'];

				pdo_update('ewei_shop_creditshop_goods', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
				plog('creditshop.goods.edit', "编辑积分商城商品 ID: {$id} <br/>商品名称: {$data['title']}");
			} else {
				if($data['goodstype']==3 || $data['packetmoney'] > 0 || $data['packettotal'] > 0){
					if($data['packettype']==0){
						if($data['grant2'] < 0.3){
							show_json(0,'红包最少为0.3元，请正确填写！');
						}
						if($data['packetmoney']!=$data['grant2']*$data['packettotal']){
							show_json(0,'请正确填写红包金额和数量！');
						}
					}else{
						if($data['minpacketmoney'] < 0.3){
							show_json(0,'红包随机金额最少为0.3元，请正确填写！');
						}
						if($data['packetmoney'] < $data['minpacketmoney']*$data['packettotal']){
							show_json(0,'请正确填写红包金额和数量！');
						}
                        if (intval($_GPC['goodstype']) ==3 && floatval($_GPC['minpacketmoney'])>floatval($_GPC['maxpacketmoney'])){
                            show_json(0,'红包最最大金额不得比最小金额小！');
                        }
					}
				}
				$data['surplusmoney'] = $data['packetmoney'];
				$data['packetsurplus'] = $data['packettotal'];

				pdo_insert('ewei_shop_creditshop_goods', $data);
				$id = pdo_insertid();
				plog('creditshop.goods.add', "添加积分商城商品 ID: {$id}  <br/>商品名称: {$data['title']}");
			}

			if($data['hasoption']){
				$totalstocks = 0;
				//处理商品规格
				$files = $_FILES;
				$spec_ids = $_POST['spec_id'];
				$spec_titles = $_POST['spec_title'];
				$specids = array();
				$len = count($spec_ids);
				$specids = array();
				$spec_items = array();
				for ($k = 0; $k < $len; $k++) {
					$spec_id = "";
					$get_spec_id = $spec_ids[$k];
					$a = array(
						"uniacid" => $_W['uniacid'],
						"goodsid" => $id,
						"displayorder" => $k,
						"title" => $spec_titles[$get_spec_id]
					);
					if (is_numeric($get_spec_id)) {
						pdo_update("ewei_shop_creditshop_spec", $a, array("id" => $get_spec_id));
						$spec_id = $get_spec_id;
					} else {
						pdo_insert("ewei_shop_creditshop_spec", $a);
						$spec_id = pdo_insertid();
					}
					//子项
					$spec_item_ids = $_POST["spec_item_id_" . $get_spec_id];
					$spec_item_titles = $_POST["spec_item_title_" . $get_spec_id];
					$spec_item_shows = $_POST["spec_item_show_" . $get_spec_id];
					$spec_item_thumbs = $_POST["spec_item_thumb_" . $get_spec_id];
					$spec_item_oldthumbs = $_POST["spec_item_oldthumb_" . $get_spec_id];
					$spec_item_virtuals = $_POST["spec_item_virtual_" . $get_spec_id];

					$itemlen = count($spec_item_ids);
					$itemids = array();
					for ($n = 0; $n < $itemlen; $n++) {
						$item_id = "";
						$get_item_id = $spec_item_ids[$n];
						$d = array(
							"uniacid" => $_W['uniacid'],
							"specid" => $spec_id,
							"displayorder" => $n,
							"title" => $spec_item_titles[$n],
							"show" => $spec_item_shows[$n],
							"thumb" => save_media($spec_item_thumbs[$n]),
							"virtual" => $data['type'] == 3 ? $spec_item_virtuals[$n] : 0
						);
                        //show_json(0,$get_item_id);
						$f = "spec_item_thumb_" . $get_item_id;
						if (is_numeric($get_item_id)) {
							pdo_update("ewei_shop_creditshop_spec_item", $d, array("id" => $get_item_id));
							$item_id = $get_item_id;
						} else {
							pdo_insert("ewei_shop_creditshop_spec_item", $d);
							$item_id = pdo_insertid();
						}
						$itemids[] = $item_id;
						//临时记录，用于保存规格项
						$d['get_id'] = $get_item_id;
						$d['id'] = $item_id;
						$spec_items[] = $d;
					}
					//删除其他的
					if (count($itemids) > 0) {
						pdo_query("delete from " . tablename('ewei_shop_creditshop_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id and id not in (" . implode(",", $itemids) . ")");
					} else {
						pdo_query("delete from " . tablename('ewei_shop_creditshop_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id");
					}
					//更新规格项id
					pdo_update("ewei_shop_creditshop_spec", array("content" => serialize($itemids)), array("id" => $spec_id));
					$specids[] = $spec_id;
				}
				//删除其他的
				if (count($specids) > 0) {
					pdo_query("delete from " . tablename('ewei_shop_creditshop_spec') . " where uniacid={$_W['uniacid']} and goodsid=$id and id not in (" . implode(",", $specids) . ")");
				} else {
					pdo_query("delete from " . tablename('ewei_shop_creditshop_spec') . " where uniacid={$_W['uniacid']} and goodsid=$id");
				}
				//保存规格
				$optionArray = json_decode($_POST['optionArray'],true);
				$option_idss = $optionArray['option_ids'];
				$len = count($option_idss);
				$optionids = array();
				$levelArray = array();
				for ($k = 0; $k < $len; $k++) {
					$option_id = "";
					$ids = $option_idss[$k];
					$get_option_id = $optionArray['option_id'][$k];

					$idsarr = explode("_", $ids);
					$newids = array();
					foreach ($idsarr as $key => $ida) {
						foreach ($spec_items as $it) {
							if ($it['get_id'] == $ida) {
								$newids[] = $it['id'];
								break;
							}
						}
					}
                    // asort($newids);
					$newids = implode("_", $newids);
					$a = array(
						"uniacid" => $_W['uniacid'],
						"title" => $optionArray['option_title'][$k],
						"credit" => $optionArray['option_credit'][$k],
						"money" => $optionArray['option_money'][$k],
						"total" => $optionArray['option_total'][$k],
						"weight" => $optionArray['option_weight'][$k],
						"goodssn" => $optionArray['option_goodssn'][$k],
						"productsn" => $optionArray['option_productsn'][$k],
						"goodsid" => $id,
						"specs" => $newids,
						'virtual' => $data['type'] == 3 ? $optionArray['option_virtual'][$k] : 0,
					);

					$totalstocks+=$a['total'];
					if (empty($get_option_id)) {
						pdo_insert("ewei_shop_creditshop_option", $a);
						$option_id = pdo_insertid();
					} else {
						pdo_update("ewei_shop_creditshop_option", $a, array('id' => $get_option_id));
						$option_id = $get_option_id;
					}
					$optionids[] = $option_id;
				}
				//更新最低价格，最低积分
				if (count($optionids) > 0 && $data['hasoption'] !== 0) {
					pdo_query("delete from " . tablename('ewei_shop_creditshop_option') . " where goodsid=$id and id not in ( " . implode(',', $optionids) . ")");

					//更新最低价和最高价
					$sql = "update ".tablename('ewei_shop_creditshop_goods')." g set
					g.mincredit = (select min(credit) from ".tablename('ewei_shop_creditshop_option')." where goodsid = $id),
					g.minmoney = (select min(money) from ".tablename('ewei_shop_creditshop_option')." where goodsid = $id),
					g.maxcredit = (select max(credit) from ".tablename('ewei_shop_creditshop_option')." where goodsid = $id),
					g.maxmoney = (select max(money) from ".tablename('ewei_shop_creditshop_option')." where goodsid = $id)
					where g.id = $id and g.hasoption=1";

					pdo_query($sql);
				} else {
					pdo_query("delete from " . tablename('ewei_shop_creditshop_option') . " where goodsid=$id");
					$sql = "update ".tablename('ewei_shop_creditshop_goods')." set minmoney = money,maxmoney = money,mincredit = credit,maxcredit = credit where id = $id and hasoption=0;";
					pdo_query($sql);
				}
				//总库存
				if ($data['hasoption'] !== 0) {
					pdo_update("ewei_shop_creditshop_goods", array("total" => $totalstocks), array("id" => $id));
				}
			}

			show_json(1, array('url' => webUrl('creditshop/goods/edit', array('id' => $id, 'tab' => str_replace("#tab_", "", $_GPC['tab'])))));
		}
		include $this->template();
	}

	function delete() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		if (empty($id)) {
			$id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
		}
		$items = pdo_fetchall("SELECT id,title FROM " . tablename('ewei_shop_creditshop_goods') . " WHERE id in( $id ) AND uniacid=" . $_W['uniacid']);
		foreach ($items as $item) {
			pdo_update('ewei_shop_creditshop_goods', array('deleted' => 1), array('id' =>$item['id'], 'uniacid' => $_W['uniacid']));
			plog('creditshop.goods.delete', "删除积分商城商品 ID: {$item['id']}  <br/>商品名称: {$item['title']} ");
		}
		show_json(1, array('url' => referer()));
	}
    /*修改商品状态*/
    function status() {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
        }
        $status = intval($_GPC['status']);
        $items = pdo_fetchall("SELECT id,status FROM " . tablename('ewei_shop_creditshop_goods') . " WHERE id in( $id ) AND uniacid=" . $_W['uniacid']);
        foreach ($items as $item) {
            $status_update = pdo_update('ewei_shop_creditshop_goods', array('status' => $status), array('id' => $item['id']));
            if(!$status_update){
                show_json(0,'商品状态修改失败！');
            }
            plog('creditshop.goods.edit', "修改积分商城商品 {$item['id']} <br /> 状态: " . ($status == 0 ? '下架' : '上架'));
        }
        show_json(1, array('url' => referer()));
    }
    /*
     * 彻底删除
     * */
    function deleted() {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
        }
        $items = pdo_fetchall("SELECT id,title FROM " . tablename('ewei_shop_creditshop_goods') . " WHERE id in( $id ) AND uniacid=" . $_W['uniacid']);

        foreach ($items as $item) {
            pdo_delete('ewei_shop_creditshop_goods', array('id' => $item['id']));
            plog('creditshop.goods.deleted', "从回收站彻底删除商品<br/>ID: {$item['id']}<br/>商品名称: {$item['title']}");
        }
        show_json(1, array('url' => referer()));
    }
    /*
     * 回收站恢复商品到仓库
     * */
    function recycle() {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
        }
        $items = pdo_fetchall("SELECT id,title FROM " . tablename('ewei_shop_creditshop_goods') . " WHERE id in( $id ) AND uniacid=" . $_W['uniacid']);

        foreach ($items as $item) {
            pdo_update('ewei_shop_creditshop_goods', array('status' => 0,'deleted' => 0), array('id' => $item['id']));
            plog('creditshop.goods.edit', "从回收站恢复商品<br/>ID: {$item['id']}<br/>商品名称: {$item['title']}");
        }
        show_json(1, array('url' => referer()));
    }

	function property() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$type = trim($_GPC['type']);

		$value = intval($_GPC['value']);
		if (in_array($type, array('istop', 'isrecommand', 'status', 'displayorder','title'))) {

			pdo_update("ewei_shop_creditshop_goods", array($type => $value), array("id" => $id, "uniacid" => $_W['uniacid']));
			$statusstr = "";
			if ($type == 'istop') {
				$typestr = "置顶";
				$statusstr = $value == 1 ? '置顶' : '取消置顶';
			} else if ($type == 'isrecommand') {
				$typestr = "推荐";
				$statusstr = $value == 1 ? '推荐' : '取消推荐';
			} else if ($type == 'status') {
				$typestr = "上下架";
				$statusstr = $value == 1 ? '上架' : '下架';
			} else if ($type == 'displayorder') {
				$typestr = "排序";
				$statusstr = "序号 {$value}";
			}
			plog('creditshop.goods.edit', "修改积分商城商品{$typestr}状态   ID: {$id} {$statusstr} ");
		}
		show_json(1);
	}

    /*function query(){
        global $_W, $_GPC;
        $kwd = trim($_GPC['keyword']);

        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $condition=" and status=1 and deleted=0 and uniacid=:uniacid";
        if (!empty($kwd)) {
            $condition.=" AND `title` LIKE :keywords";
            $params[':keywords'] = "%{$kwd}%";
        }

        $list = pdo_fetchall('SELECT *, money as minprice FROM ' . tablename('ewei_shop_creditshop_goods') . " WHERE 1 {$condition} order by createtime desc", $params);
        $list = set_medias($list, array('thumb'));

        include $this->template();

    }*/
    function query(){
        global $_W, $_GPC;
        $kwd = trim($_GPC['keyword']);
        $type = intval($_GPC['type']);

        $pindex = max(1, intval($_GPC['page']));
        $psize = 8;
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $condition=" and status=1 and deleted=0 and uniacid=:uniacid and type = 1 and groupstype = 0 
                    and isdiscount = 0 and istime = 0 and  ifnull(bargain,0)=0 and ispresell = 0 ";
        if (!empty($kwd)) {
            $condition.=" AND (`title` LIKE :keywords OR `keywords` LIKE :keywords)";
            $params[':keywords'] = "%{$kwd}%";
        }
        if (empty($type)) {
            $condition.=" AND `type` != 10 ";
        }else{
            $condition.=" AND `type` = :type ";
            $params[':type'] = $type;
        }
        $list = array();
        $list = pdo_fetchall('SELECT id,title,thumb,marketprice,productprice,share_title,share_icon,description,minprice,costprice,total,content,hasoption FROM ' . tablename('ewei_shop_goods') . " WHERE 1 {$condition} order by createtime desc LIMIT ". ($pindex - 1) * $psize . ',' . $psize, $params);

        $list = set_medias($list,array('thumb','share_icon'));
        foreach ($list as $key => $value){
            if(intval($value['hasoption'])==1) {

                $allspecs = pdo_fetchall("select * from " . tablename('ewei_shop_goods_spec') . " where goodsid=:id order by displayorder asc", array(":id" => $value['id']));
                foreach ($allspecs as &$s) {
                    $s['items'] = pdo_fetchall("select a.id,a.specid,a.title,a.thumb,a.show,a.displayorder,a.valueId,a.virtual,b.title as title2 from " . tablename('ewei_shop_goods_spec_item') . " a left join " . tablename('ewei_shop_virtual_type') . " b on b.id=a.virtual  where a.specid=:specid order by a.displayorder asc", array(":specid" => $s['id']));
                }
                unset($s);
                $options = pdo_fetchall("select * from " . tablename('ewei_shop_goods_option') . " where goodsid=:id order by id asc", array(':id' => $value['id']));

            }
            $list[$key]['content'] = m('common')->html_to_images($value['content']);
            $list[$key]['content'] = str_replace ("'",'"', $list[$key]['content']);
            $list[$key]['allspecs'] = $allspecs;
            $list[$key]['options'] = $options;
        }
        if($_GPC['suggest']){
            die(json_encode(array('value'=>$list)));
        }
        $total = pdo_fetchcolumn('SELECT count(1) FROM ' . tablename('ewei_shop_goods') . " WHERE 1 {$condition} ", $params);
        $pager = pagination2($total, $pindex, $psize,'',array('before' => 5, 'after' => 4, 'ajaxcallback'=>'select_page', 'callbackfuncname'=>'select_page'));
        include $this->template();

    }

    function querygoods(){
        global $_W, $_GPC;
        $kwd = trim($_GPC['keyword']);

        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $condition=" and status=1 and deleted=0 and uniacid=:uniacid";
        if (!empty($kwd)) {
            $condition.=" AND `title` LIKE :keywords";
            $params[':keywords'] = "%{$kwd}%";
        }

        $list = pdo_fetchall('SELECT *, money as minprice FROM ' . tablename('ewei_shop_creditshop_goods') . " WHERE 1 {$condition} order by createtime desc", $params);
        $list = set_medias($list, array('thumb'));

        include $this->template();

    }

}
