CREATE TABLE `sh_order_pindan` (
  `order_id` INT (11) NOT NULL AUTO_INCREMENT,
  `master_order` INT (11) NOT NULL COMMENT '主订单id',
  PRIMARY KEY (`order_id`),
  KEY `master_order` (`master_order`)
) ENGINE = INNODB DEFAULT CHARSET = utf8 ;
