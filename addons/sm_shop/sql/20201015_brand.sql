-- 品牌表添加：sh_brand --
CREATE TABLE `sh_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `img` varchar(255) NOT NULL,
  `letter_pre` varchar(1) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- 商品表添加 品牌字段：brand_id --
ALTER TABLE sh_goods ADD `brand_id` INT (11) NOT NULL DEFAULT 0 AFTER `name` ;