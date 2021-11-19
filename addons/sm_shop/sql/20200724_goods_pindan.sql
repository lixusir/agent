-- 添加商品拼单信息表中的 有效时间字段：validate_time --
ALTER TABLE sh_goods_pindan
  ADD `validate_time` INT (11) NOT NULL DEFAULT 0 COMMENT '(单位:小时)拼单有效时间' AFTER `number` ;


-- 添加订单拼单信息表 --
CREATE TABLE `sh_order_pindan_info` (
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `validate_time` int(11) DEFAULT NULL COMMENT '小时:拼单有效时间',
  `number` int(11) DEFAULT NULL COMMENT '订单拼单数量',
  `complete` int(1) DEFAULT '0' COMMENT '拼单是否完成',
  `start_time` datetime NOT NULL COMMENT '拼单开始时间',
  PRIMARY KEY (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
