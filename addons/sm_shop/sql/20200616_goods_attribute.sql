
CREATE TABLE `sh_goods_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sh_goods_attr_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `attr` varchar(255) NOT NULL COMMENT '关联的属性',
  `price` decimal(10,2) NOT NULL,
  `stock` int(9) NOT NULL DEFAULT '-1' COMMENT '-1:库存不限',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sh_goods_attr_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL,
  `value` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attr_id` (`attr_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
