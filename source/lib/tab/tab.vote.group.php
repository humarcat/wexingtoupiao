<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class tab_vote_group {
	static $perms;

	//获取表配置参数
	static function get_perms($key) {
		if( empty(self::$perms) ) {
			self::$perms = array(
			);
		}
		$arr_return = array();
		if(isset(self::$perms[$key])) $arr_return = self::$perms[$key];
		return $arr_return;
	}

	/* 保存操作
	 * arr_fields : 为字段数据，默认如果包函 id，则为修改，否则为插入
	 * where : 默认为空，用于有时候条件修改
	 */
	static function on_save($arr_fields , $where = '') {
		$arr_return = array("code"=>0,"id"=>0,"msg"=>"");
		if(isset($arr_fields['group_id'])) {
			$arr_fields['id'] = $arr_fields['group_id'];
			unset($arr_fields['group_id']);
		}
		if( isset($arr_fields['id']) ) {
			$arr_return['id'] = (int)$arr_fields['id'];
			unset($arr_fields['id']);
			if( $arr_return['id'] > 0 ) { //大于零，为修改状态
				if( empty($where) ){
					$where = " group_id='" . $arr_return['id'] . "'";
				} else {
					$where = "(" . $where . ") and group_id='" . $arr_return['id'] . "'";
				}
			}
		}
		$obj_db = cls_obj::db_w();
		if( empty($where) ) {

			//必填项检查
			if(!isset($arr_fields['group_item_id']) || empty($arr_fields['group_item_id'])) return array("code" => 500 , "msg" => "请指定分组所属活动");
			if(!isset($arr_fields['group_name']) || empty($arr_fields['group_name'])) return array("code" => 500 , "msg" => "分组名称不能为空");
			
			//初始默认值
			$arr_fields['group_addtime'] = TIME;
			if(!isset($arr_fields['group_sort']) || empty($arr_fields['group_sort'])) {
				$obj_rs = $obj_db->get_one("select max(group_sort) as sort from " . cls_config::DB_PRE . "vote_group");
				(!empty($obj_rs))? $arr_fields['group_sort'] = $obj_rs["sort"] + 1 : $arr_fields['group_sort'] = 1;
			}
			//插入到用户表
			$arr = $obj_db->on_insert(cls_config::DB_PRE."vote_group",$arr_fields);
			if($arr['code'] == 0) {
				$arr_return['id'] = $obj_db->insert_id();
				//其它非mysql数据库不支持insert_id 时
				if(empty($arr_return['id'])) {
					$where  = "group_sort='" . $arr_fields['group_sort'] . " and group_addtime=" . $arr_fields['group_addtime'] . " and group_name='".$arr_fields['group_name'] . "'";
					$obj_rs = $obj_db->get_one("select group_id from ".cls_config::DB_PRE."vote_group where ".$where);
					if(!empty($obj_rs)) $arr_return['id'] = $obj_rs['group_id'];
				}
			} else {
				$arr_return['code'] = $arr['code'];
				$arr_return['msg']  = cls_language::get("db_edit");
			}
		} else {

			if($arr_return['id'] < 1) {
				$obj_rs = $obj_db->get_one("select group_id from ".cls_config::DB_PRE."vote_group where ".$where);
				if(!empty($obj_rs)) {
					$arr_return['id'] = $obj_rs['group_id'];
				} else {
					$arr_return['code'] = 114;
					$arr_return['msg']  = cls_language::get("no_editinfo");//修改信息不在在
					return $arr_return;
				}
				$where = "group_id='".$arr_return['id']."'";
			}
			//修改数据表
			$arr = $obj_db->on_update(cls_config::DB_PRE."vote_group" , $arr_fields , $where);
			if($arr['code'] != 0) {
				$arr_return['code'] = $arr['code'];
				$arr_return['msg']  = cls_language::get("db_edit");
			}
		}
		return $arr_return;
	}
	/* 删除函数
	 * arr_id : 要删除的 id数组
	 * where : 删除附加条件
	 */
	static function on_delete($arr_id , $where = '') {
		$arr_return = array("code"=>0,"msg"=>"");
		$str_id = fun_format::arr_id($arr_id);
		if( $str_id == "" && empty($where) ){
			$arr_return["code"] = 22;
			$arr_return["msg"]="id".cls_language::get("not_null");
			return $arr_return;
		}
		if( !empty($str_id) ) {
			(is_numeric($str_id)) ? $arr_where[] = "group_id='".$str_id."'" : $arr_where[] = "group_id in(".$str_id.")";
		}
		if( !empty($where) ) {
			if(stristr($where , " or ") && substr(trim($where),0,1) != "(") $where = "(" . $where . ")";
			$arr_where[] = $where;
		}
		$where = implode(" and " , $arr_where);
		$arr_return=cls_obj::db_w()->on_delete(cls_config::DB_PRE."vote_group" , $where);
		return $arr_return;
	}
	/** 按层次返回列表记录
	 *	pid : 指定父级id , layer : 当前层次 ，where : 附加条件
	 */
	static function get_list_layer($where = '') {
		$arr_list = array();
		$obj_db = cls_obj::db_w();
		$str_where = "";
		if($where != '') $str_where = " where " . $where;
		$obj_result = $obj_db->select("SELECT * FROM ".cls_config::DB_PRE."vote_group" . $str_where . " order by group_sort,group_id");
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$arr_list[] = $obj_rs;
		}
		$arr_return=array("list" => $arr_list);
		return $arr_return ;
	}
}