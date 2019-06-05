#-----------------创建表--- kj_vote_award_lottery
DROP TABLE IF EXISTS `{DB_PRE}vote_award_lottery`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}vote_award_lottery` (
`lottery_id` int(10) NOT NULL auto_increment,`lottery_item_id` int(10) default '0',`lottery_award_id` int(10) default '0',`lottery_datetime` datetime NOT NULL,`lottery_user_id` int(10) NOT NULL,`lottery_name` varchar(50) NOT NULL,`lottery_tel` varchar(20) NOT NULL,`lottery_address` varchar(50) NOT NULL,`lottery_sendid` varchar(20) NOT NULL,`lottery_sendname` varchar(20) NOT NULL,`lottery_sendtime` int(10) default '0',`lottery_sendval` decimal(10,2) NOT NULL,`lottery_state` tinyint(1) default '0',PRIMARY KEY (`lottery_id`)
) ENGINE=myisam  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1

