<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style>
    .popover{
        width:170px;
        font-size:12px;
        line-height: 21px;
        color: #0d0706;
    }
    .popover span{
        color: #b9b9b9;
    }
    .nickname{
        display: inline-block;
        max-width:200px;
        overflow : hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
    .tooltip-inner{
        border:none;
    }
    .page-content{
        overflow: visible;
    }
</style>
<div class="page-header"> 当前位置：<span class="text-primary">代理商管理</span> </div>
<div class="page-content">
   <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="ewei_shopv2" />
            <input type="hidden" name="do" value="web" />
            <input type="hidden" name="r" value="abonus.agent" />
            
<div class="page-toolbar  m-b-sm m-t-sm">
                            <div class="col-sm-4">

                                <?php  echo tpl_daterange('time', array('sm'=>true, 'placeholder'=>'成为区域代理时间'),true);?>
                               </div>
			 
                            <div class="col-sm-8 pull-right">
                                <div class="input-group">
                                        <div class="input-group-select">
                                            <select name='aagentstatus' class='form-control  input-sm select-md' style="width:100px;"  >
                                                <option value=''>状态</option>
                                                <option value='0' <?php  if($_GPC['aagentstatus']=='0') { ?>selected<?php  } ?>>未审核</option>
                                                <option value='1' <?php  if($_GPC['aagentstatus']=='1') { ?>selected<?php  } ?>>已审核</option>
                                            </select>
                                        </div>
                                    <div class="input-group-select">
                                        <select name='followed' class='form-control  input-sm select-md' style="width:140px">
                                            <option value=''>关注</option>
                                            <option value='0' <?php  if($_GPC['followed']=='0') { ?>selected<?php  } ?>>未关注</option>
                                            <option value='1' <?php  if($_GPC['followed']=='1') { ?>selected<?php  } ?>>已关注</option>
                                            <option value='2' <?php  if($_GPC['followed']=='2') { ?>selected<?php  } ?>>取消关注</option>
                                        </select>
                                    </div>
                                    <div class="input-group-select">
                                        <select name='aagenttype' class='form-control  input-sm select-md' style="width:140px;"  >
                                            <option value=''>类型</option>
                                            <option value='1' <?php  if($_GPC['aagenttype']==1) { ?>selected<?php  } ?>>省级代理</option>
                                            <option value='2' <?php  if($_GPC['aagenttype']==2) { ?>selected<?php  } ?>>市级代理</option>
                                            <option value='3' <?php  if($_GPC['aagenttype']==3) { ?>selected<?php  } ?>>区级代理</option>
                                        </select>
                                    </div>
                                    <div class="input-group-select">
                                        <select name='aagentblack'  class='form-control  input-sm select-md'   style="width:140px;"  >
                                            <option value=''>黑名单</option>
                                            <option value='0' <?php  if($_GPC['aagentblack']=='0') { ?>selected<?php  } ?>>否</option>
                                            <option value='1' <?php  if($_GPC['aagentblack']=='1') { ?>selected<?php  } ?>>是</option>
                                        </select>
                                    </div>
                                          <input type="text" class="form-control input-sm"  name="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="区域代理昵称/姓名/手机号"/>
                                         <span class="input-group-btn">

                                                                <button class="btn btn-primary" type="submit"> 搜索</button>
                                                                                                                <?php if(cv('abonus.agent.export')) { ?>
                                                <button type="submit" name="export" value="1" class="btn btn-success">导出</button>
                                                <?php  } ?>
                                        </span>
                                </div>
								
                            </div>
</div>
  </form>
<?php  if(count($list)>0) { ?>
        <div class="page-table-header">
            <input type="checkbox">
           <div class="btn-group">
               <?php if(cv('abonus.agent.edit')) { ?>
               <a class='btn btn-default btn-operation' data-toggle='batch' data-href="<?php  echo webUrl('abonus/agent/aagentblack',array('aagentblack'=>1))?>" data-confirm='确认要设置黑名单?'>
                   <i class="icow icow-heimingdan2"></i>  设置黑名单</a>
               <a class='btn btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('abonus/agent/aagentblack',array('aagentblack'=>0))?>" data-confirm='确认要取消黑名单?'>
                   <i class="icow icow-yongxinyonghu"></i> 取消黑名单</a>
               <a class='btn btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('abonus/agent/check',array('status'=>1))?>"  data-confirm='确认要审核通过?' disabled>
                   <i class="icow icow-shenhetongguo"></i>审核通过</a>
               <a class='btn btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('abonus/agent/check',array('status'=>0))?>" data-confirm='确认要取消审核?' disabled>
                   <i class="icow icow-yiquxiao"></i> 取消审核</a>
               <?php  } ?>
               <?php if(cv('abonus.agent.delete')) { ?>
               <a class="btn btn-default btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('abonus/agent/delete')?>">
                   <i class='icow icow-shanchu1'></i> 删除</a>
               <?php  } ?>
           </div>
        </div>
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
            <tr>
                 <th style="width:45px;"></th>
                <th style="width: 250px;">粉丝</th>
                <th >姓名<br/>手机号码</th>
                <th>等级</th>
                <th >累计分红</th>
                <th >注册时间</br>审核时间</th>
                <!--<th style='width:90px;'></th>-->
                <th style='width:90px;'>状态</th>
                <th style='width:150px;'>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  if(is_array($list)) { foreach($list as $row) { ?>
           <tr>
               <td>
                        <input type='checkbox'   value="<?php  echo $row['id'];?>"/>
                </td>
               
                <td style="overflow: visible">
                    <div  rel="pop" style="display: flex"  data-content="
                    <span>ID：</span><?php  echo $row['id'];?></br>
                   <span>推荐人：</span>
                    <?php  if(empty($row['agentid'])) { ?>
				        <?php  if($row['isagent']==1) { ?>
				        总店
				         <?php  } else { ?>
				       暂无
				         <?php  } ?>
				    <?php  } else { ?>
                    	<?php  if(!empty($row['parentavatar'])) { ?>
                         <img src='<?php  echo $row['parentavatar'];?>' style='width:20px;height:20px;padding1px;border:1px solid #ccc' onerror='this.src='../addons/ewei_shopv2/static/images/nopic.png''/>
                         <?php  } ?>
                        [<?php  echo $row['agentid'];?>]<?php  if(empty($row['parentname'])) { ?>未更新<?php  } else { ?><?php  echo $row['parentname'];?><?php  } ?>
					   <?php  } ?></br>
                        <span> 是否关注：</span>
                         <?php  if(empty($row['followed'])) { ?>
                    <?php  if(empty($row['uid'])) { ?>
                    <span class='text-default'>未关注</span>
                    <?php  } else { ?>
                    <span class='text-default'>取消关注</span>
                    <?php  } ?>
                    <?php  } else { ?>
                    <span class='text-default'>已关注</span>
                    <?php  } ?>
					   ">

                        <?php if(cv('member.list.view')) { ?>
                        <a href="<?php  echo webUrl('member/list/detail',array('id' => $row['id']));?>" title='会员信息' target='_blank' style="display: flex;">
                            <?php  if(!empty($row['avatar'])) { ?>
                            <img class="radius50" src='<?php  echo $row['avatar'];?>' style='width:36px;height:36px;padding1px;border:1px solid #ccc;vertical-align: middle' onerror="this.src='../addons/ewei_shopv2/static/images/noface.png'" />
                            <?php  } ?>
                       <span style="display: flex;flex-direction: column;justify-content: center;align-items: flex-start;padding-left: 5px">
                           <span class="nickname">
                                <?php  if($row['aagenttype']==1) { ?>
                                <span class="label label-primary">省级</span>
                                <?php  } else if($row['aagenttype']==2) { ?>
                                <span class="label label-success">市级</span>
                                <?php  } else if($row['aagenttype']==3) { ?>
                                <span class="label label-warning">区级</span>
                                <?php  } ?>
                               <?php  if(empty($row['nickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?>
                           </span>
                         <?php  if($row['aagentblack']==1) { ?><span class="text-danger">黑名单<i class="icow icow-heimingdan1"style="color: #db2228;margin-left: 2px;font-size: 13px;"></i></span><?php  } ?>
                       </span>
                        </a>
                        <?php  } else { ?>
                        <?php  if(!empty($row['avatar'])) { ?>
                        <img src='<?php  echo $row['avatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
                        <?php  } ?>
                       <span style="display: flex;flex-direction: column;justify-content: center;align-items: flex-start;padding-left: 5px">
                           <span class="nickname">
                               <?php  if(empty($row['nickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?>
                           </span>
                         <?php  if($row['aagentblack']==1) { ?><span class="text-danger">黑名单<i class="icow icow-heimingdan1"style="color: #db2228;margin-left: 2px;font-size: 13px;"></i></span><?php  } ?>
                       </span>
                        <?php  } ?>

                    </div>
                </td>

                <td><?php  echo $row['realname'];?> <br/> <?php  echo $row['mobile'];?></td>
                <td><?php  if(empty($row['levelname'])) { ?> <?php echo empty($this->set['levelname'])?'普通等级':$this->set['levelname']?><?php  } else { ?><?php  echo $row['levelname'];?><?php  } ?></td>
             
                <td class="text-warning"><?php  echo $row['bonus'];?></td>
                       <td><?php  echo date('Y-m-d',$row['createtime'])?> <?php  echo date('H:i',$row['createtime'])?>
                           <br/>
                           <?php  if(!empty($row['aagenttime'])) { ?>
                           <?php  echo date('Y-m-d',$row['aagenttime'])?> <?php  echo date('H:i',$row['aagenttime'])?>
                           <?php  } else { ?>
                           -
                           <?php  } ?>
                       </td>
                <td>
             
                   
                    <span class='label <?php  if($row['aagentstatus']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
										  <?php if(cv('abonus.agent.check')) { ?>
										  data-toggle='ajaxSwitch' 
                                          data-confirm ='确认要<?php  if($row['aagentstatus']==1) { ?>取消审核<?php  } else { ?>审核通过<?php  } ?>?'
										  data-switch-value='<?php  echo $row['aagentstatus'];?>'
										  data-switch-value0='0|未审核|label label-default|<?php  echo webUrl('abonus/agent/check',array('status'=>1,'id'=>$row['id']))?>'
										  data-switch-value1='1|已审核|label label-primary|<?php  echo webUrl('abonus/agent/check',array('status'=>0,'id'=>$row['id']))?>'
										  <?php  } ?>
										>
										  <?php  if($row['aagentstatus']==1) { ?>已审核<?php  } else { ?>未审核<?php  } ?></span>
                    <br/>
                    
                    
                       <!--<span class='label <?php  if($row['aagentblack']==0) { ?>label-success<?php  } else { ?>label-default<?php  } ?>' -->
										  <?php if(cv('abonus.agent.aagentblack')) { ?>
										  <!--data-toggle='ajaxSwitch' -->
                                                                                       <!--data-confirm ='确认要<?php  if($row['aagentblack']==1) { ?>取消黑名单<?php  } else { ?>设置黑名单<?php  } ?>?'-->
										  <!--data-switch-value='<?php  echo $row['aagentblack'];?>'-->
										  <!--data-switch-value0='0|正常|label label-success|<?php  echo webUrl('abonus/agent/aagentblack',array('aagentblack'=>1,'id'=>$row['id']))?>'-->
										  <!--data-switch-value1='1|黑名单|label label-default|<?php  echo webUrl('abonus/agent/aagentblack',array('aagentblack'=>0,'id'=>$row['id']))?>'-->
										  <?php  } ?>
										<!--&gt;-->
										  <!--<?php  if($row['aagentblack']==1) { ?>黑名单<?php  } else { ?>正常<?php  } ?></span>-->
                     </td>
             
        
                <td  style="overflow:visible;">

                    <div class="btn-group ">
                            <?php if(cv('order.list')) { ?>
                            <a class="btn  btn-op btn-operation" href="<?php  echo webUrl('order/list',array('agentid' => $row['id']));?>"  target='_blank'>
                                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="推广订单">
                                            <i class='icow icow-tuiguang'></i>
                                        </span>
                            </a>
                            <?php  } ?>
                            <?php if(cv('commission.agent.user')) { ?>
                                    <a class="btn  btn-op btn-operation" href="<?php  echo webUrl('commission/agent/user',array('id' => $row['id']));?>"   target='_blank'>
                                         <span data-toggle="tooltip" data-placement="top" title="" data-original-title="推广下线">
                                           <i class='icow icow-cengjiguanli'></i>
                                        </span>
                                    </a>
                            <?php  } ?>
                            <?php if(cv('order')) { ?>
                                <a class="btn  btn-op btn-operation" href="<?php  echo webUrl('order/list', array('searchfield'=>'member', 'keyword'=>$row['nickname']))?>"  target='_blank'>
                                             <span data-toggle="tooltip" data-placement="top" title="" data-original-title="会员订单">
                                                <i class='icow icow-dingdan2'></i>
                                            </span>
                                </a>
                            <?php  } ?>
                            <?php if(cv('finance.recharge.credit1|finance.recharge.credit2')) { ?>
                                <a class="btn  btn-op btn-operation" data-toggle="ajaxModal" href="<?php  echo webUrl('finance/recharge', array('type'=>'credit1','id'=>$row['id']))?>" >
                                            <span data-toggle="tooltip" data-placement="top" title="" data-original-title="充值">
                                               <i class='icow icow-31'></i>
                                            </span>
                                </a>
                            <?php  } ?>
                            <?php if(cv('abonus.agent.delete')) { ?>
                            <a class="btn  btn-op btn-operation" data-toggle='ajaxRemove' href="<?php  echo webUrl('abonus/agent/delete',array('id' => $row['id']));?>" data-confirm="确定要删除该区域代理吗？">
                                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除区域代理">
                                               <i class='icow icow-shanchu1'></i>
                                            </span>
                            </a>
                            <?php  } ?>
                    </div>


                </td>
            </tr>
            <?php  } } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><input type="checkbox"></td>
                    <td colspan="4">
                        <div class="btn-group">
                            <?php if(cv('abonus.agent.edit')) { ?>
                            <a class='btn btn-default btn-operation' data-toggle='batch' data-href="<?php  echo webUrl('abonus/agent/aagentblack',array('aagentblack'=>1))?>" data-confirm='确认要设置黑名单?'>
                                <i class="icow icow-heimingdan2"></i>  设置黑名单</a>
                            <a class='btn btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('abonus/agent/aagentblack',array('aagentblack'=>0))?>" data-confirm='确认要取消黑名单?'>
                                <i class="icow icow-yongxinyonghu"></i> 取消黑名单</a>
                            <a class='btn btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('abonus/agent/check',array('status'=>1))?>"  data-confirm='确认要审核通过?' disabled>
                                <i class="icow icow-shenhetongguo"></i>审核通过</a>
                            <a class='btn btn-default btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('abonus/agent/check',array('status'=>0))?>" data-confirm='确认要取消审核?' disabled>
                                <i class="icow icow-yiquxiao"></i> 取消审核</a>
                            <?php  } ?>
                            <?php if(cv('abonus.agent.delete')) { ?>
                            <a class="btn btn-default btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('abonus/agent/delete')?>">
                                <i class='icow icow-shanchu1'></i> 删除</a>
                            <?php  } ?>
                        </div>
                    </td>
                    <td colspan="3" class="text-right"> <?php  echo $pager;?></td>
                </tr>
            </tfoot>
        </table>

        
                <?php  } else { ?>
<div class='panel panel-default'>
	<div class='panel-body' style='text-align: center;padding:30px;'>
		 暂时没有任何区域代理!
	</div>
</div>
<?php  } ?>
</div>
    <script language="javascript">
			require(['bootstrap'],function(){
        $("[rel=pop]").popover({
            trigger:'manual',
            placement : 'right',
            title : $(this).data('title'),
            html: 'true', 
            content : $(this).data('content'),
            animation: false
        }).on("mouseenter", function () {
                    var _this = this;
                    $(this).popover("show"); 
                    $(this).siblings(".popover").on("mouseleave", function () {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function () {
                    var _this = this;
                    setTimeout(function () {
                        if (!$(".popover:hover").length) {
                            $(_this).popover("hide")
                        }
                    }, 100);
                });
 
	 
	   });
 
			   
</script> 
 
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--913702023503242914-->