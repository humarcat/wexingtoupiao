<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class mod_report_award extends inc_mod_vote {
	/* 订单量
	 * 默认为当天统计
	 */
	function award_num() {
		$mode = fun_get::get("mode");
		switch($mode) {
			case "year":
				//按月
				$arr_return = $this->award_num_byyear();
				break;
			case "month":
				//按月
				$arr_return = $this->award_num_bymonth();
				break;
			default:
				//按天
				$arr_return = $this->award_num_byday();
		}
		return $arr_return;
	}
	//按年
	function award_num_byyear() {
		$arr_return = array("list" => '' , "sub"=> '' , "splitX" => 12);
		$date = fun_get::get("year" , date("Y"));
		$obj_db = cls_obj::db();
		$arr_list = $arr_sub = array();
		for($i = 1; $i <= $arr_return["splitX"] ; $i++ ) {
			$ii = $i;
			if($i<10) $ii = "0" . $i;
			$arr_list[$ii] = 0;
			$arr_sub[] = $i . "月";
		}
		$field = "count(1)";
		$where = " where left(record_usedate,4)='" . $date . "'";
		if($this->admin_item["id"] > 0) {
			$where .= " and award_item_id='" . $this->admin_item["id"] . "'";
		}

		$obj_result = $obj_db->select("SELECT  left(record_usedate,7) as 'tips'," . $field . " as 'val' FROM " . cls_config::DB_PRE . "vote_award_record" . $where . " group by left(record_usedate,7)");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$tips = substr($obj_rs["tips"] , -2);
			$arr_list[$tips] = $obj_rs['val'];
		}
		$arr_list = array_values($arr_list);
		$arr_return['data'] = str_replace('"' , '' , fun_format::json( $arr_list ));
		$arr_return['sub'] = fun_format::json( $arr_sub );
		return $arr_return;
	}
	//按月
	function award_num_bymonth() {
		$arr_return = array("list" => '' , "sub"=> '' , "max" => 0 , "min" => 0);
		$year = fun_get::get("year" , date("Y"));
		$month = fun_get::get("month" , date("m"));
		if(strlen($month)<2) $month = "0" . $month;
		$date = $year . "-" . $month;
		$arr_return["splitX"] = (int)fun_get::end_day();
		$obj_db = cls_obj::db();
		$arr_list = $arr_sub = array();
		for($i = 1; $i <= $arr_return["splitX"] ; $i++ ) {
			$ii = $i;
			if($i<10) $ii = "0" . $i;
			$arr_list[$ii] = 0;
			$arr_sub[] = $i;
		}
		$field = "count(1)";
		$where = " where left(record_usedate,7)='" . $date . "'";
		if($this->admin_item["id"] > 0) {
			$where .= " and award_item_id='" . $this->admin_item["id"] . "'";
		}
		$obj_result = $obj_db->select("SELECT  record_usedate as 'tips'," . $field . " as 'val' FROM " . cls_config::DB_PRE . "vote_award_record" . $where . "  group by record_usedate");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$tips = substr($obj_rs["tips"] , -2);
			$arr_list[$tips] = $obj_rs["val"];
		}
		$arr_list = array_values($arr_list);
		$arr_return['data'] = str_replace('"' , '' , fun_format::json( $arr_list ));
		$arr_return['sub'] = fun_format::json( $arr_sub );
		return $arr_return;
	}
	//按天
	function award_num_byday() {
		$arr_return = array("list" => '' , "sub"=> '' , "splitX" => 24);
		$date = fun_get::get("date" , date("Y-m-d"));
		$obj_db = cls_obj::db();
		$arr_list = $arr_sub = array();
		for($i=0;$i<24;$i++) {
			$ii = $i;
			if($i<10) $ii = "0" . $i;
			$arr_list[$ii] = 0;
			$arr_sub[] = $ii;
		}
		$field = "count(1)";
		$where = " where record_usedate='" . $date . "'";
		if($this->admin_item["id"] > 0) {
			$where .= " and award_item_id='" . $this->admin_item["id"] . "'";
		}

		$obj_result = $obj_db->select("SELECT  left(record_usedate,13) as 'tips'," . $field . " as 'val' FROM " . cls_config::DB_PRE . "vote_award_record" . $where . "  group by left(record_usedate,13)");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$tips = substr($obj_rs["tips"] , -2);
			$arr_list[$tips] = $obj_rs["val"];
		}
		$arr_list = array_values($arr_list);
		$arr_return['data'] = str_replace('"' , '' , fun_format::json( $arr_list ));
		$arr_return['sub'] = fun_format::json( $arr_sub );
		return $arr_return;
	}
}