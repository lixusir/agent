DELETE
FROM
  ims_modules
WHERE `name` = 'sm_shop' ;

INSERT INTO ims_modules SET `version` = '1.0',
`name` = 'sm_shop',
`type` = 'business',
title = 'sm_shop',
ability = 'sm_shop',
description = 'sm_shop',
author = 'Cloud',
issystem = 0,
wxapp_support = 1,
welcome_support = 1,
oauth_type = 1,
webapp_support = 1,
phoneapp_support = 1,
account_support = 2,
xzapp_support = 1,
aliapp_support = 1,
baiduapp_support = 1,
toutiaoapp_support = 1,
`from` = 'cloud' ;

DELETE FROM ims_modules_plugin WHERE `name` = 'sm_shop' ;
INSERT INTO ims_modules_plugin SET `name` = 'sm_shop' , main_module=1 ;

DELETE FROM ims_uni_modules WHERE uniacid = 3 AND module_name = 'sm_shop' ;
INSERT INTO ims_uni_modules SET uniacid = 3, module_name = 'sm_shop' ;

DELETE FROM ims_core_cache;
