<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require EWEI_SHOPV2_PLUGIN . 'abonus/core/page_login_mobile.php';
class Register_EweiShopV2Page extends AbonusMobileLoginPage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$set = set_medias($this->set, 'regbg');
		$member = m('member')->getMember($openid);

		if ($member['agentblack'] || $member['aagentblack'])
		{
			include $this->template();
			exit();
		}
		$apply_set = array();
		$apply_set['open_protocol'] = $set['open_protocol'];
		if (empty($set['applytitle'])) 
		{
			$apply_set['applytitle'] = '区域代理申请协议';
		}
		else 
		{
			$apply_set['applytitle'] = $set['applytitle'];
		}
		$template_flag = 0;
		$diyform_plugin = p('diyform');
		if ($diyform_plugin) 
		{
			$set_config = $diyform_plugin->getSet();
			$abonus_diyform_open = $set_config['abonus_diyform_open'];
			if ($abonus_diyform_open == 1) 
			{
				$template_flag = 1;
				$diyform_id = $set_config['abonus_diyform'];
				if (!(empty($diyform_id))) 
				{
					$formInfo = $diyform_plugin->getDiyformInfo($diyform_id);
					$fields = $formInfo['fields'];
					$diyform_data = iunserializer($member['diyaagentdata']);
					$f_data = $diyform_plugin->getDiyformData($diyform_data, $fields, $member);
				}
			}
		}

		$levels = $this->model->getLevels();

		if ($_W['ispost']) 
		{
			if ($set['become'] != '1') 
			{
				show_json(0, '未开启' . $set['texts']['agent'] . '注册!');
			}

			$level_id = $_GPC['level_id']?$_GPC['level_id']:show_json(0,'参数错误');

			$level = pdo_get('ewei_shop_abonus_level',array('id'=>$level_id));

            //需要支付金额
            $money = $level['money'];

            if($member['credit2'] <=0 || $member['credit2'] < $money){

                show_json(0,'余额不足,无法购买');

            }

            $province = trim(str_replace(' ', '', $_GPC['province']));
            $provinces = ((!(empty($province)) ? iserializer(array($province)) : iserializer(array())));
            $city = trim(str_replace(' ', '', $_GPC['city']));
            $citys = ((!(empty($city)) ? iserializer(array(str_replace(' ', '', $city))) : iserializer(array())));
            $area = trim(str_replace(' ', '', $_GPC['area']));
            $areas = ((!(empty($area)) ? iserializer(array($area)) : iserializer(array())));

            $where = ' uniacid=:uniacid ';

            $params = [':uniacid'=>$_W['uniacid']];

            if($_GPC['aagenttype'] == 1){

                $where .= " and aagentprovinces like :keyword ";

                $params[':keyword'] = '%'.$provinces.'%';

            }

            if($_GPC['aagenttype'] == 2){

                $where .= " and aagentcitys like :keyword ";

                $params[':keyword'] = '%'.$citys.'%';

            }

            if($_GPC['aagenttype'] == 3){

                $where .= " and aagentareas like :keyword ";

                $params[':keyword'] = '%'.$areas.'%';

            }

            $is_abonus = pdo_fetchcolumn('select count(id) from '.tablename('ewei_shop_member')." where ".$where,$params);

            if(!empty($is_abonus)){

                show_json(0,'该区域已存在代理,请选择其他地区或联系平台');
            }

            $data = array(
                'isaagent' => 1,
                'aagentstatus' => 1,
                'mobile' => trim($_GPC['mobile']),
                'weixin' => trim($_GPC['weixin']),
                'aagenttime' => 0,
                'aagentlevel'  => $level_id,
                'aagenttype' => intval($_GPC['aagenttype']),
                'aagentprovinces' => $provinces,
                'aagentcitys' => $citys,
                'aagentareas' => $areas
            );

            pdo_update('ewei_shop_member', $data, array('id' => $member['id']));

            m('member')->setCredit($member['openid'],'credit2',$money,[$_W['uid'],'申请代理消费'],400);

            if (!(empty($member['uid'])))
            {
                m('member')->mc_update($member['uid'], array('mobile' => $data['mobile']));
            }

			show_json(1);
		}

		include $this->template();
	}
}
?>