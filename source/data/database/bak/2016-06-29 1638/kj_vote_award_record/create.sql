#-----------------创建表--- kj_vote_award_record
DROP TABLE IF EXISTS `{DB_PRE}vote_award_record`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}vote_award_record` (
`record_id` int(10) NOT NULL auto_increment,`record_user_id` int(10) NOT NULL,`record_item_id` int(10) NOT NULL,`record_usetime` int(10) default '0' COMMENT '抽奖时间',`record_addtime` int(10) NOT NULL,`record_usedate` datetime NOT NULL COMMENT '抽奖日期',`record_adddate` datetime NOT NULL COMMENT '添加日期',`record_award_id` int(10) default '0',`record_sid` varchar(50) NOT NULL,PRIMARY KEY (`record_id`)
) ENGINE=myisam  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31

