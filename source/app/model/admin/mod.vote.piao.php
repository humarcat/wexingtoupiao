<?php
/**
 * 菜单模型类 关联表名：vote_piao
 * 
 */
class mod_vote_piao extends inc_mod_vote {
	/* 按模块查询菜单信息并返回数组列表
	 * module : 指定查询模块
	 */
	function get_pagelist() {
		$arr_where = array("piao_item_id='" . $this->admin_item['id'] . "'");
		$arr_where_s = array();
		$str_where = '';
		$lng_issearch = 0;
		//取查询参数
		$arr_search_key = array(
			'act_name' => fun_get::get("s_act_name"),
			'user_netname' => fun_get::get("s_u_name"),
			'time1' => fun_get::get("s_time1"),
			'time2' => fun_get::get("s_time2"),
		);
		if( !empty($arr_search_key['act_name']) ) $arr_where_s[] = "act_name like '%" . $arr_search_key['act_name'] . "%'"; 
		if( !empty($arr_search_key['user_netname']) ) $arr_where_s[] = "user_netname like '%" . $arr_search_key['user_netname'] . "%'"; 
		if( fun_is::isdate( $arr_search_key['time1'] ) ) $arr_where_s[] = "piao_datetime >= '" . strtotime( $arr_search_key['time1'] ) . "'"; 
		if( fun_is::isdate( $arr_search_key['time2'] ) ) $arr_where_s[] = "piao_datetime <= '" . fun_get::endtime( $arr_search_key['time2'] ) . "'"; 
		//合并查询数组
		$arr_where = array_merge($arr_where , $arr_where_s);
		if(count($arr_where)>0) $str_where = " where " . implode(" and " , $arr_where);
		$arr_return = $this->sql_list($str_where , (int)fun_get::get('page'));

		if( count($arr_where_s) > 0 ) $lng_issearch = 1;
		$arr_return['issearch'] = $lng_issearch;

		return $arr_return;
	}


	/* 实现按具体条件查询数据表，并返回分页信息
	 * str_where : sql 查询条件 , lng_page : 当前页 , lng_pagesize : 分页大小
	 */
	function sql_list($str_where = "" , $lng_page = 1 , $lng_pagesize = 10) {
		$arr_return = array("list" => array());
		$obj_db = cls_obj::db();
		//取字段信息
		$arr_cfg_fields = tab_sys_user_config::get_fields("vote.piao" , $this->app_dir , "vote");
		$arr_return['tabtd'] = $arr_cfg_fields["tabtd"];
		$arr_return['tabtit'] = $arr_cfg_fields["tabtit"];
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info("vote.piao"  , $this->app_dir);
		$sort = $arr_config_info["sortby"];
		$arr_return["sort"] = $arr_config_info["sort"];
		$lng_pagesize = $arr_config_info["pagesize"];
		//取分页信息
		$arr_return["list"] = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."vote_piao a left join " . cls_config::DB_PRE . "vote_act b on a.piao_act_id=b.act_id left join " . cls_config::DB_PRE . "sys_user c on a.piao_user_id=c.user_id" , $str_where , $lng_page , $lng_pagesize);
		$obj_result = $obj_db->select("SELECT " . $arr_cfg_fields["sel"] . " FROM ".cls_config::DB_PRE."vote_piao a left join " . cls_config::DB_PRE . "vote_act b on a.piao_act_id=b.act_id left join " . cls_config::DB_PRE . "sys_user c on a.piao_user_id=c.user_id" . $str_where . $sort . $arr_return['pageinfo']['limit']);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$arr_return["list"][] = $obj_rs;
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}


}