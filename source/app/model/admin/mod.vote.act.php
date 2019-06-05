<?php
/**
 * 菜单模型类 关联表名：vote_act
 * 
 */
class mod_vote_act extends inc_mod_vote {
	/* 按模块查询菜单信息并返回数组列表
	 * module : 指定查询模块
	 */
	function get_pagelist($isexcel = false) {
		$arr_where = array("act_item_id='" . $this->admin_item['id'] . "'");
		$arr_where_s = array();
		$str_where = '';
		$lng_issearch = 0;
		//取查询参数
		$arr_search_key = array(
			'act_name' => fun_get::get("s_key"),
		);
		if( !empty($arr_search_key['act_name']) ) $arr_where_s[] = "act_name like '%" . $arr_search_key['act_name'] . "%'"; 
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
		$arr_cfg_fields = tab_sys_user_config::get_fields("vote.act" , $this->app_dir , "vote");
		$arr_return['tabtd'] = $arr_cfg_fields["tabtd"];
		$arr_return['tabtit'] = $arr_cfg_fields["tabtit"];
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info("vote.act"  , $this->app_dir);
		$sort = $arr_config_info["sortby"];
		$arr_return["sort"] = $arr_config_info["sort"];
		$lng_pagesize = $arr_config_info["pagesize"];
		//取分页信息
		$arr_return["list"] = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."vote_act" , $str_where , $lng_page , $lng_pagesize);
		$sql = "SELECT " . $arr_cfg_fields["sel"] . " FROM ".cls_config::DB_PRE."vote_act" . $str_where . $sort;
		if(!$isexcel) $sql .= $arr_return['pageinfo']['limit'];
		$obj_result = $obj_db->select($sql);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			if(isset($obj_rs["act_addtime"]) && !empty($obj_rs["act_addtime"])) $obj_rs["act_addtime"] = date("Y-m-d H:i:s" , $obj_rs["act_addtime"]);
			if(isset($obj_rs["act_state"])) $obj_rs["act_state"] = array_search($obj_rs["act_state"] , tab_vote_act::get_perms("state"));
			$arr_return["list"][] = $obj_rs;
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}

	/* 查询配置表指定id信息
	 * msg_id : sys_config 表中 config_id
	 */
	function get_editinfo($msg_id) {
		$obj_rs = cls_obj::db()->edit(cls_config::DB_PRE."vote_act" , "act_id='".$msg_id."'");
		if( empty($obj_rs["act_id"]) ) {
			$obj_rs["act_state"] = 1;
		}
		$obj_rs["pics"] = empty($obj_rs["act_pics"]) ? array() : explode("|" , $obj_rs["act_pics"]);
		return $obj_rs;
	}
	function get_group() {
		$arr_list = array();
		$obj_result= cls_obj::db()->select("select * from " . cls_config::DB_PRE . "vote_group where group_item_id='" . $this->admin_item['id'] . "' order by group_sort,group_id");
		while($obj_rs = cls_obj::db()->fetch_array($obj_result)) {
			$arr_list[] = $obj_rs;
		}
		return $arr_list;
	}
	/* 保存数据
	 * 
	 */
	function on_save() {
		$arr_return = array("code" => 0 , "id"=>0 , "msg" => cls_language::get("save_ok"));
		$arr_fields = array(
			"act_id"=>fun_get::post("id"),
			"act_name"=>fun_get::post("act_name"),
			"act_ext1"=>fun_get::post("act_ext1"),
			"act_ext2"=>fun_get::post("act_ext2"),
			"act_beta"=>fun_get::post("act_beta"),
			"act_state"=>fun_get::post("act_state"),
			"act_group"=>fun_get::post("act_group"),
			"act_title"=>fun_get::post("act_title"),
			"act_sort"=>fun_get::post("act_sort"),
			"act_item_id"=>$this->admin_item['id'],
		);
		$arr_pics = array();
		$arr_pic = fun_get::get("slide1_url",array());
		foreach($arr_pic as $item) {
			if(!empty($item)) $arr_pics[] = $item;
		}
		$arr_fields['act_pics'] = implode("|",$arr_pics);
		$arr_fields['act_pic'] = (count($arr_pics)>0) ? $arr_pics[0] : '';
		$arr_ping = cls_pinyin::get($arr_fields['act_name'] , cls_config::DB_CHARSET);
		$arr_fields["act_ping"] = $arr_ping["style3"];
		$arr = tab_vote_act::on_save($arr_fields);
		if($arr['code']==0) {
			if(isset($arr['id'])) $arr_return['id'] = $arr['id'];
		} else {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}

	/* 删除指定  act_id 数据
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
		$arr = tab_vote_act::on_delete($str_id);
		if($arr['code'] != 0) {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}
	function on_excel() {
		$arr_return = $this->get_pagelist(true);
		$arr_excel=array();
		$arr = array();
		foreach($arr_return['tabtit'] as $item) {
			$arr[] = $item["name"];
		}
		$arr_excel[] = $arr;

		foreach($arr_return['list'] as $item) {
			$arr = array() ;
			foreach($arr_return['tabtit'] as $field) {
				$val = $item[$field["key"]];
				if($field["key"] == 'order_ticket' && empty($val)) $val = '';
				if($field["key"] == 'order_act_ids') $val = str_replace('<br>' , '；' , $val);
				$arr[] = $val;
			}
			$arr_excel[] = $arr;
		}
		$get_excel_name = fun_get::get("excel_name" , date("Y-m-d"));
		cls_excel::save_excel($get_excel_name.".xls",$arr_excel);
	}

}