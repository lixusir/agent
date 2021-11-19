-- 订单添加收货地址名称（收货人姓名） --
ALTER TABLE sh_order ADD `address_name` VARCHAR(32) NOT NULL AFTER `address_detail`;
-- 订单添加收货地址电话 --
ALTER TABLE sh_order ADD `address_phone` VARCHAR(20) NOT NULL AFTER `address_detail`;