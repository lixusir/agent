-- 用户推荐关系表 --
CREATE TABLE `sh_customer_chain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `head_id` int(11) NOT NULL COMMENT '推荐链头部的用户id',
  `tail_id` int(11) NOT NULL COMMENT '推荐链尾部的用户id',
  `chain` text NOT NULL COMMENT '推荐链所有用户id序列',
  PRIMARY KEY (`id`),
  KEY `head_id` (`head_id`),
  KEY `tail_id` (`tail_id`),
  FULLTEXT KEY `chain` (`chain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 用户表添加推荐人id --
ALTER TABLE sh_customer ADD `referee` INT (11) NOT NULL DEFAULT 0 COMMENT '推荐人用户id' AFTER `id`;