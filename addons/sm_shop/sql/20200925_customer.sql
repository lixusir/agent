-- 用户添加备注字段：remark --
ALTER TABLE sh_customer
  ADD `remark` TEXT NOT NULL AFTER `status` ;