-- 添加两个字段：数量：number, 型号：model --
ALTER TABLE sh_goods ADD sale INT(10) NOT NULL DEFAULT 0 COMMENT '商品销量，用来展示在前台'  AFTER price;
ALTER TABLE sh_goods ADD model VARCHAR(64) NOT NULL DEFAULT '' COMMENT '商品型号' AFTER price;
