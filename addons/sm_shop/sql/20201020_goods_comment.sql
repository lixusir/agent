-- 添加商品评论用户头像字段：avatar --
ALTER TABLE sh_goods_comment ADD `avatar` VARCHAR(255) NOT NULL AFTER `author`;