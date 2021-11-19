<?php defined('IN_IA') or exit('Access Denied');?>
<div class="form-group">
    <label class="col-lg control-label must">选择优惠券</label>
    <div class="col-sm-9 col-xs-12">
        <select id="couponid" name="couponid" class="form-control"  id="value_5">
            <?php  if(is_array($coupons)) { foreach($coupons as $coupon) { ?>
            <option value="<?php  echo $coupon['id'];?>" <?php  if($coupon['id']==$couponid) { ?>selected<?php  } ?> ><?php  echo $coupon['couponname'];?></option>
            <?php  } } ?>
        </select>

        <!--   <?php  echo tpl_selector('couponid',array(
             'preview'=>false,
             'text'=>'couponname',
             'url'=>webUrl('sale/coupon/query'),
             'items'=>$coupon,
             'placeholder'=>'优惠券名称',
             'buttontext'=>"选择优惠券"))
             ?>-->
    </div>
</div>

<div class="form-group">
<label class="col-lg control-label">每人发送张数</label>
<div class="col-sm-9 col-xs-12">
    <input type="text" id="send_total" id="send_total" name="send_total" class="form-control" value="1" data-rule-min='1' data-rule-required='true' />
    <span class='help-block'>此处受总数限制，如果剩余张数不足以发放给选定会员数量，则无法发放</span>
</div>
</div>


<div class='page-heading'><h2 class="form-group-title">发送对象</h2></div>

<div class="form-group">
    <label class="col-lg control-label" >发送类型</label>
    <div class="col-sm-9 col-xs-12">
        <label class="radio-inline"><input type="radio" name="send1" value="1"  checked /> 指定用户发送</label>
        <label class="radio-inline"><input type="radio" name="send1" value="2"    /> 按用户等级发送</label>
        <label class="radio-inline"><input type="radio" name="send1" value="3"  /> 按用户分组等级发送</label>
        <?php  if(p('commission')) { ?>
        <label class="radio-inline"><input type="radio" name="send1" value="5"  /> 按分销商等级发送</label>
        <?php  } ?>
        <?php  if(p('globonus')) { ?>
        <label class="radio-inline"><input type="radio" name="send1" value="6"  /> 按股东等级发送</label>
        <?php  } ?>
        <?php  if(p('abonus')) { ?>
        <label class="radio-inline"><input type="radio" name="send1" value="7"  /> 按区域代理等级发送</label>
        <?php  } ?>
        <label class="radio-inline"><input type="radio" name="send1" value="4" />全部发送</label>
    </div>
</div>
<div class="form-group choose choose_1">
    <label class="col-lg control-label" >选择会员</label>
    <div class="col-sm-9 col-xs-12">
       <span style="line-height: 35px"> 按用户发送</span>
        <textarea id="send_openid" name="send_openid" class="form-control" rows="5" placeholder="请用半角逗号隔开OPENID, 如 aaa,bbb" id="value_1"></textarea>
       <!-- <?php  echo tpl_selector('send_openid',array('key'=>'openid','text'=>'nickname', 'thumb'=>'avatar','multi'=>1,'placeholder'=>'昵称/姓名/手机号','buttontext'=>'选择通知人', 'items'=>$salers,'url'=>webUrl('member/query') ))?>-->
        <span class='help-block'>订单生成后以模板消息的方式商家通知，可以制定多个人，如果不填写则不通知</span>
    </div>
</div>

<div class="form-group choose choose_2" style='display: none' >
    <label class="col-lg control-label" >选择会员等级</label>
    <div class="col-sm-8 col-lg-9 col-xs-12">
        <select id="send_level" name="send_level" class="form-control" id="value_2" >
            <option value="">全部</option>
            <option value="0">普通等级</option>
            <?php  if(is_array($list)) { foreach($list as $type) { ?>
            <option value="<?php  echo $type['id'];?>"><?php  echo $type['levelname'];?></option>
            <?php  } } ?>
        </select>
    </div>
</div>
<div class="form-group choose choose_3" style='display:none '>
    <label class="col-lg control-label" >选择会员分组</label>

    <div class="col-sm-8 col-lg-9 col-xs-12">
        <select id="send_group" name="send_group" class="form-control"  id="value_3">
            <option value="">全部</option>
            <option value="0">无分组</option>
            <?php  if(is_array($list2)) { foreach($list2 as $type2) { ?>
            <option value="<?php  echo $type2['id'];?>"><?php  echo $type2['groupname'];?></option>
            <?php  } } ?>
        </select>
    </div>
</div>
<?php  if(p('commission')) { ?>
<div class="form-group choose choose_5" style='display:none '>
    <label class="col-lg control-label" >选择分销商等级</label>
    <div class="col-sm-8 col-lg-9 col-xs-12">
        <select id="send_agentlevel" name="send_agentlevel" class="form-control"  id="value_5">
            <option value="">全部</option>
            <option value="0"> <?php echo empty($plugin_com_set['levelname'])?'普通等级':$plugin_com_set['levelname']?></option>
            <?php  if(is_array($list3)) { foreach($list3 as $type3) { ?>
            <option value="<?php  echo $type3['id'];?>"><?php  echo $type3['levelname'];?></option>
            <?php  } } ?>
        </select>
    </div>
</div>
<?php  } ?>
<?php  if(p('globonus')) { ?>
<div class="form-group choose choose_6" style='display:none '>
    <label class="col-lg control-label" >选择股东等级</label>
    <div class="col-sm-8 col-lg-9 col-xs-12">
        <select id="send_partnerlevels" name="send_partnerlevels" class="form-control"  id="value_6">
            <option value="">全部</option>
            <option value="0"><?php echo empty($plugin_globonus_set['levelname'])?'普通等级':$plugin_globonus_set['levelname']?></option>
            <?php  if(is_array($list4)) { foreach($list4 as $type4) { ?>
            <option value="<?php  echo $type4['id'];?>"><?php  echo $type4['levelname'];?></option>
            <?php  } } ?>
        </select>
    </div>
</div>
<?php  } ?>
<?php  if(p('abonus')) { ?>
<div class="form-group choose choose_7" style='display:none '>
    <label class="col-lg control-label" >选择区域代理等级</label>
    <div class="col-sm-8 col-lg-9 col-xs-12">
        <select id="send_aagentlevels" name="send_aagentlevels" class="form-control"  id="value_7">
            <option value="">全部</option>
            <option value="0"><?php echo empty($plugin_abonus_set['levelname'])?'普通等级':$plugin_abonus_set['levelname']?></option>
            <?php  if(is_array($list5)) { foreach($list5 as $type5) { ?>
            <option value="<?php  echo $type5['id'];?>"><?php  echo $type5['levelname'];?></option>
            <?php  } } ?>
        </select>
    </div>
</div>
<?php  } ?>

<div class='page-heading'><h2 class="form-group-title">推送设置</h2></div>

<div class="form-group">
    <label class="col-lg control-label">推送模式</label>
    <div class="col-sm-9 col-xs-12">
        <label class="radio-inline coupon-radio">
            <input type="radio" name="messagetype" value="1"  checked="true"  /> 发送模板消息
        </label>
        <label class="radio-inline coupon-radio">
            <input type="radio" name="messagetype" value="2" /> 发送客服消息
        </label>
        <label class="radio-inline coupon-radio">
            <input type="radio" name="messagetype" value="0"  /> 不发送推送消息
        </label>
       <!-- <span class='help-block'>混合消息发送方式为先发送模板消息,如果发送失败再次发送客服消息</span>-->
    </div>
</div>
<!--4000097827-->