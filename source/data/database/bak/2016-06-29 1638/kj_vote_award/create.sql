#-----------------创建表--- kj_vote_award
DROP TABLE IF EXISTS `{DB_PRE}vote_award`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}vote_award` (
`award_id` int(10) NOT NULL auto_increment,`award_item_id` int(10) NOT NULL,`award_name` varchar(50) NOT NULL,`award_num` int(10) default '0' COMMENT '总数',`award_rate` float(10,2) default '0.00' COMMENT '中奖机率',`award_user_num` int(10) default '0' COMMENT '每人限中数量',`award_addtime` int(10) default '0',`award_type` smallint(2) default '0' COMMENT '类型',`award_pic` varchar(100) NOT NULL,`award_lottery` int(10) default '0' COMMENT '抽中',PRIMARY KEY (`award_id`)
) ENGINE=myisam  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4

