DROP TABLE IF EXISTS `dg_configs`;
CREATE TABLE `dg_configs` (
  `vkey` varchar(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`vkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dg_configs
-- ----------------------------
INSERT INTO `dg_configs` VALUES ('price_dx', '0.2');
INSERT INTO `dg_configs` VALUES ('price_all', '0.5');
INSERT INTO `dg_configs` VALUES ('price_dx2', '0.1');
INSERT INTO `dg_configs` VALUES ('price_all2', '0.4');
INSERT INTO `dg_configs` VALUES ('tc_rate', '0.1');
INSERT INTO `dg_configs` VALUES ('point_invite1', '1');
INSERT INTO `dg_configs` VALUES ('point_invite2', '0');
INSERT INTO `dg_configs` VALUES ('domain', 'www.baidu.com');
INSERT INTO `dg_configs` VALUES ('domains', 'klsf.com,baidu.com');
INSERT INTO `dg_configs` VALUES ('price_ktfz', '30');
INSERT INTO `dg_configs` VALUES ('price_ktfz_super', '100');
INSERT INTO `dg_configs` VALUES ('ktfz_rate', '10');
INSERT INTO `dg_configs` VALUES ('qiandao_num', '50');
INSERT INTO `dg_configs` VALUES ('qiandao_rule', '1:0.2,3:0.4,5:0.6');
INSERT INTO `dg_configs` VALUES ('qq', '1277180438');
INSERT INTO `dg_configs` VALUES ('gg_admin', '');
INSERT INTO `dg_configs` VALUES ('daili_rate', '90');
INSERT INTO `dg_configs` VALUES ('invite_rate', '10');
INSERT INTO `dg_configs` VALUES ('email_host', 'smtp.qq.com');
INSERT INTO `dg_configs` VALUES ('email_port', '465');
INSERT INTO `dg_configs` VALUES ('email_user', '123456@qq.com');
INSERT INTO `dg_configs` VALUES ('email_pwd', '');

DROP TABLE IF EXISTS `dg_kms`;
CREATE TABLE `dg_kms` (
  `kid` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `km` varchar(255) NOT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  `addtime` timestamp NULL DEFAULT NULL,
  `useid` int(11) NOT NULL DEFAULT '0',
  `usetime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_orders`;
CREATE TABLE `dg_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `zt` tinyint(2) NOT NULL DEFAULT '0',
  `endtime` datetime NOT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_points`;
CREATE TABLE `dg_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `action` varchar(255) NOT NULL,
  `point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bz` varchar(1024) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_qqs`;
CREATE TABLE `dg_qqs` (
  `qid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uin` varchar(12) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `skey` varchar(255) NOT NULL,
  `p_skey` varchar(255) NOT NULL,
  `superkey` varchar(255) DEFAULT NULL,
  `zt` tinyint(2) NOT NULL DEFAULT '0',
  `cookiezt` tinyint(2) NOT NULL DEFAULT '0',
  `addtime` datetime DEFAULT NULL,
  `id` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_tools`;
CREATE TABLE `dg_tools` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
INSERT INTO `dg_tools` VALUES ('1', '全套代挂', '0.00');
INSERT INTO `dg_tools` VALUES ('2', '电脑在线', '0.00');
INSERT INTO `dg_tools` VALUES ('3', '手机在线', '0.00');
INSERT INTO `dg_tools` VALUES ('4', '电脑管家', '0.00');
INSERT INTO `dg_tools` VALUES ('5', '音乐加速', '0.00');
INSERT INTO `dg_tools` VALUES ('6', '手游加速', '0.00');
INSERT INTO `dg_tools` VALUES ('7', '勋章加速', '0.00');
INSERT INTO `dg_tools` VALUES ('8', '钱包签到', '0.00');
INSERT INTO `dg_tools` VALUES ('9', 'QQ会员成长', '0.00');
DROP TABLE IF EXISTS `dg_users`;
CREATE TABLE `dg_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL,
  `upid` int(11) NOT NULL DEFAULT '0',
  `user` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `sid` varchar(50) DEFAULT NULL,
  `peie` int(11) NOT NULL DEFAULT '0',
  `coin` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rmb` decimal(10,2) NOT NULL DEFAULT '0.00',
  `power` int(11) NOT NULL DEFAULT '0',
  `daili` int(11) NOT NULL DEFAULT '0',
  `qq` varchar(255) NOT NULL,
  `isemail` varchar(255) DEFAULT NULL,
  `invite` varchar(255) DEFAULT NULL,
  `regip` varchar(255) DEFAULT NULL,
  `regtime` datetime DEFAULT NULL,
  `invitetime` datetime DEFAULT NULL,
  `pay_account` varchar(50) DEFAULT NULL,
  `pay_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;
INSERT INTO `dg_users` VALUES ('10000', '1', '0', 'admin', '4d3ea8f0d1aa6fa07b6c0a5375645c48', '1107338256151ba2b8d48c209e023d34', '10', '0.00', '0.00', '0.00', '9', '0', '1277180438', '0', null, null, '2016-05-31 10:22:26', null, null, null);
DROP TABLE IF EXISTS `dg_webs`;
CREATE TABLE `dg_webs` (
  `zid` int(11) NOT NULL AUTO_INCREMENT,
  `upzid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `super` tinyint(2) NOT NULL DEFAULT '0',
  `domain` varchar(255) DEFAULT NULL,
  `domain2` varchar(255) DEFAULT NULL,
  `qq` varchar(12) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price_dx` varchar(50) NOT NULL DEFAULT '0.8|0.6|0.5|0.4|0.3|0.1',
  `price_all` varchar(50) NOT NULL DEFAULT '3|2|1.8|1.5|1|0.8',
  `price_vip` varchar(50) NOT NULL DEFAULT '3|5|10|15|22',
  `price_ktfz` decimal(10,2) NOT NULL DEFAULT '30',
  `addtime` timestamp NULL DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `gg_panel` text,
  `gg_qqadd` text,
  `gg_dgadd` text,
  `gg_invite` text,
  `endtime` datetime DEFAULT '2016-06-01 00:00:00',
  `app_conf` text DEFAULT NULL,
  PRIMARY KEY (`zid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_hongbaos`;
CREATE TABLE `dg_hongbaos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `point` varchar(255) NOT NULL,
  `hbdate` date NOT NULL,
  `lqtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_qiandaos`;
CREATE TABLE `dg_qiandaos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  `qdtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_apis`;
CREATE TABLE `dg_apis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `apikey` varchar(64) DEFAULT NULL,
  `active` tinyint(2) NOT NULL DEFAULT '1',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_dgkms`;
CREATE TABLE `dg_dgkms` (
  `kid` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `km` varchar(255) NOT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  `addtime` timestamp NULL DEFAULT NULL,
  `user` varchar(20) NOT NULL DEFAULT '0',
  `usetime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_pay`;
CREATE TABLE `dg_pay` (
`trade_no` varchar(64) NOT NULL,
`type` varchar(20) NULL,
`uid` int(11) NOT NULL DEFAULT '0',
`time` datetime NULL,
`name` varchar(64) NULL,
`money` varchar(32) NULL,
`status` int(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (`trade_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_tixian`;
CREATE TABLE `dg_tixian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `money` varchar(32) NOT NULL,
  `realmoney` varchar(32) NOT NULL,
  `pay_account` varchar(50) NOT NULL,
  `pay_name` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `dg_chats`;
CREATE TABLE `dg_chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;