/*Table structure for table `sh_banner` */

DROP TABLE IF EXISTS `sh_banner`;

CREATE TABLE `sh_banner` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_banner_image` */

DROP TABLE IF EXISTS `sh_banner_image`;

CREATE TABLE `sh_banner_image` (
  `banner_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banner_image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_cart` */

DROP TABLE IF EXISTS `sh_cart`;

CREATE TABLE `sh_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `goods_option` varchar(255) NOT NULL DEFAULT '' COMMENT '商品规格',
  `goods_num` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_category` */

DROP TABLE IF EXISTS `sh_category`;

CREATE TABLE `sh_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_category_path` */

DROP TABLE IF EXISTS `sh_category_path`;

CREATE TABLE `sh_category_path` (
  `category_id` int(11) NOT NULL,
  `path_id` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`,`path_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_city` */

DROP TABLE IF EXISTS `sh_city`;

CREATE TABLE `sh_city` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) NOT NULL,
  `up_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3405 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_customer` */

DROP TABLE IF EXISTS `sh_customer`;

CREATE TABLE `sh_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_group_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `telephone` varchar(13) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0:禁用1:启用',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_group_id` (`customer_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_customer_address` */

DROP TABLE IF EXISTS `sh_customer_address`;

CREATE TABLE `sh_customer_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `province_id` int(11) DEFAULT NULL,
  `province_name` varchar(64) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `city_name` varchar(64) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `area_name` varchar(64) DEFAULT NULL,
  `detail_address` text NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_customer_group` */

DROP TABLE IF EXISTS `sh_customer_group`;

CREATE TABLE `sh_customer_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `description` text NOT NULL,
  `is_default` int(1) NOT NULL DEFAULT '0',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_goods` */

DROP TABLE IF EXISTS `sh_goods`;

CREATE TABLE `sh_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `image` varchar(255) NOT NULL COMMENT '主图相对途经',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0:下架，1：上架',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_goods_attr` */

DROP TABLE IF EXISTS `sh_goods_attr`;

CREATE TABLE `sh_goods_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_goods_attr_object` */

DROP TABLE IF EXISTS `sh_goods_attr_object`;

CREATE TABLE `sh_goods_attr_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `attr` varchar(255) NOT NULL COMMENT '关联的属性',
  `price` decimal(10,2) NOT NULL,
  `stock` int(9) NOT NULL DEFAULT '-1' COMMENT '-1:库存不限',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_goods_attr_value` */

DROP TABLE IF EXISTS `sh_goods_attr_value`;

CREATE TABLE `sh_goods_attr_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL,
  `value` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attr_id` (`attr_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_goods_description` */

DROP TABLE IF EXISTS `sh_goods_description`;

CREATE TABLE `sh_goods_description` (
  `goods_id` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_goods_img` */

DROP TABLE IF EXISTS `sh_goods_img`;

CREATE TABLE `sh_goods_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_goods_to_category` */

DROP TABLE IF EXISTS `sh_goods_to_category`;

CREATE TABLE `sh_goods_to_category` (
  `goods_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`goods_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_module` */

DROP TABLE IF EXISTS `sh_module`;

CREATE TABLE `sh_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL COMMENT '唯一标识',
  `title` varchar(32) NOT NULL COMMENT '标题',
  `settings` text COMMENT 'json内容',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`),
  KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_order` */

DROP TABLE IF EXISTS `sh_order`;

CREATE TABLE `sh_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) NOT NULL,
  `create_time` datetime NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total` float(6,2) NOT NULL,
  `shipping_method` varchar(16) NOT NULL,
  `payment_method` varchar(16) NOT NULL,
  `is_payed` tinyint(1) NOT NULL DEFAULT '0',
  `address_province` varchar(128) DEFAULT NULL,
  `address_city` varchar(128) DEFAULT NULL,
  `address_area` varchar(128) DEFAULT NULL,
  `address_detail` text,
  `address_phone` varchar(20) NOT NULL,
  `address_name` varchar(32) NOT NULL,
  `message` text,
  `status` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_order_goods` */

DROP TABLE IF EXISTS `sh_order_goods`;

CREATE TABLE `sh_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `goods_name` varchar(32) NOT NULL,
  `goods_option` varchar(255) NOT NULL DEFAULT '',
  `goods_num` int(8) NOT NULL,
  `goods_price` float(6,2) NOT NULL,
  `goods_total` float(6,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_order_history` */

DROP TABLE IF EXISTS `sh_order_history`;

CREATE TABLE `sh_order_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_status` varchar(16) NOT NULL,
  `comment` text,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_order_status` */

DROP TABLE IF EXISTS `sh_order_status`;

CREATE TABLE `sh_order_status` (
  `code` varchar(16) NOT NULL,
  `name` varchar(16) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_order_total` */

DROP TABLE IF EXISTS `sh_order_total`;

CREATE TABLE `sh_order_total` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `code` varchar(16) NOT NULL,
  `value` float(6,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_payment` */

DROP TABLE IF EXISTS `sh_payment`;

CREATE TABLE `sh_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(16) NOT NULL,
  `name` varchar(32) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_payment_config` */

DROP TABLE IF EXISTS `sh_payment_config`;

CREATE TABLE `sh_payment_config` (
  `code` varchar(16) NOT NULL,
  `config` text NOT NULL COMMENT '采用json的形式存储不同支付类型设置',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_poster` */

DROP TABLE IF EXISTS `sh_poster`;

CREATE TABLE `sh_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `bg_img` varchar(255) NOT NULL,
  `design` text NOT NULL COMMENT '设计部分',
  `reply` varchar(64) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_setting` */

DROP TABLE IF EXISTS `sh_setting`;

CREATE TABLE `sh_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(128) NOT NULL,
  `key` varchar(128) NOT NULL,
  `value` text NOT NULL,
  `serialized` tinyint(1) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Table structure for table `sh_shipping` */

DROP TABLE IF EXISTS `sh_shipping`;

CREATE TABLE `sh_shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(16) NOT NULL,
  `name` varchar(64) NOT NULL,
  `active` int(1) NOT NULL,
  `is_default` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_user` */

DROP TABLE IF EXISTS `sh_user`;

CREATE TABLE `sh_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `telephone` varchar(13) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `sh_zone` */

DROP TABLE IF EXISTS `sh_zone`;

CREATE TABLE `sh_zone` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `code` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4238 DEFAULT CHARSET=utf8;

