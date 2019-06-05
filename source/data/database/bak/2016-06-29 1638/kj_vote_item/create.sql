#-----------------创建表--- kj_vote_item
DROP TABLE IF EXISTS `{DB_PRE}vote_item`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}vote_item` (
`item_id` int(10) NOT NULL auto_increment,`item_title` varchar(20) NOT NULL,`item_cont` text NOT NULL,`item_start_time` datetime NOT NULL,`item_end_time` datetime NOT NULL,`item_pic` varchar(100) NOT NULL,`item_day_limit` int(10) default '0' COMMENT '每天限制',`item_share` tinyint(1) default '0' COMMENT '分享',`item_isrepeat` tinyint(1) default '0' COMMENT '是否可以重复投票',`item_limit` int(10) default '0' COMMENT '投票总限制',`item_ping` varchar(50) NOT NULL,`item_pics` varchar(1000) NOT NULL,`item_addtime` int(10) NOT NULL,`item_award` tinyint(1) default '0',`item_login` tinyint(1) default '0',`item_footer` varchar(255) NOT NULL,PRIMARY KEY (`item_id`)
) ENGINE=myisam  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2

