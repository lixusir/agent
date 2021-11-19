<?php defined('IN_IA') or exit('Access Denied');?><div class="copyright" style="text-align: center;line-height: 50px;" ><?php if(empty($_W['setting']['copyright']['footerleft'])) { ?>Powered by <a href="http://www.we7.cc"><b>微擎</b></a> v<?php echo IMS_VERSION;?>  2014-2015 <a href="http://www.we7.cc">www.we7.cc</a><?php } else { ?><?php echo $_W['setting']['copyright']['footerleft'];?><?php } ?></div>
<?php if(!empty($_W['setting']['copyright']['icp'])) { ?><div>备案号：<a href="http://www.miitbeian.gov.cn" target="_blank"><?php echo $_W['setting']['copyright']['icp'];?></a></div><?php } ?>

</body>
</html>