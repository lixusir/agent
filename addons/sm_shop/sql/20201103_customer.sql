-- 取消telephone 唯一索引字段，更改为普通索引 --
ALTER TABLE sh_customer DROP INDEX telephone;
ALTER TABLE sh_customer ADD INDEX `telephone` (`telephone`);