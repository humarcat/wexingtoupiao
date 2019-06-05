<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class inc_mod_default extends cls_base{

	/**
	 * admin 目录 初始类，启动 : 登录检查，权限检查
	 */
	function __construct($arr_v) {
		parent::__construct($arr_v);
		$this->this_login_user = cls_obj::get("cls_user");
		$this->uid = cls_obj::get("cls_user")->uid;
		$itemid = (int)fun_get::get("itemid");
		if(empty($itemid)) {
			$obj_item = cls_obj::db()->get_one("select * from " . cls_config::DB_PRE . "vote_item");
		} else {
			$obj_item = cls_obj::db()->get_one("select * from " . cls_config::DB_PRE . "vote_item where item_id='" . $itemid . "'");
		}
		if(!empty($obj_item)) $obj_item['item_cont'] = fun_format::tohtml($obj_item['item_cont']);
		if(isset($obj_item['item_login']) && $obj_item['item_login']) fun_base::wx_login();
		$this->obj_item = $obj_item;
	}
	/**
	 * 统一获取分页样式
	 * arr_info : 数组 , 值为 : 
	 * 返回 : 分页html字符串
	 */
	function get_pagebtns( $arr_info , $listnum = 10 ) {
		$arr_return = array("pre" => 0, "next" => 0,"list" => array() ,"premore" => 0 , "nextmore" => 0 , "start" => 1 , "end" => $arr_info['pages']);
		if($arr_info['total'] < 1) return $arr_return;
		$arr_return['pre'] = max(0,$arr_info['page']-1);
		$arr_return['next'] = $arr_info['page']+1;
		if($arr_return['next'] > $arr_info['pages']) $arr_return['next'] = 0;

		if($arr_info["pages"] > $listnum) {
			$ii = intval($listnum/2);
			if($arr_info['page'] > $ii){
				$lng_pre=$arr_info['page'] - $ii;
				$lng_next=$arr_info['page'] + $ii;
			}else{
				$lng_pre=1;
				$lng_next = $listnum;
			}
		}else{
			$lng_pre=1;
			$lng_next=$arr_info['pages'];
		}
		if($lng_pre < 1) $lng_pre = 1;
		if( $lng_next >= $arr_info['pages'] ) $lng_next = $arr_info['pages'];
		$arr_return['start'] = $lng_pre;
		$arr_return['end'] = $lng_next;

		for($i=$lng_pre;$i<=$lng_next;$i++){
			$arr_return['list'][] = $i;
		}
		return $arr_return;
	}

}