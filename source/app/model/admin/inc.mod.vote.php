<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class inc_mod_vote extends inc_mod_admin {
	function __construct($arr_v) {
		parent::__construct($arr_v);
		$this->admin_item = $this->get_admin_item();
	}
	//获取当前管理的店铺信息
	function get_admin_item() {
		$arr_return = array("id"=>0 , "name" => "默认" , "mode" => 0);
		//是否指定店铺
		$item_id = (int)fun_get::get("url_item_id");
		if(empty($item_id)) {
			$item_id = intval(cls_session::get_cookie("admin_item_id"));
		} else {
			cls_session::set_cookie("admin_item_id" , $item_id);//将当前选择保有到cookie
		}
		
		if(!empty($item_id) && $item_id>0) {
			$obj_rs = cls_obj::db()->get_one("select item_id,item_title from " . cls_config::DB_PRE . "vote_item where item_id='" . $item_id . "'");
			if(!empty($obj_rs)) {
				$arr_return["id"] = $obj_rs["item_id"];
				$arr_return["name"] = $obj_rs["item_title"];
			} else {
				$arr_return["id"] = -1;
				$arr_return["name"] = "";
			}
		} else if($item_id == -999) {//所有
				$arr_return["id"] = -999;
				$arr_return["name"] = "所有";
		}
		return $arr_return;
	}
}