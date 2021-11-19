-- 添加商品虚拟增长数量，根据此值的范围（0-该值）每日更新到sale字段. 需要计划任务 --
ALTER TABLE sh_goods
  ADD sale_add TINYINT NOT NULL DEFAULT 0 COMMENT '商品每日虚拟增长数量范围：0-值' AFTER `sale` ;