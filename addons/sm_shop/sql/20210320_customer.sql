-- 添加推荐人id --
ALTER TABLE sh_customer ADD `referee` INT(11) NOT NULL COMMENT '推荐人用户id' AFTER `id`;