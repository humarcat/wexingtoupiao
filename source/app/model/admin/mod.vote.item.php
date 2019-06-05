<?php
/**
 * 菜单模型类 关联表名：vote_item
 * 
 */
class mod_vote_item extends inc_mod_vote {
	/* 按模块查询菜单信息并返回数组列表
	 * module : 指定查询模块
	 */
	function get_pagelist($isexcel = false) {
		$arr_where = array();
		$arr_where_s = array();
		$str_where = '';
		$lng_issearch = 0;
		//取查询参数
		$arr_search_key = array(
			'item_title' => fun_get::get("s_key"),
		);
		if( !empty($arr_search_key['item_title']) ) $arr_where_s[] = "item_title like '%" . $arr_search_key['item_title'] . "%'"; 
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
		$arr_cfg_fields = tab_sys_user_config::get_fields("vote.item" , $this->app_dir , "vote");
		$arr_return['tabtd'] = $arr_cfg_fields["tabtd"];
		$arr_return['tabtit'] = $arr_cfg_fields["tabtit"];
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info("vote.item"  , $this->app_dir);
		$sort = $arr_config_info["sortby"];
		$arr_return["sort"] = $arr_config_info["sort"];
		$lng_pagesize = $arr_config_info["pagesize"];
		//取分页信息
		$arr_return["list"] = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."vote_item" , $str_where , $lng_page , $lng_pagesize);
		$sql = "SELECT " . $arr_cfg_fields["sel"] . " FROM ".cls_config::DB_PRE."vote_item" . $str_where . $sort;
		if(!$isexcel) $sql .= $arr_return['pageinfo']['limit'];
		$obj_result = $obj_db->select($sql);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			if(isset($obj_rs["act_addtime"]) && !empty($obj_rs["act_addtime"])) $obj_rs["act_addtime"] = date("Y-m-d H:i:s" , $obj_rs["act_addtime"]);
			$arr_return["list"][] = $obj_rs;
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}

	/* 查询配置表指定id信息
	 * msg_id : sys_config 表中 config_id
	 */
	function get_editinfo($msg_id) {
		$obj_rs = cls_obj::db()->edit(cls_config::DB_PRE."vote_item" , "item_id='".$msg_id."'");
		$obj_rs["pics"] = empty($obj_rs["item_pics"]) ? array() : explode("|" , $obj_rs["item_pics"]);
		return $obj_rs;
	}

	/* 保存数据
	 * 
	 */
	function on_save() {
		$arr_return = array("code" => 0 , "id"=>0 , "msg" => cls_language::get("save_ok"));
		$arr_fields = array(
			"item_id"=>fun_get::post("id"),
			"item_title"=>fun_get::post("item_title"),
			"item_cont"=>fun_get::post("item_cont"),
			"item_start_time"=>fun_get::post("item_start_time"),
			"item_end_time"=>fun_get::post("item_end_time"),
			"item_day_limit"=>fun_get::post("item_day_limit"),
			"item_isrepeat"=>fun_get::post("item_isrepeat"),
			"item_award"=>fun_get::post("item_award"),
			"item_limit"=>fun_get::post("item_limit"),
			"item_login"=>fun_get::post("item_login"),
			"item_footer"=>fun_get::post("item_footer"),
		);
		if($arr_fields['item_login']) $arr_fields['item_share'] = fun_get::post("item_share");
		$arr_pics = array();
		$arr_pic = fun_get::get("slide1_url",array());
		foreach($arr_pic as $item) {
			if(!empty($item)) $arr_pics[] = $item;
		}
		$arr_fields['item_pics'] = implode("|",$arr_pics);
		$arr_fields['item_pic'] = (count($arr_pics[0])>0) ? $arr_pics[0] : '';

		$arr_ping = cls_pinyin::get($arr_fields['item_title'] , cls_config::DB_CHARSET);
		$arr_fields["item_ping"] = $arr_ping["style3"];
		$arr = tab_vote_item::on_save($arr_fields);
		if($arr['code']==0) {
			if(isset($arr['id'])) $arr_return['id'] = $arr['id'];
		} else {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}

	/* 删除指定  item_id 数据
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
		$arr = tab_vote_item::on_delete($str_id);
		if($arr['code'] != 0) {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}
}