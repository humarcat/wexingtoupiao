#-----------------创建表--- kj_vote_piao
DROP TABLE IF EXISTS `{DB_PRE}vote_piao`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}vote_piao` (
`piao_id` int(10) NOT NULL auto_increment,`piao_act_id` int(10) NOT NULL,`piao_user_id` int(10) NOT NULL,`piao_date` date NOT NULL,`piao_datetime` datetime NOT NULL,`piao_ip` varchar(20) NOT NULL,`piao_item_id` int(10) default '0',`piao_sid` varchar(50) NOT NULL,PRIMARY KEY (`piao_id`)
) ENGINE=myisam  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1

