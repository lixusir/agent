-- 用户表重新整理 --
drop table sh_customer;
CREATE TABLE `sh_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_group_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `open_id` varchar(50) NOT NULL COMMENT '公众号openid',
  `telephone` varchar(13) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL,
  `headUrl` text COMMENT '微信头像url',
  `info` text COMMENT '微信用户信息',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0:禁用1:启用',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telephone` (`telephone`),
  KEY `customer_group_id` (`customer_group_id`),
  KEY `open_id` (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--  小程序 openid 和 手机号 的对应关系表 --
CREATE TABLE `sh_customer_xcx` (
  `open_id` varchar(50) NOT NULL,
  `telephone` varchar(13) NOT NULL,
  PRIMARY KEY (`open_id`),
  UNIQUE KEY `telephone` (`telephone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
