-- 订单商品表添加商品规格字段：goods_option  --
ALTER TABLE sh_order_goods
  ADD goods_option VARCHAR (255) NOT NULL DEFAULT '' AFTER goods_name ;

