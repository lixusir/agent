<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .multi-img-details .multi-item img{height:100px;}
</style>
<div class="page-header">
    当前位置：<span class="text-primary"><?php  if(!empty($item['id'])) { ?>编辑<?php  } else { ?>添加<?php  } ?>赠品 <small><?php  if(!empty($item['id'])) { ?>修改【<?php  echo $item['title'];?>】<?php  } ?></small></span>
</div>


<div class="page-content">
    <div class="page-sub-toolbar">
        <span class="">
            <?php if(cv('sale.gift.add')) { ?>
                <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('sale/gift/add',array('type'=>$type))?>"><i class='fa fa-plus'></i> 添加赠品</a>
            <?php  } ?>
        </span>
    </div>
    <form <?php if( ce('sale.gift' ,$item) ) { ?>action="" method="post"<?php  } ?> class="form-horizontal form-validate" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
    <div class="tab-content ">
        <div class="tab-pane active">
            <div class="panel-body">

                <div class="form-group">
                    <label class="col-lg control-label">排序</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('sale.gift' ,$item) ) { ?>
                        <input type='text' class='form-control' name='displayorder' value="<?php  echo $item['displayorder'];?>" />
                        <span class="help-block">数字越大，排名越靠前,如果为空，默认排序方式为创建时间</span>
                        <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $item['displayorder'];?></div>
                        <?php  } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg control-label must">赠品标题</label>
                    <div class="col-sm-9 col-xs-12 ">
                        <?php if( ce('sale.gift' ,$item) ) { ?>
                        <input type="text" id='title' name="title" class="form-control" value="<?php  echo $item['title'];?>" data-rule-required="true"/>
                        <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $item['title'];?></div>
                        <?php  } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg control-label">活动类型</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="hidden" name="activitytype" value="<?php  echo $item['activity'];?>">
                        <?php if( ce('sale.gift' ,$item) ) { ?>
                        <label class="radio-inline">
                            <input type="radio" name="activity" value="1" <?php  if(!empty($item['id'])) { ?>disabled<?php  } ?> <?php  if(empty($item['activity']) || $item['activity'] == 1) { ?>checked="true"<?php  } ?> onclick="$('#order').show();$('#product').hide();" />
                            订单金额
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="activity" value="2" <?php  if(!empty($item['id'])) { ?>disabled<?php  } ?>  <?php  if($item['activity'] == 2) { ?>checked="true"<?php  } ?>  onclick="$('#product').show();$('#order').hide();" />
                            指定商品
                        </label>
                        <span class="help-block">赠品类型，赠品保存后无法修改，请谨慎选择</span>
                        <?php  } else { ?>
                        <div class='form-control-static'>
                            <?php  if($item['activity'] == 1) { ?>
                            订单金额
                            <?php  } else if($item['activity']==2) { ?>
                            指定商品
                            <?php  } ?></div>
                        <?php  } ?>
                    </div>
                </div>

                <div class="form-group cgt cgt-0" id="order" <?php  if($item['activity']==2) { ?>style="display:none;"<?php  } ?>>
                <label class="col-lg control-label must">订单金额</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('sale.package' ,$item) ) { ?>
                    <div class="input-group fixsingle-input-group">
                        <input type='text' class='form-control' name='orderprice' value="<?php  echo $item['orderprice'];?>" data-rule-required="true"/>
                        <span class="input-group-addon">元</span>
                    </div>
                    <span class="help-block image-block" style="display: block;">订单金额为赠品活动最低条件。</span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['orderprice'];?></div>
                    <?php  } ?>
                </div>
            </div>

            <div class="form-group" id="product" <?php  if($item['activity']==1 || empty($item['activity'])) { ?>style="display:none;"<?php  } ?>>
            <label class="col-lg control-label">指定商品</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('sale.package' ,$item) ) { ?>
                <div class="input-group">
                    <input type="text" id="goodsid_text" name="goodsid_text" value="" class="form-control text" readonly="">
                    <div class="input-group-btn">
                        <button class="btn btn-primary select_goods" type="button">选择商品</button>
                    </div>
                </div>
                <div class="input-group multi-img-details container ui-sortable goods_show">
                    <?php  if(!empty($goods)) { ?>
                    <?php  if(is_array($goods)) { foreach($goods as $g) { ?>
                    <div class="multi-item" data-id="<?php  echo $g['id'];?>" data-name="goodsid" id="<?php  echo $g['id'];?>">
                        <img class="img-responsive img-thumbnail" src="<?php  echo tomedia($g['thumb'])?>" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'" style="width:100px;height:100px;">
                        <div class="img-nickname"><?php  echo $g['title'];?></div>
                        <input type="hidden" value="<?php  echo $g['id'];?>" name="goodsid[]">
                        <em onclick="removeHtml(<?php  echo $g['id'];?>)" class="close">×</em>
                        <div style="clear:both;"></div>
                    </div>
                    <?php  } } ?>
                    <?php  } ?>
                </div>
                <script>
                    $(function(){
                        var title = '';
                        $('.img-nickname').each(function(){
                            title += $(this).html()+';';
                        });
                        $('#goodsid_text').val(title);
                    })
                    myrequire(['web/goods_selector'],function (Gselector) {
                        $('.select_goods').click(function () {
                            var ids = select_goods_ids();
                            Gselector.open('goods_show','',0,true,'',ids);
                        });
                    })
                    function goods_show(data) {
//                        console.log(data);
                        if(data.act == 1){
                            var html = '<div class="multi-item" data-id="'+data.id+'" data-name="goodsid" id="'+data.id+'">'
                                +'<img class="img-responsive img-thumbnail" src="'+data.thumb+'" onerror="this.src=\'../addons/ewei_shopv2/static/images/nopic.png\'" style="width:100px;height:100px;">'
                                +'<div class="img-nickname">'+data.title+'</div>'
                                +'<input type="hidden" value="'+data.id+'" name="goodsid[]">'
                                +'<em onclick="removeHtml('+data.id+')" class="close">×</em>'
                                +'</div>';

                            $('.goods_show').append(html);
                            var title = '';
                            $('.img-nickname').each(function(){
                                title += $(this).html()+';';
                            });
                            $('#goodsid_text').val(title);
                        }else if(data.act == 0){
                            removeHtml(data.id);
                        }

                    }
                    function removeHtml(id){
                        $("[id='"+id+"']").remove();
                        var title = '';
                        $('.img-nickname').each(function(){
                            title += $(this).html()+';';
                        });
                        $('#goodsid_text').val(title);
                    }
                    function select_goods_ids(){
                        var goodsids = [];
                        $(".multi-item").each(function(){
                            goodsids.push($(this).attr('data-id'));
                        });
                        return goodsids;
                    }
                </script>
                <?php  } else { ?>
                <div class="input-group multi-img-details container ui-sortable">
                    <?php  if(is_array($goods)) { foreach($goods as $item) { ?>
                    <div data-name="goodsid" data-id="<?php  echo $item['id'];?>" class="multi-item">
                        <img src="<?php  echo tomedia($item['thumb'])?>" class="img-responsive img-thumbnail">
                        <div class="img-nickname"><?php  echo $item['title'];?></div>
                    </div>
                    <?php  } } ?>
                </div>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">赠品缩略图</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('sale.gift' ,$item) ) { ?>
                <?php  echo tpl_form_field_image2('thumb', $item['thumb']);?>
                <span class="help-block image-block" style="display: block;">建议为正方型图片，尺寸建议宽度为640，如果不上传默认为第一件赠品缩略图。</span>
                <?php  } else { ?>
                <input type="hidden" name="thumb" value="<?php  echo $item['thumb'];?>" />
                <div class='form-control-static'><?php  echo $item['thumb'];?></div>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">选择赠品</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('goods' ,$item) ) { ?>
                <?php  echo tpl_selector('giftgoodsid',
                        array('multi'=>1,
                'type'=>'image',
                'placeholder'=>'自定义赠品名称',
                'buttontext'=>'选择赠品',
                'items'=>$gift,
                'nokeywords'=>1,
                'autosearch'=>1,
                'url'=>webUrl('sale/gift/querygift')))?>
                <span class="help-block image-block" style="display: block;">选择指定的商品参加赠品活动。</span>
                <?php  } else { ?>
                <?php  if(is_array($gift)) { foreach($gift as $item) { ?>
                <a href='<?php  echo tomedia($item["thumb"])?>' target='_blank'>
                    <img src="<?php  echo tomedia($item['thumb'])?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
                </a>
                <?php  } } ?>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">限时设置</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('sale.gift' ,$item) ) { ?>
                <div class="input-group">
                    <span class="input-group-addon">开始时间</span>
                    <?php echo tpl_form_field_date('starttime', !empty($item['starttime']) ? date('Y-m-d H:i',$item['starttime']) :date('Y-m-d H:i'), 1)?>
                    <span class="input-group-addon">结束时间</span>
                    <?php echo tpl_form_field_date('endtime', !empty($item['endtime']) ? date('Y-m-d H:i',$item['endtime']) :date('Y-m-d H:i'), 1)?>
                </div>
                <?php  } else { ?>
                <div class='form-control-static'><?php  echo date('Y-m-d H:i',$item['starttime'])?> - <?php  echo date('Y-m-d H:i',$item['endtime'])?></div>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">分享标题</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('sale.gift' ,$item) ) { ?>
                <input type="text" name="share_title" id="share_title" class="form-control" value="<?php  echo $item['share_title'];?>" />
                <span class='help-block'>如果不填写，默认为赠品名称</span>
                <?php  } else { ?>
                <div class='form-control-static'><?php  echo $item['share_title'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">分享图标</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('sale.gift' ,$item) ) { ?>
                <?php  echo tpl_form_field_image2('share_icon', $item['share_icon'])?>
                <span class='help-block'>如果不选择，默认为赠品缩略图片</span>
                <?php  } else { ?>
                <?php  if(!empty($item['share_icon'])) { ?>
                <a href='<?php  echo tomedia($item['share_icon'])?>' target='_blank'>
                <img src="<?php  echo tomedia($item['share_icon'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
                </a>
                <?php  } ?>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">分享描述</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('sale.gift' ,$item) ) { ?>
                <textarea name="share_desc" class="form-control" rows="5" ><?php  echo $item['share_desc'];?></textarea>
                <span class='help-block'>如果不填写，则使用店铺名称</span>
                <?php  } else { ?>
                <div class='form-control-static'><?php  echo $item['share_desc'];?></div>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">状态</label>
            <div class="col-xs-12 col-sm-8">
                <div class="input-group">
                    <?php if( ce('sale.gift' ,$item) ) { ?>
                    <label class="radio radio-inline">
                        <input type="radio" name="status" value="1" <?php  if(intval($item['status']) ==1 ) { ?>checked="checked"<?php  } ?>> 开启
                    </label>
                    <label class="radio radio-inline">
                        <input type="radio" name="status" value="0" <?php  if(intval($item['status']) ==0) { ?>checked="checked"<?php  } ?>> 关闭
                    </label>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  if(intval($item['status']) ==1 ) { ?>开启<?php  } else { ?>关闭<?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>
        </div>

    </div>
</div>

</div>

<?php if( ce('sale.gift' ,$item) ) { ?>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
        <input type="submit"  value="提交" class="btn btn-primary" />
        <a class="btn btn-default" href="<?php  echo webUrl('sale/gift',array('type'=>$type))?>">返回列表</a>
    </div>
</div>
<?php  } ?>

</form>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>