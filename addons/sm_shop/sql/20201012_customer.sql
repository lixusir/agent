-- 用户表添加平台区分字段：uniacid --
ALTER TABLE sh_customer ADD uniacid INT(10) UNSIGNED NOT NULL AFTER id ;