#-----------------创建表--- kj_sys_user
DROP TABLE IF EXISTS `{DB_PRE}sys_user`;
CREATE TABLE IF NOT EXISTS `{DB_PRE}sys_user` (
`user_id` int(10) NOT NULL,`user_name` varchar(50) NOT NULL COMMENT '用户名',`user_pwd` varchar(50) NOT NULL COMMENT '密码',`user_pwd_key` varchar(10) NOT NULL COMMENT '密码加密值',`user_pwd_admin` varchar(50) NOT NULL COMMENT '理管员密码',`user_email` varchar(50) NOT NULL COMMENT '邮箱',`user_regdate` datetime NOT NULL,`user_regtime` int(10) default '0',`user_regip` varchar(20) NOT NULL,`user_loginip` varchar(20) NOT NULL COMMENT '登录ip',`user_netname` varchar(30) NOT NULL COMMENT '昵称',`user_logintime` datetime NOT NULL COMMENT '最近登录时间',`user_loginnum` int(10) NOT NULL COMMENT '登录次数',`user_loginerr` varchar(20) default '0' COMMENT '登录错误次数',`user_continuenum` int(10) default '0' COMMENT '连续登录次数',`user_type` varchar(20) NOT NULL COMMENT '用户类型',`user_state` int(2) NOT NULL COMMENT '状态',`user_score` int(10) default '0' COMMENT '户用积分',`user_experience` int(10) default '0' COMMENT '经验',`user_repayment` decimal(10,2) default '0.00' COMMENT '预付款',`user_email_verify` tinyint(1) default '0' COMMENT '邮箱认证',`user_birthday` varchar(10) NOT NULL COMMENT '生日',`user_sex` tinyint(1) default '0' COMMENT '性别',`user_location` varchar(50) NOT NULL COMMENT '当前所在地',`user_house_location` varchar(50) NOT NULL COMMENT '家乡',`user_tel` varchar(20) NOT NULL COMMENT '电话',`user_mobile` varchar(20) NOT NULL COMMENT '手机',`user_address` varchar(50) NOT NULL COMMENT '联系地址',`user_realname` varchar(20) NOT NULL COMMENT '真实名称',`user_invite_uid` int(10) default '0' COMMENT '邀请id',`user_invite_uids` varchar(255) NOT NULL,`user_invite_num` int(10) default '0' COMMENT '邀请人数',`user_isdel` tinyint(1) default '0' COMMENT '回收站',`user_group_id` int(10) default '0' COMMENT '所属分组id',`user_depart_id` int(10) default '0' COMMENT '部门id',`user_order_num` int(10) default '0' COMMENT '订单总次数',`user_totalpay` decimal(10,2) default '0.00' COMMENT '除去抵扣积分总消费金额',`user_score_money` int(10) default '0' COMMENT '总抵扣金额',`user_order_time` datetime NOT NULL COMMENT '最后消费时间',`user_verify_tel` tinyint(4) default '0' COMMENT '手机验证',`user_verify_email` tinyint(4) default '0' COMMENT '邮箱验证',`user_site_id` int(10) default '0',`user_pic` varchar(255) NOT NULL,PRIMARY KEY (`user_id`)
) ENGINE=myisam  DEFAULT CHARSET=utf8
