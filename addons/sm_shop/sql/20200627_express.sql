-- 快递类型表 --
CREATE TABLE `sh_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(24) NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 订单快递表 --
CREATE TABLE `sh_order_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `express_code` varchar(24) NOT NULL COMMENT '快递类型',
  `package_no` varchar(24) NOT NULL COMMENT '快递单号',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `express_code` (`express_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO sh_express SET CODE="shunfeng",NAME="顺丰";
INSERT INTO sh_express SET CODE="shentong",NAME="申通";
INSERT INTO sh_express SET CODE="yunda",NAME="韵达快运";
INSERT INTO sh_express SET CODE="tiantian",NAME="天天快递";
INSERT INTO sh_express SET CODE="yuantong",NAME="圆通速递";
INSERT INTO sh_express SET CODE="zhongtong",NAME="中通速递";
INSERT INTO sh_express SET CODE="ems",NAME="ems快递";
INSERT INTO sh_express SET CODE="huitongkuaidi",NAME="百世快递";
INSERT INTO sh_express SET CODE="quanfengkuaidi",NAME="全峰快递";
INSERT INTO sh_express SET CODE="zhaijisong",NAME="宅急送";
INSERT INTO sh_express SET CODE="aae",NAME="aae全球专递";
INSERT INTO sh_express SET CODE="anjie",NAME="安捷快递";
INSERT INTO sh_express SET CODE="anxindakuaixi",NAME="安信达快递";
INSERT INTO sh_express SET CODE="biaojikuaidi",NAME="彪记快递";
INSERT INTO sh_express SET CODE="bht",NAME="bht";
INSERT INTO sh_express SET CODE="baifudongfang",NAME="百福东方国际物流";
INSERT INTO sh_express SET CODE="coe",NAME="中国东方（COE）";
INSERT INTO sh_express SET CODE="changyuwuliu",NAME="长宇物流";
INSERT INTO sh_express SET CODE="datianwuliu",NAME="大田物流";
INSERT INTO sh_express SET CODE="debangwuliu",NAME="德邦物流";
INSERT INTO sh_express SET CODE="dhl",NAME="dhl";
INSERT INTO sh_express SET CODE="dpex",NAME="dpex";
INSERT INTO sh_express SET CODE="dsukuaidi",NAME="d速快递";
INSERT INTO sh_express SET CODE="disifang",NAME="递四方";
INSERT INTO sh_express SET CODE="fedex",NAME="fedex（国外）";
INSERT INTO sh_express SET CODE="feikangda",NAME="飞康达物流";
INSERT INTO sh_express SET CODE="fenghuangkuaidi",NAME="凤凰快递";
INSERT INTO sh_express SET CODE="feikuaida",NAME="飞快达";
INSERT INTO sh_express SET CODE="guotongkuaidi",NAME="国通快递";
INSERT INTO sh_express SET CODE="ganzhongnengda",NAME="港中能达物流";
INSERT INTO sh_express SET CODE="guangdongyouzhengwuliu",NAME="广东邮政物流";
INSERT INTO sh_express SET CODE="gongsuda",NAME="共速达";
INSERT INTO sh_express SET CODE="hengluwuliu",NAME="恒路物流";
INSERT INTO sh_express SET CODE="huaxialongwuliu",NAME="华夏龙物流";
INSERT INTO sh_express SET CODE="haihongwangsong",NAME="海红";
INSERT INTO sh_express SET CODE="haiwaihuanqiu",NAME="海外环球";
INSERT INTO sh_express SET CODE="jiayiwuliu",NAME="佳怡物流";
INSERT INTO sh_express SET CODE="jinguangsudikuaijian",NAME="京广速递";
INSERT INTO sh_express SET CODE="jixianda",NAME="急先达";
INSERT INTO sh_express SET CODE="jiajiwuliu",NAME="佳吉物流";
INSERT INTO sh_express SET CODE="jymwl",NAME="加运美物流";
INSERT INTO sh_express SET CODE="jindawuliu",NAME="金大物流";
INSERT INTO sh_express SET CODE="jialidatong",NAME="嘉里大通";
INSERT INTO sh_express SET CODE="jykd",NAME="晋越快递";
INSERT INTO sh_express SET CODE="kuaijiesudi",NAME="快捷速递";
INSERT INTO sh_express SET CODE="lianb",NAME="联邦快递（国内）";
INSERT INTO sh_express SET CODE="lianhaowuliu",NAME="联昊通物流";
INSERT INTO sh_express SET CODE="longbanwuliu",NAME="龙邦物流";
INSERT INTO sh_express SET CODE="lijisong",NAME="立即送";
INSERT INTO sh_express SET CODE="lejiedi",NAME="乐捷递";
INSERT INTO sh_express SET CODE="minghangkuaidi",NAME="民航快递";
INSERT INTO sh_express SET CODE="meiguokuaidi",NAME="美国快递";
INSERT INTO sh_express SET CODE="menduimen",NAME="门对门";
INSERT INTO sh_express SET CODE="ocs",NAME="OCS";
INSERT INTO sh_express SET CODE="peisihuoyunkuaidi",NAME="配思货运";
INSERT INTO sh_express SET CODE="quanchenkuaidi",NAME="全晨快递";
INSERT INTO sh_express SET CODE="quanjitong",NAME="全际通物流";
INSERT INTO sh_express SET CODE="quanritongkuaidi",NAME="全日通快递";
INSERT INTO sh_express SET CODE="quanyikuaidi",NAME="全一快递";
INSERT INTO sh_express SET CODE="rufengda",NAME="如风达";
INSERT INTO sh_express SET CODE="santaisudi",NAME="三态速递";
INSERT INTO sh_express SET CODE="shenghuiwuliu",NAME="盛辉物流";
INSERT INTO sh_express SET CODE="suer",NAME="速尔物流";
INSERT INTO sh_express SET CODE="shengfeng",NAME="盛丰物流";
INSERT INTO sh_express SET CODE="saiaodi",NAME="赛澳递";
INSERT INTO sh_express SET CODE="tiandihuayu",NAME="天地华宇";
INSERT INTO sh_express SET CODE="tnt",NAME="tnt";
INSERT INTO sh_express SET CODE="ups",NAME="ups";
INSERT INTO sh_express SET CODE="wanjiawuliu",NAME="万家物流";
INSERT INTO sh_express SET CODE="wenjiesudi",NAME="文捷航空速递";
INSERT INTO sh_express SET CODE="wuyuan",NAME="伍圆";
INSERT INTO sh_express SET CODE="wxwl",NAME="万象物流";
INSERT INTO sh_express SET CODE="xinbangwuliu",NAME="新邦物流";
INSERT INTO sh_express SET CODE="xinfengwuliu",NAME="信丰物流";
INSERT INTO sh_express SET CODE="yafengsudi",NAME="亚风速递";
INSERT INTO sh_express SET CODE="yibangwuliu",NAME="一邦速递";
INSERT INTO sh_express SET CODE="youshuwuliu",NAME="优速物流";
INSERT INTO sh_express SET CODE="youzhengguonei",NAME="邮政快递包裹";
INSERT INTO sh_express SET CODE="youzhengguoji",NAME="邮政国际包裹挂号信";
INSERT INTO sh_express SET CODE="yuanchengwuliu",NAME="远成物流";
INSERT INTO sh_express SET CODE="yuanweifeng",NAME="源伟丰快递";
INSERT INTO sh_express SET CODE="yuanzhijiecheng",NAME="元智捷诚快递";
INSERT INTO sh_express SET CODE="yuntongkuaidi",NAME="运通快递";
INSERT INTO sh_express SET CODE="yuefengwuliu",NAME="越丰物流";
INSERT INTO sh_express SET CODE="yad",NAME="源安达";
INSERT INTO sh_express SET CODE="yinjiesudi",NAME="银捷速递";
INSERT INTO sh_express SET CODE="zhongtiekuaiyun",NAME="中铁快运";
INSERT INTO sh_express SET CODE="zhongyouwuliu",NAME="中邮物流";
INSERT INTO sh_express SET CODE="zhongxinda",NAME="忠信达";
INSERT INTO sh_express SET CODE="zhimakaimen",NAME="芝麻开门";
INSERT INTO sh_express SET CODE="annengwuliu",NAME="安能物流";
INSERT INTO sh_express SET CODE="jd",NAME="京东快递";
INSERT INTO sh_express SET CODE="weitepai",NAME="微特派";
INSERT INTO sh_express SET CODE="jiuyescm",NAME="九曳供应链";
