<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class mod_index extends inc_mod_admin {
	//登录信息
	function get_login_info() {
		$obj_rs = cls_obj::db()->get_one("select user_loginnum,user_loginip,group_name from " . cls_config::DB_PRE . "sys_user a left join " . cls_config::DB_PRE . "sys_user_group b on a.user_group_id=b.group_id where user_id='" . cls_obj::get("cls_user")->uid . "'");
		$lastlogintime = cls_obj::get("cls_user")->lastlogintime;
		$arr_return = array(
			"lastlogintime" => $lastlogintime,
			"loginnum"  => $obj_rs["user_loginnum"],
			"loginip" => $obj_rs["user_loginip"],
			"group_name" => $obj_rs["group_name"]
		);
		return $arr_return;
	}
	//服务器信息
	function get_server_info() {
		$arr_return = array(
			"php_version" => PHP_VERSION,
			"os" => PHP_OS,
			"software" => $_SERVER ['SERVER_SOFTWARE'],
			"max_memory" => get_cfg_var ("memory_limit")?get_cfg_var("memory_limit") : "",
			"max_time" => get_cfg_var("max_execution_time")."秒 ",
			"max_upload" =>  get_cfg_var("upload_max_filesize") ? get_cfg_var("upload_max_filesize") : "禁止上传",
			"mysql_maxlink" =>  @get_cfg_var("mysql.max_links")==-1 ? "不限" : @get_cfg_var("mysql.max_links"),
			"mysql_version" =>  cls_obj::db()->version(),
		);
		return $arr_return;
	}

	function get_count_info() {
		$obj_db = cls_obj::db();
		$today_time = strtotime(date('Y-m-d'));
		//今日投票
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "vote_piao where piao_date='" . date("Y-m-d") . "'");
		$arr_return["piao_num"] = $obj_rs['num'];
		//累计投票
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "vote_piao");
		$arr_return["piao_total"] = $obj_rs['num'];
		//今日抽奖
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "vote_award_record where record_usedate='" . date("Y-m-d") . "'");
		$arr_return["award_num"] = $obj_rs['num'];
		//累计抽奖
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "vote_award_record");
		$arr_return["award_total"] = $obj_rs['num'];
		//今日 新增用户总数
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "sys_user where user_regtime>'" . $today_time . "'");
		$arr_return["user_new"] = $obj_rs["num"];
		//今日 回头用户
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "sys_user");
		$arr_return["user_num"] = $obj_rs["num"];
		return $arr_return;
	}
	//获取短信统计信息
	function get_sms_info() {
		$arr = array('code' => 0 ,'over' => "" , 'total' => "" , "today" => "" , "today_order" => "" , "today_re" => "" );
		$arr['code'] = (cls_obj::get('cls_com')->is('sms'))? 0 : 500;
		if($arr['code'] != 0) {
			$arr['over'] = "未安装";
			return $arr;
		}
		$arr_x = cls_obj::get('cls_com')->sms('get_over');
		$arr['code'] = $arr_x['code'];
		if($arr_x['code']==0) {
			$arr_x['num'];
			$arr['over'] = $arr_x['num'];
		} else if($arr_x['code']==502) {
			$arr['over'] = "未开通";
		} else if($arr_x['code']==503) {
			$arr['over'] = "连接失败";
			return $arr;
		} else {
			$arr['over'] = "连接失败";
			return $arr;
		}
		$obj_db = cls_obj::db();
		//发送总量
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "other_sms");
		$arr['total'] = $obj_rs['num'];
		//今日发送
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "other_sms where sms_day='" . date("Y-m-d") . "'");
		$arr['today'] = $obj_rs['num'];
		//今日订单短信
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "other_sms where sms_day='" . date("Y-m-d") . "' and sms_type=1");
		$arr['today_order'] = $obj_rs['num'];
		//今日订单回复
		$obj_rs = $obj_db->get_one("select count(1) as num from " . cls_config::DB_PRE . "other_sms where sms_day='" . date("Y-m-d") . "' and sms_type=1 and sms_retime=0");
		$arr['today_re'] = $obj_rs['num'];
		return $arr;
	}
	//下载安装包
	function on_down() {
		$zipname = fun_get::get("zipname");
		$cont = cls_klkkdj::down($zipname);
		if(!empty($cont)) {
			$path = KJ_DIR_DATA . "/package/" . $zipname . ".zip";
			file_put_contents($path , $cont);
			if(file_exists($path)) {
				//在线解压
				$arr = cls_zip::unzip($path);
				if($arr["code"]!=0) {
					return array("code"=>500 , "msg"=>$arr['msg']);
				}
			}
			return array("code" => 0);
		} else {
			return array("code"=>500,"msg" => "下载升级包失败，请尝试方法二手动下载");
		}
	}
	//获取安装步骤
	function get_install_steps() {
		$arr_return = array("code"=>0);
		$arr = $this->get_install_obj();
		if($arr["code"]!=0) return $arr;
		$obj_com = $arr["obj"];
		$arr_return["steps"] = $obj_com->get_install_steps();
		return $arr_return;
	}
	function get_install_obj(){
		$zipname = fun_get::get("zipname");
		$file = KJ_DIR_DATA . "/package/" . $zipname . "/install.php";
		if(!file_exists( $file )) return array("code" => 500 , "msg" => "未找到升级包");
		include_once($file);
		$cls = "install_sys";
		$obj_com = new $cls();
		return array("code"=>0 , "obj" => &$obj_com);
	}
	//安装
	function on_install() {
		$arr = $this->get_install_obj();
		if($arr["code"]!=0) return $arr;
		$step = (int)fun_get::get("step");
		$obj_com = $arr["obj"];
		$arr_steps = $obj_com->get_install_steps();
		$step = "install_" . $arr_steps[$step]['step'];
		$arr = $obj_com->$step();
		return $arr;
	}
	//预存款
	function get_user_repayment() {
		$obj_db = cls_obj::db();
		//预付总金额
		$obj_rs = $obj_db->get_one("select sum(repayment_val) as val from " . cls_config::DB_PRE . "sys_user_repayment where repayment_val>0");
		$arr['total'] = $obj_rs['val'];
		//已消费金额
		$obj_rs = $obj_db->get_one("select sum(repayment_val) as val from " . cls_config::DB_PRE . "sys_user_repayment where repayment_val<0");
		$arr['over'] = $obj_rs['val'];
		//当前金额
		$obj_rs = $obj_db->get_one("select sum(repayment_val) as val from " . cls_config::DB_PRE . "sys_user_repayment");
		$arr['now'] = $obj_rs['val'];
		//今日预存
		$obj_rs = $obj_db->get_one("select sum(repayment_val) as val from " . cls_config::DB_PRE . "sys_user_repayment where repayment_day='" . date("Y-m-d") . "' and repayment_val>0");
		$arr['today_total'] = $obj_rs['val'];
		//今日消费
		$obj_rs = $obj_db->get_one("select sum(repayment_val) as val from " . cls_config::DB_PRE . "sys_user_repayment where repayment_day='" . date("Y-m-d") . "' and repayment_val<0");
		$arr['today_over'] = $obj_rs['val'];
		return $arr;
	}
	function get_piao_report() {
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
		$where = " where piao_date='" . $date . "'";
		$obj_result = $obj_db->select("SELECT  left(piao_datetime,13) as 'tips'," . $field . " as 'val' FROM " . cls_config::DB_PRE . "vote_piao" . $where . "  group by left(piao_datetime,13)");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$tips = substr($obj_rs["tips"] , -2);
			$arr_list[$tips] = $obj_rs["val"];
		}
		$arr_list = array_values($arr_list);
		$arr_return['data'] = str_replace('"' , '' , fun_format::json( $arr_list ));
		$arr_return['sub'] = fun_format::json( $arr_sub );
		return $arr_return;
	}
	function get_award_report() {
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
		$where = " where left(record_usedate,10)='" . $date . "'";
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