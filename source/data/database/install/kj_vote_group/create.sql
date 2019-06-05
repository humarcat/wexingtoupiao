#-----------------创建表--- kj_vote_group
DROP TABLE IF EXISTS `{DB_PRE}vote_group`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}vote_group` (
`group_id` int(11) NOT NULL auto_increment,`group_name` varchar(20) NOT NULL,`group_addtime` int(10) NOT NULL,`group_item_id` int(10) NOT NULL,`group_sort` int(10) default '0',PRIMARY KEY (`group_id`)
) ENGINE=myisam  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3

