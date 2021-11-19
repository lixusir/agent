-- 商品特殊价格字段添加： special --
ALTER TABLE sh_goods
  ADD `special` DECIMAL (10, 2) NOT NULL DEFAULT 0 COMMENT '商品特殊价格' AFTER `price`;

