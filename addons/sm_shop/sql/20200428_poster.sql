-- 添加回复关键字字段 --
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;