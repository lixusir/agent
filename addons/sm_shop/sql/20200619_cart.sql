-- 在购物车添加商品选项id字段 --
ALTER TABLE sh_cart
  ADD `option_id` INT(11) NOT NULL default 0 COMMENT '商品规格选项id:sh_goods_attr_object主键' AFTER `goods_id` ;


ALTER TABLE sh_cart
  ADD `goods_option`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '商品规格' AFTER `goods_id` ;