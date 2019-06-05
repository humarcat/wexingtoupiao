#-----------------创建表--- kj_vote_act
DROP TABLE IF EXISTS `{DB_PRE}vote_act`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}vote_act` (
`act_id` int(10) NOT NULL auto_increment,`act_name` varchar(50) NOT NULL,`act_ping` varchar(20) NOT NULL,`act_ext1` varchar(50) NOT NULL,`act_ext2` varchar(50) NOT NULL,`act_pic` varchar(100) NOT NULL,`act_pics` varchar(1000) NOT NULL,`act_detail` text NOT NULL,`act_beta` varchar(500) NOT NULL,`act_vote` int(10) default '0',`act_hits` int(10) default '0',`act_addtime` int(10) default '0',`act_state` tinyint(1) default '0',`act_group` varchar(20) NOT NULL,`act_title` varchar(50) NOT NULL,`act_linkname` varchar(20) NOT NULL,`act_linktel` varchar(20) NOT NULL,`act_sort` int(10) default '0' COMMENT '排名',`act_item_id` int(10) default '0',`act_user_id` int(10) NOT NULL,PRIMARY KEY (`act_id`)
) ENGINE=myisam  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6

