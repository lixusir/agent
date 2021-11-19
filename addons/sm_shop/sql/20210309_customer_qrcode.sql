-- 用户推广二维码表 --
CREATE TABLE `sh_user_qrcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `scene` varchar(255) NOT NULL,
  `qrcode_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;