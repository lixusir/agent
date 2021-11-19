-- 添加用户退款字段：apply_refund --
ALTER TABLE sh_order ADD `apply_refund` INT(1) NOT NULL DEFAULT 0 COMMENT '用户申请退款' AFTER `is_payed`;