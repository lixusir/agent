-- 添加新字段：拼单价格 --
ALTER TABLE sh_goods_attr_object ADD `price_pindan` DECIMAL(10,2) NOT NULL COMMENT '拼单价格'  AFTER `price`;