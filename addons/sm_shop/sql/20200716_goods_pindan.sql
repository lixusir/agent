-- 商品拼单设置表 --
CREATE TABLE `sh_goods_pindan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `number` int(10) NOT NULL DEFAULT '2' COMMENT '拼单人数',
  `price` decimal(10,2) NOT NULL COMMENT '拼单价格',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0:禁用1:起用',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
