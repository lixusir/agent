-- 设置表添加uniacid字段， 为之后的多商户做准备 --
ALTER TABLE sh_setting
  ADD `uniacid` INT (10) NOT NULL DEFAULT 0 AFTER `setting_id` ;