-- 添加图片高度和宽度 字段：image_width, image_height --
ALTER TABLE sh_banner ADD image_width INT(4) NOT NULL DEFAULT 0 AFTER `name`;
ALTER TABLE sh_banner ADD image_height INT(4) NOT NULL DEFAULT 0 AFTER `name`;