ALTER TABLE `dg_webs`
ADD COLUMN `app_conf` text DEFAULT NULL;

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