<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class mod_vote extends inc_mod_common {

	/* 取当前系统所有用户
	 * 
	 */
	function get_item() {
		$arr_return = array("list" => array());
		$obj_db = cls_obj::db();
		$arr_where = array();
		$arr_where_s = array();
		$lng_issearch = 0;
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info(".vote.item"  , $this->app_dir , 0 , array('pagesize' => 50) );
		$lng_pagesize = $arr_config_info["pagesize"];
		//取分页信息
		$str_where = "";
		$lng_page = (int)fun_get::get("page");
		$sort = " order by item_id desc";
		//取查询参数
		$arr_search_key = array(
			'key' => fun_get::get("s_key"),
		);
		if( $arr_search_key['key'] != '' ) $arr_where_s[] = "item_title like '%" . $arr_search_key['key'] . "%'"; 
		$arr_where = array_merge($arr_where , $arr_where_s);

		if(count($arr_where)>0) $str_where = " where " . implode(" and " , $arr_where);

		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."vote_item" , $str_where , $lng_page , $lng_pagesize);

		$obj_result = $obj_db->select("SELECT item_id,item_title FROM ".cls_config::DB_PRE."vote_item" . $str_where . $sort . $arr_return['pageinfo']['limit']);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$arr_return["list"][] = $obj_rs;
		}
		if( count($arr_where_s) > 0 ) $lng_issearch = 1;
		$arr_return['issearch'] = $lng_issearch;
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;

	}

}