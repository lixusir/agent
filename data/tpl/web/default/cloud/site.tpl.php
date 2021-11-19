<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div>
	<div class="text-center">
		<img src="//cdn.w7.cc/web/resource/images/wechat/website-logo.png" alt="" class="" />
	</div>
	<div class="form-files-box sms-index">
		<div class="form-files">
			<div class="form-file header">基本信息</div>
			<div class="form-file">
				<div class="form-label">站点ID</div>
				<div class="form-value"><?php  echo $site_info['key'];?></div>
				<div class="form-edit"></div>
			</div>
			<div class="form-file">
				<div class="form-label">通信密钥</div>
				<div class="form-value"><?php  echo $site_info['token'];?></div>
				<div class="form-edit"></div>
			</div>
			<div class="form-file">
				<div class="form-label">服务类型</div>
				<div class="form-value">
					<?php  if($site_info['family'] == 's') { ?>
					授权版
					<?php  } else if($site_info['family'] == 'x') { ?>
					商业版
					<?php  } else if($site_info['family'] == 'l') { ?>
					单公号版
					<?php  } else { ?>
					免费版
					<a href="//s.w7.cc/goods-1.html" class="color-default" target="_blank">升级为商业服务</a>&nbsp;&nbsp;
					<a href="//s.w7.cc/goods-1.html" class="color-default" target="_blank">商业版介绍</a>
					<?php  } ?>
				</div>
				<div class="form-edit"></div>
			</div>
			<div class="form-file">
				<div class="form-label">增值服务</div>
				<div class="form-value"><?php echo empty($site_info['services'])? '未获得' : $site_info['services']?></div>
				<div class="form-edit">
					<a href="//s.w7.cc/goods-6.html" target="_blank">购买增值服务</a>
				</div>
			</div>
			<div class="form-file">
				<div class="form-label">网站名称</div>
				<div class="form-value"><?php  echo $site_info['sitename'];?></div>
				<div class="form-edit"></div>
			</div>
			<div class="form-file">
				<div class="form-label">网站URL</div>
				<div class="form-value"><?php  echo $site_info['url'];?></div>
				<div class="form-edit"></div>
			</div>
			<div class="form-file">
				<div class="form-label">网站IP</div>
				<div class="form-value"><?php  echo $site_info['ip'];?></div>
				<div class="form-edit"></div>
			</div>
		</div>
		<div class="form-files we7-margin-top">
			<div class="form-file header">绑定信息</div>
			<div class="form-file">
				<div class="form-label">商城账户</div>
				<div class="form-value"><?php  echo $site_info['username'];?></div>
				<div class="form-edit"></div>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="full_token">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h3 class="modal-title">通信密钥</h3>
			</div>
			<div class="modal-body we7-form">
				<div class="text-center"><?php  echo $site_info['full_token'];?></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
			</div>
		</div>
	</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>