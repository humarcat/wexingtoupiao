<?php
/**
 * 菜单模型类 关联表名：vote_award_lottery
 * 
 */
class mod_vote_award_lottery extends inc_mod_vote {
	/* 按模块查询菜单信息并返回数组列表
	 * module : 指定查询模块
	 */
	function get_pagelist($isexcel = false) {
		$arr_where = array("lottery_item_id='" . $this->admin_item["id"] . "'");
		$arr_where_s = array();
		$str_where = '';
		$lng_issearch = 0;
		//取查询参数
		$arr_search_key = array(
			'key' => fun_get::get("s_key"),
			'time1' => fun_get::get("s_time1"),
			'time2' => fun_get::get("s_time2"),
			'state' => (int)fun_get::get("s_state",-999),
			'type'  => (int)fun_get::get("type",-999),
		);
		if( !empty($arr_search_key['time1']) ) $arr_where_s[] = "lottery_datetime>='" . $arr_search_key['time1'] . "'"; 
		if( !empty($arr_search_key['time2']) ) $arr_where_s[] = "lottery_datetime<='" . date("Y-m-d H:i:s",fun_get::endtime($arr_search_key['time2'])) . "'"; 
		if( !empty($arr_search_key['key']) ) $arr_where_s[] = "(user_netname like '%" . $arr_search_key['key'] . "%' || award_name like '%" . $arr_search_key['key'] . "%')"; 
		if( $arr_search_key['type'] != -999 ) $arr_where_s[] = "award_type='" . $arr_search_key['type']  . "'";
		if( $arr_search_key['state']!=-999 ) $arr_where_s[] = "lottery_state='" . $arr_search_key['state'] . "'"; 
		//合并查询数组
		$arr_where = array_merge($arr_where , $arr_where_s);
		if(count($arr_where)>0) $str_where = " where " . implode(" and " , $arr_where);
		$arr_return = $this->sql_list($str_where , (int)fun_get::get('page') , $isexcel);

		if( count($arr_where_s) > 0 ) $lng_issearch = 1;
		$arr_return['issearch'] = $lng_issearch;

		return $arr_return;
	}
	function on_send() {
		$id = fun_get::get("id");
		$arr_fields = array(
			"lottery_sendid" => fun_get::get("sendid"),
			"lottery_sendname" => fun_get::get("sendname"),
			"lottery_state" => 1,
			"lottery_sendtime" => TIME,
		);
		$arr_msg = cls_obj::db()->on_update(cls_config::DB_PRE . "vote_award_lottery",$arr_fields,"lottery_id='" . $id . "'");
		$arr_msg['id'] = $id;
		return $arr_msg;
	}

	/* 实现按具体条件查询数据表，并返回分页信息
	 * str_where : sql 查询条件 , lng_page : 当前页 , lng_pagesize : 分页大小
	 */
	function sql_list($str_where = "" , $lng_page = 1 , $isexcel = false) {
		$arr_return = array("list" => array());
		$obj_db = cls_obj::db();
		//取字段信息
		$arr_cfg_fields = tab_sys_user_config::get_fields("vote.award.lottery" , $this->app_dir , "vote");
		$arr_return['tabtd'] = $arr_cfg_fields["tabtd"];
		$arr_return['tabtit'] = $arr_cfg_fields["tabtit"];
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info("vote.award.lottery"  , $this->app_dir);
		$sort = $arr_config_info["sortby"];
		$arr_return["sort"] = $arr_config_info["sort"];
		$lng_pagesize = $arr_config_info["pagesize"];
		//取分页信息
		$arr_return["list"] = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."vote_award_lottery a left join " . cls_config::DB_PRE . "sys_user b on a.lottery_user_id=b.user_id left join " . cls_config::DB_PRE . "vote_award c on a.lottery_award_id=c.award_id" , $str_where , $lng_page , $lng_pagesize);
		$sql = "SELECT " . $arr_cfg_fields["sel"] . " FROM ".cls_config::DB_PRE."vote_award_lottery a left join " . cls_config::DB_PRE . "sys_user b on a.lottery_user_id=b.user_id left join " . cls_config::DB_PRE . "vote_award c on a.lottery_award_id=c.award_id" . $str_where . $sort;
		if(!$isexcel) $sql .= $arr_return['pageinfo']['limit'];
		$obj_result = $obj_db->select($sql);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			if(isset($obj_rs["lottery_addtime"]) && !empty($obj_rs["lottery_addtime"])) $obj_rs["lottery_addtime"] = date("Y-m-d H:i:s" , $obj_rs["lottery_addtime"]);
			if(isset($obj_rs["lottery_sendtime"]) && !empty($obj_rs["lottery_sendtime"])) $obj_rs["lottery_sendtime"] = date("Y-m-d H:i:s" , $obj_rs["lottery_sendtime"]);
			if(isset($obj_rs['lottery_state'])) $obj_rs['lottery_state'] = empty($obj_rs['lottery_state']) ? "<font style='color:#ff0000'>未发放</font>" : "已发放";
			$arr_return["list"][] = $obj_rs;
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}

	/* 查询配置表指定id信息
	 * msg_id : sys_config 表中 config_id
	 */
	function get_editinfo($msg_id) {
		$obj_rs = cls_obj::db()->edit(cls_config::DB_PRE."vote_award_lottery" , "lottery_id='".$msg_id."'");
		$obj_rs["pics"] = empty($obj_rs["lottery_pics"]) ? array() : explode("|" , $obj_rs["lottery_pics"]);
		return $obj_rs;
	}

	/* 保存数据
	 * 
	 */
	function on_save() {
		$arr_return = array("code" => 0 , "id"=>0 , "msg" => cls_language::get("save_ok"));
		$arr_fields = array(
			"lottery_id" => fun_get::post("id"),
			"lottery_name" => fun_get::post("lottery_name"),
			"lottery_item_id" => $this->admin_item['id'],
			"lottery_num" => (int)fun_get::post("lottery_num"),
			"lottery_rate" => (float)fun_get::post("lottery_rate"),
			"lottery_user_num" => (int)fun_get::post("lottery_user_num"),
			"lottery_type" => fun_get::post("lottery_type"),
			"lottery_pic" => fun_get::post("lottery_pic"),
		);
		if($arr_fields['lottery_rate']>100) return array("code" => 500 , "msg" => "中奖机率不能大于100%");
		$arr = tab_vote_award_lottery::on_save($arr_fields);
		if($arr['code']==0) {
			if(isset($arr['id'])) $arr_return['id'] = $arr['id'];
		} else {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}

	/* 删除指定  lottery_id 数据
	 */
	function on_delete() {
		$arr_return = array("code"=>0 , "msg"=> cls_language::get("delete_ok"));
		$str_id = fun_get::get("id");
		$arr_id = fun_get::get("selid");
		if( empty($arr_id) && empty($str_id) ) {
			$arr_return['code'] = 22;//见参数说明表
			$arr_return['msg']  = cls_language::get("delete_no_id");
			return $arr_return;
		}
		if(!empty($arr_id)) $str_id = $arr_id; //优先考虑 arr_id
		$arr = tab_vote_award_lottery::on_delete($str_id);
		if($arr['code'] != 0) {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}
}