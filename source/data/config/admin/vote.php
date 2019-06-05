<?php
return array (
 	"vote.act" => array(
		"act_id" => array("val" => 1,"w" => 0), //分享id
		"act_sort" => array("val" => 1,"w" => 80), //姓名
		"act_name" => array("val" => 1,"w" => 80), //姓名
		"act_title" => array("val" => 0,"w" => 150), //作品名称
		"act_ext1" => array("val" => 0,"w" => 120), //年龄
		"act_ext2" => array("val" => 1,"w" => 120), //备注
		"act_pic" => array("val" => 1,"w" => 120), //头像
		"act_vote" => array("val" => 1,"w" => 60), //票数
		"act_hits" => array("val" => 1,"w" => 100), //访问次数
		"act_state" => array("val" => 1,"w" => 60), //状态
		"act_addtime" => array("val" => 1,"w" => 300), //添加时间
		"act_linkname" => array("val" => 1,"w" => 100), //状态
		"act_linktel" => array("val" => 1,"w" => 100), //状态
	),
 	"vote.piao" => array(
		"piao_id" => array("val" => 1,"w" => 0), //分享id
		"piao_act_id" => array("val" => 0,"w" => 60), //选手id
		"act_name" => array("val" => 1,"w" => 200), //姓名
		"user_netname" => array("val" => 1,"w" => 200), //投票人
		"piao_datetime" => array("val" => 1,"w" => 120), //投票时间
		"piao_ip" => array("val" => 1,"w" => 120), //ip
	),
 	"vote.item" => array(
		"item_id" => array("val" => 1,"w" => 0), //id
		"item_title" => array("val" => 1,"w" => 200), //标题
		"item_start_time" => array("val" => 1,"w" => 120), //开始时间
		"item_end_time" => array("val" => 1,"w" => 120), //结束时间
		"item_limit" => array("val" => 1,"w" => 60), //总票限制
		"item_day_limit" => array("val" => 1,"w" => 60), //每天限制
		"item_share" => array("val" => 1,"w" => 60), //分享增加
		"item_isrepeat" => array("val" => 1,"w" => 60), //重复投票
	),
 	"vote.group" => array(
		"group_id" => array("val" => 1,"w" => 0), //id
		"group_sort" => array("val" => 1,"w" => 100), //标题
		"group_name" => array("val" => 1,"w" => 200), //开始时间
		"group_addtime" => array("val" => 1,"w" => 120), //结束时间
	),
 	"vote.award" => array(
		"award_id" => array("val" => 1,"w" => 0), //id
		"award_type" => array("val" => 1,"w" => 80), //类型
		"award_name" => array("val" => 1,"w" => 200), //名称
		"award_num" => array("val" => 1,"w" => 60), //数量
		"award_lottery" => array("val" => 1,"w" => 60), //已抽出
		"award_rate" => array("val" => 1,"w" => 60), //中奖机率
		"award_user_num" => array("val" => 1,"w" => 60), //每人限中
		"award_addtime" => array("val" => 1,"w" => 120), //添加时间
	),
 	"vote.award.record" => array(
		"record_id" => array("val" => 1,"w" => 0), //id
		"record_user_id" => array("val" => 0,"w" => 60), //抽奖人id
		"user_netname" => array("val" => 1,"w" => 200), //抽奖人
		"record_usetime" => array("val" => 1,"w" => 120), //抽奖时间
		"record_addtime" => array("val" => 1,"w" => 120), //领取时间
		"record_award_id" => array("val" => 0,"w" => 60), //奖品id
		"award_name" => array("val" => 1,"w" => 200), //奖品
	),
 	"vote.award.lottery" => array(
		"lottery_id" => array("val" => 1,"w" => 0), //id
		"lottery_award_id" => array("val" => 0,"w" => 60), //奖品id
		"award_name" => array("val" => 1,"w" => 200), //奖品
		"lottery_user_id" => array("val" => 1,"w" => 120), //抽奖人id
		"user_netname" => array("val" => 1,"w" => 120), //抽奖人
		"lottery_datetime" => array("val" => 0,"w" => 60), //中奖时间
		"lottery_state" => array("val" => 1,"w" => 60), //状态
		"lottery_sendid" => array("val" => 1,"w" => 100), //发货单号
		"lottery_sendname" => array("val" => 1,"w" => 200), //快递名称
		"lottery_sendtime" => array("val" => 1,"w" => 120), //发货时间
		"award_type" => array("val" => 11,"w" => 120), //发货时间
	),

);