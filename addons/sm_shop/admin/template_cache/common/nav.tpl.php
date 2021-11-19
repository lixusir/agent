<?php defined('IN_IA') or exit('Access Denied');?><nav id="column-left" class="active">
    <div id="navigation"><span class="fa fa-bars"></span> 功能导航</div>
    <ul id="menu" class="in">
        <li id="menu-dashboard" class="hide">
            <a href="#/"><i class="fa fa-dashboard fw"></i> 管理首页</a>
        </li>
        <li id="menu-catalog">
            <a href="#collapse1" data-toggle="collapse" aria-expanded="true" class="parent <?php if(strpos($this->route,'category')===false && strpos($this->route,'goods')===false) { ?> collapsed <?php } ?>"><i class="fa fa-tags fw"></i> 商品目录</a>
            <ul id="collapse1" class="collapse <?php if(strpos($this->route,'category.')!==false || strpos($this->route,'goods')!==false || strpos($this->route,'brand')!==false) { ?> in<?php } ?>">
                <li class="<?php if(strpos($this->route,'category.page_list')!==false) { ?> active <?php } ?> ">
                <a href="<?php echo $this->url_pre .'&r=category.page_list'?>">商品分类</a></li>
                <li class="<?php if(strpos($this->route,'brand')!==false) { ?> active <?php } ?> ">
                    <a href="<?php echo $this->url_pre .'&r=brand.page_list'?>">品牌</a></li>
                <li class="<?php if(strpos($this->route,'goods.')!==false) { ?> active <?php } ?> ">
                    <a href="<?php echo $this->url_pre .'&r=goods.page_list'?>">商品管理</a>
                </li>
                <li class="<?php if(strpos($this->route,'goodsComment')!==false) { ?> active <?php } ?> ">
                    <a href="<?php echo $this->url_pre .'&r=goodsComment.page_list'?>">评论管理</a>
                </li>
            </ul>
        </li>
        <li id="menu-sale">
            <a href="#sale" data-toggle="collapse" class="parent <?php if(strpos($this->route,'order')===false) { ?> collapsed <?php } ?>">
                <i class="fa fa-shopping-cart fw"></i> 订单销售</a>
            <ul id="sale" class="collapse <?php if(strpos($this->route,'order')!==false) { ?> in <?php } ?>">
                <li <?php if(strpos($this->route,'order')!==false) { ?> active <?php } ?>><a href="<?php echo $this->url_pre .'&r=order.page_list'?>">订单管理</a></li>
            </ul>
        </li>
        <li id="menu-payment" class="hide">
            <a href="#payment" data-toggle="collapse" class="parent collapsed">
                <i class="fa fa-paypal fw"></i>支付管理</a>
            <ul id="payment" class="collapse">
                <li>
                    <a href="#/payment/payment">支付分类</a>
                </li>
            </ul>
        </li>
        <li id="menu-system" >
            <a href="#system" data-toggle="collapse" class="parent <?php if(strpos($this->route,'setting')===false) { ?> collapsed <?php } ?>">
                <i class="fa fa-cog fw"></i> 系统设置</a>
            <ul id="system" class="collapse <?php if(strpos($this->route,'setting')!==false) { ?> in <?php } ?>">
                <li class="<?php if(strpos($this->route,'setting')!==false) { ?> active <?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=setting.page_list'?>">网店设置</a>
                </li>
            </ul>
        </li>

        <li id="menu-design" >
            <a href="#design" data-toggle="collapse" class="parent <?php if(strpos($this->route,'banner')===false && strpos($this->route,'design.')===false) { ?> collapsed <?php } ?>">
                <i class="fa fa-television fw"></i>
                页面设计
            </a>
            <ul id="design" class="collapse <?php if(strpos($this->route,'banner')!==false || strpos($this->route,'design.')!==false) { ?> in <?php } ?>">
                <li class="<?php if(strpos($this->route,'banner')!==false) { ?> active <?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=banner.page_list'?>">横幅管理</a>
                </li>
                <li class="<?php if(strpos($this->route,'design.customerCenter.page_list')!==false) { ?> active <?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=design.customerCenter.page_list'?>">用户中心设置</a>
                </li>
            </ul>
        </li>

        <li id="menu-function" >
            <a href="#function" data-toggle="collapse" class="parent <?php if(strpos($this->route,'recommend')===false && strpos($this->route,'poster')===false && strpos($this->route,'pickup')===false) { ?> collapsed <?php } ?>">
                <i class="fa fa-cube fw"></i>
                模块管理
            </a>
            <ul id="function" class="collapse <?php if(strpos($this->route,'recommend')!==false || strpos($this->route,'poster')!==false || strpos($this->route,'pickup')!==false) { ?> in <?php } ?>">
                <li class="<?php if(strpos($this->route,'recommend.page_list')!==false) { ?>active<?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=recommend.page_list'?>">商品推荐</a>
                </li>
                <li class="<?php if(strpos($this->route,'recommend.category')!==false) { ?>active<?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=recommend.category'?>">分类推荐</a>
                </li>
                <li class="<?php if(strpos($this->route,'poster')!==false) { ?>active<?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=poster.page_list'?>">海报</a>
                </li>
                <li class="<?php if(strpos($this->route,'pickup')!==false) { ?>active<?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=pickup.page_list'?>">订单自提点</a>
                </li>
            </ul>
        </li>

        <li id="menu-customer">
            <a href="#collapse7" data-toggle="collapse" class="parent <?php if(strpos($this->route,'customer')===false) { ?> collapsed <?php } ?>">
                <i class="fa fa-user fw"></i> 客户管理
            </a>
            <ul id="collapse7" class="collapse <?php if(strpos($this->route,'customer.')!==false) { ?> in <?php } ?>">
                <li class="<?php if(strpos($this->route,'customer.')!==false) { ?> active <?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=customer.page_list'?>">客户列表</a>
                </li>
                <li class="<?php if(strpos($this->route,'customerGroup.')!==false) { ?> active <?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=customerGroup.page_list'?>">客户群组</a>
                </li>
            </ul>
        </li>
        <li id="menu-marketing" class="hide">
            <a href="#marketing" data-toggle="collapse" class="parent <?php if(strpos($this->route,'marketing')===false) { ?> collapsed <?php } ?>">
                <i class="fa fa-user fw"></i> 营销推广
            </a>

            <ul id="marketing" class="collapse <?php if(strpos($this->route,'marketing')!==false) { ?> in <?php } ?>">
                <li class="<?php if(strpos($this->route,'marketing.huodong')!==false) { ?> active <?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=huodong.page_list'?>">活动</a>
                </li>
            </ul>

        </li>
        <li id="menu-plugin" >
            <a href="#plugin" data-toggle="collapse"
               class="parent <?php if(strpos($this->route,'plugin')===false) { ?> collapsed <?php } ?>">
<!--                <i class="fa fa-user fw"></i>-->
                <i class="el-icon-s-tools"></i>
                插件
            </a>

            <ul id="plugin"
                class="collapse <?php if(strpos($this->route,'plugin')!==false) { ?> in <?php } ?>">
                <li class="<?php if(strpos($this->route,'plugin.')!==false) { ?> active <?php } ?>">
                    <a href="<?php echo $this->url_pre .'&r=plugin.page_list'?>">插件列表</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>