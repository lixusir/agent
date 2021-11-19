-- 自提点 --
CREATE TABLE `sh_pick_up` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `phone` varchar(18) NOT NULL COMMENT '自提点电话',
  `province` varchar(32) DEFAULT NULL COMMENT '省',
  `city` varchar(32) DEFAULT NULL COMMENT '市',
  `area` varchar(32) DEFAULT NULL COMMENT '区，县',
  `street` varchar(32) DEFAULT NULL COMMENT '街道，乡，镇',
  `detail` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `latitude` float(9,6) DEFAULT NULL COMMENT '纬度',
  `longitude` float(9,6) unsigned zerofill DEFAULT NULL COMMENT '经度',
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 订单自提点 --
CREATE TABLE `sh_order_pickup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `pickup_id` int(11) NOT NULL COMMENT '自提点ID',
  `pickup_name` varchar(64) NOT NULL COMMENT '自提点名称,店铺',
  `pickup_phone` varchar(18) NOT NULL COMMENT '自提点电话',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `pickup_id` (`pickup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

