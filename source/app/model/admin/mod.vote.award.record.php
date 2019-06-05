<?php
/**
 * 菜单模型类 关联表名：vote_award_record
 * 
 */
class mod_vote_award_record extends inc_mod_vote {
	/* 按模块查询菜单信息并返回数组列表
	 * module : 指定查询模块
	 */
	function get_pagelist($isexcel = false) {
		$arr_where = array("record_item_id='" . $this->admin_item["id"] . "'");
		$arr_where_s = array();
		$str_where = '';
		$lng_issearch = 0;
		//取查询参数
		$arr_search_key = array(
			'key' => fun_get::get("s_key"),
			'time1' => fun_get::get("s_time1"),
			'time2' => fun_get::get("s_time2"),
			'time3' => fun_get::get("s_time3"),
			'time4' => fun_get::get("s_time4"),
			'state' => (int)fun_get::get("s_state"),
		);
		if( !empty($arr_search_key['time1']) ) $arr_where_s[] = "record_addtime>='" . strtotime( $arr_search_key['time1'] ) . "'"; 
		if( !empty($arr_search_key['time2']) ) $arr_where_s[] = "record_addtime<='" . fun_get::endtime($arr_search_key['time2']) . "'"; 
		if( !empty($arr_search_key['time3']) ) $arr_where_s[] = "record_usetime>='" . strtotime( $arr_search_key['time3'] ) . "'"; 
		if( !empty($arr_search_key['time4']) ) $arr_where_s[] = "record_usetime<='" . fun_get::endtime($arr_search_key['time4']) . "'"; 
		if( !empty($arr_search_key['key']) ) $arr_where_s[] = "(user_netname like '%" . $arr_search_key['key'] . "%' || award_name like '%" . $arr_search_key['key'] . "%')"; 
		if($arr_search_key['state'] == 1) {
			$arr_where_s[] = "record_usetime=0";
		} else if($arr_search_key['state']==2) {
			$arr_where_s[] = "record_usetime>0";
		}
		//合并查询数组
		$arr_where = array_merge($arr_where , $arr_where_s);
		if(count($arr_where)>0) $str_where = " where " . implode(" and " , $arr_where);
		$arr_return = $this->sql_list($str_where , (int)fun_get::get('page') , $isexcel);

		if( count($arr_where_s) > 0 ) $lng_issearch = 1;
		$arr_return['issearch'] = $lng_issearch;

		return $arr_return;
	}


	/* 实现按具体条件查询数据表，并返回分页信息
	 * str_where : sql 查询条件 , lng_page : 当前页 , lng_pagesize : 分页大小
	 */
	function sql_list($str_where = "" , $lng_page = 1 , $isexcel = false) {
		$arr_return = array("list" => array());
		$obj_db = cls_obj::db();
		//取字段信息
		$arr_cfg_fields = tab_sys_user_config::get_fields("vote.award.record" , $this->app_dir , "vote");
		$arr_return['tabtd'] = $arr_cfg_fields["tabtd"];
		$arr_return['tabtit'] = $arr_cfg_fields["tabtit"];
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info("vote.award.record"  , $this->app_dir);
		$sort = $arr_config_info["sortby"];
		$arr_return["sort"] = $arr_config_info["sort"];
		$lng_pagesize = $arr_config_info["pagesize"];
		//取分页信息
		$arr_return["list"] = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."vote_award_record a left join " . cls_config::DB_PRE . "sys_user b on a.record_user_id=b.user_id left join " . cls_config::DB_PRE . "vote_award c on a.record_award_id=c.award_id" , $str_where , $lng_page , $lng_pagesize);
		$sql = "SELECT " . $arr_cfg_fields["sel"] . " FROM ".cls_config::DB_PRE."vote_award_record a left join " . cls_config::DB_PRE . "sys_user b on a.record_user_id=b.user_id left join " . cls_config::DB_PRE . "vote_award c on a.record_award_id=c.award_id" . $str_where . $sort;
		if(!$isexcel) $sql .= $arr_return['pageinfo']['limit'];
		$obj_result = $obj_db->select($sql);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			if(isset($obj_rs["record_addtime"]) && !empty($obj_rs["record_addtime"])) $obj_rs["record_addtime"] = date("Y-m-d H:i:s" , $obj_rs["record_addtime"]);
			if(isset($obj_rs['record_usetime'])) $obj_rs["record_usetime"] = empty($obj_rs['record_usetime']) ? "" : date("Y-m-d H:i:s" , $obj_rs["record_usetime"]);
			$arr_return["list"][] = $obj_rs;
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}

	/* 查询配置表指定id信息
	 * msg_id : sys_config 表中 config_id
	 */
	function get_editinfo($msg_id) {
		$obj_rs = cls_obj::db()->edit(cls_config::DB_PRE."vote_award_record" , "record_id='".$msg_id."'");
		$obj_rs["pics"] = empty($obj_rs["record_pics"]) ? array() : explode("|" , $obj_rs["record_pics"]);
		return $obj_rs;
	}

	/* 保存数据
	 * 
	 */
	function on_save() {
		$arr_return = array("code" => 0 , "id"=>0 , "msg" => cls_language::get("save_ok"));
		$arr_fields = array(
			"record_id" => fun_get::post("id"),
			"record_name" => fun_get::post("record_name"),
			"record_item_id" => $this->admin_item['id'],
			"record_num" => (int)fun_get::post("record_num"),
			"record_rate" => (float)fun_get::post("record_rate"),
			"record_user_num" => (int)fun_get::post("record_user_num"),
			"record_type" => fun_get::post("record_type"),
			"record_pic" => fun_get::post("record_pic"),
		);
		if($arr_fields['record_rate']>100) return array("code" => 500 , "msg" => "中奖机率不能大于100%");
		$arr = tab_vote_award_record::on_save($arr_fields);
		if($arr['code']==0) {
			if(isset($arr['id'])) $arr_return['id'] = $arr['id'];
		} else {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}

	/* 删除指定  record_id 数据
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
		$arr = tab_vote_award_record::on_delete($str_id);
		if($arr['code'] != 0) {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}
}