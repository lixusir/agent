<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="notice-show">
	<?php  if($do == 'list') { ?>
	<ul class="we7-page-tab">
		<li >
			<a href="<?php  echo url('article/notice-news/list');?>" >全部</a>
		</li>
		<li>
			<a href="<?php  echo url('article/news-show/list');?>" >新闻</a>
		</li>
		<li >
			<a href="<?php  echo url('article/notice-show/list');?>" >公告</a>
		</li>
	</ul>
	<div class="search-box we7-margin-bottom">
		<select name="" class="we7-margin-right">
			<option data-url="<?php  echo url('article/notice-show/list');?>" >全部公告</option>
			<?php  if(is_array($categroys)) { foreach($categroys as $key => $categroy) { ?>
				<?php  if($key) { ?>
				<option data-url="<?php  echo url('article/notice-show/list', array('cateid' => $categroy['id']));?>" <?php  if($cateid == $categroy['id']) { ?> selected<?php  } ?>><?php  echo $categroy['title'];?></option>
				<?php  } ?>
			<?php  } } ?>
		</select>
	</div>
	<table class="tbale we7-table">
		<tr >
			<th>名称</th>
			<th>类型</th>
			<th>发布时间</th>
		</tr>
		<?php  if(!empty($data)) { ?>
		<?php  if(is_array($data)) { foreach($data as $da) { ?>
		<tr>
			<td>
				<a href="<?php  echo url('article/notice-show/detail', array('id' => $da['id']));?>" target="_blank" class="text-over" style="<?php  if(!empty($da['style'])) { ?><?php  if(!empty($da['style']['color'])) { ?>color: <?php  echo $da['style']['color']?>;<?php  } ?><?php  if(!empty($da['style']['bold'])) { ?>font-weight:bold;<?php  } ?><?php  } ?>"><?php  echo $da['title'];?></a>
			</td>
			<td>公告</td>
			<td><?php  echo $da['createtime'];?></td>
		</tr>
		<?php  } } ?>
		<?php  } else { ?>
		<tr>
			<td colspan="3">
				<div class="we7-empty-block">暂无数据</div>
			</td>
		</tr>
		<?php  } ?>
	</table>
	<div class="pull-right">
		<?php  echo $pager;?>
	</div>
	<?php  } ?>
	<?php  if($do == 'detail') { ?>
	<div class="">
		<ol class="breadcrumb we7-breadcrumb container">
			<a href="<?php  echo url('article/notice-show/list');?>"><i class="wi wi-back-circle"></i></a>
			<li class="active"><a href="<?php  echo url('article/notice-show/list');?>">公告列表</a></li>
			<li class="active"><?php  echo $notice['title'];?></li>
		</ol>
		<div class="container">
			<div class="article-box">
				<h2 class="title">
					<?php  echo $notice['title'];?>
				</h2>
				<div class="info">
					<!-- <span>作者：<?php  echo $notice['author'];?></span>
					<span>来源：<?php  echo $notice['source'];?></span> -->
					<span>时间：<?php  echo date('Y-m-d H:i', $notice['createtime']);?></span>
					<span>阅读：<?php  echo $notice['click'];?>次</span>
				</div>
				<div class="article">
					<?php  echo html_entity_decode($notice['content'], ENT_QUOTES)?>
				</div>
				<?php  if($comment_status) { ?>
				<form class="we7-margin-top" action="" method="post">
					<div class="form-group">
						<textarea name="content" class="form-control" rows="5" placeholder="扯淡、吐槽、表扬、鼓励......想说啥就说啥！"></textarea>
					</div>
					<div class="form-group">
						<input name="submit" type="submit" value="发表评论" class="btn btn-primary"/>
						<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
					</div>
				</form>
				<div class="panel we7-panel">
					<div class="panel-heading">
						最新评论
					</div>
					<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('article/comments', TEMPLATE_INCLUDEPATH)) : (include template('article/comments', TEMPLATE_INCLUDEPATH));?>
				</div>
				<?php  } ?>
			</div>
		</div>
	</div>
	<?php  } ?>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
