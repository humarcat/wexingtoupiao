<?php
return array (
 	"vote.act" => array(
		"act_id" => array("val" => 1,"w" => 0), //����id
		"act_sort" => array("val" => 1,"w" => 80), //����
		"act_name" => array("val" => 1,"w" => 80), //����
		"act_title" => array("val" => 0,"w" => 150), //��Ʒ����
		"act_ext1" => array("val" => 0,"w" => 120), //����
		"act_ext2" => array("val" => 1,"w" => 120), //��ע
		"act_pic" => array("val" => 1,"w" => 120), //ͷ��
		"act_vote" => array("val" => 1,"w" => 60), //Ʊ��
		"act_hits" => array("val" => 1,"w" => 100), //���ʴ���
		"act_state" => array("val" => 1,"w" => 60), //״̬
		"act_addtime" => array("val" => 1,"w" => 300), //���ʱ��
		"act_linkname" => array("val" => 1,"w" => 100), //״̬
		"act_linktel" => array("val" => 1,"w" => 100), //״̬
	),
 	"vote.piao" => array(
		"piao_id" => array("val" => 1,"w" => 0), //����id
		"piao_act_id" => array("val" => 0,"w" => 60), //ѡ��id
		"act_name" => array("val" => 1,"w" => 200), //����
		"user_netname" => array("val" => 1,"w" => 200), //ͶƱ��
		"piao_datetime" => array("val" => 1,"w" => 120), //ͶƱʱ��
		"piao_ip" => array("val" => 1,"w" => 120), //ip
	),
 	"vote.item" => array(
		"item_id" => array("val" => 1,"w" => 0), //id
		"item_title" => array("val" => 1,"w" => 200), //����
		"item_start_time" => array("val" => 1,"w" => 120), //��ʼʱ��
		"item_end_time" => array("val" => 1,"w" => 120), //����ʱ��
		"item_limit" => array("val" => 1,"w" => 60), //��Ʊ����
		"item_day_limit" => array("val" => 1,"w" => 60), //ÿ������
		"item_share" => array("val" => 1,"w" => 60), //��������
		"item_isrepeat" => array("val" => 1,"w" => 60), //�ظ�ͶƱ
	),
 	"vote.group" => array(
		"group_id" => array("val" => 1,"w" => 0), //id
		"group_sort" => array("val" => 1,"w" => 100), //����
		"group_name" => array("val" => 1,"w" => 200), //��ʼʱ��
		"group_addtime" => array("val" => 1,"w" => 120), //����ʱ��
	),
 	"vote.award" => array(
		"award_id" => array("val" => 1,"w" => 0), //id
		"award_type" => array("val" => 1,"w" => 80), //����
		"award_name" => array("val" => 1,"w" => 200), //����
		"award_num" => array("val" => 1,"w" => 60), //����
		"award_lottery" => array("val" => 1,"w" => 60), //�ѳ��
		"award_rate" => array("val" => 1,"w" => 60), //�н�����
		"award_user_num" => array("val" => 1,"w" => 60), //ÿ������
		"award_addtime" => array("val" => 1,"w" => 120), //���ʱ��
	),
 	"vote.award.record" => array(
		"record_id" => array("val" => 1,"w" => 0), //id
		"record_user_id" => array("val" => 0,"w" => 60), //�齱��id
		"user_netname" => array("val" => 1,"w" => 200), //�齱��
		"record_usetime" => array("val" => 1,"w" => 120), //�齱ʱ��
		"record_addtime" => array("val" => 1,"w" => 120), //��ȡʱ��
		"record_award_id" => array("val" => 0,"w" => 60), //��Ʒid
		"award_name" => array("val" => 1,"w" => 200), //��Ʒ
	),
 	"vote.award.lottery" => array(
		"lottery_id" => array("val" => 1,"w" => 0), //id
		"lottery_award_id" => array("val" => 0,"w" => 60), //��Ʒid
		"award_name" => array("val" => 1,"w" => 200), //��Ʒ
		"lottery_user_id" => array("val" => 1,"w" => 120), //�齱��id
		"user_netname" => array("val" => 1,"w" => 120), //�齱��
		"lottery_datetime" => array("val" => 0,"w" => 60), //�н�ʱ��
		"lottery_state" => array("val" => 1,"w" => 60), //״̬
		"lottery_sendid" => array("val" => 1,"w" => 100), //��������
		"lottery_sendname" => array("val" => 1,"w" => 200), //�������
		"lottery_sendtime" => array("val" => 1,"w" => 120), //����ʱ��
		"award_type" => array("val" => 11,"w" => 120), //����ʱ��
	),

);