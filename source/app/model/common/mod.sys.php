<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class mod_sys extends inc_mod_common {
	/* 取当前系统所有用户
	 * 
	 */
	function get_user_dialog1() {
		$arr_return = array("list" => array());
		$obj_db = cls_obj::db();
		$arr_where = array();
		$arr_where_s = array();
		$lng_issearch = 0;
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info(".dialog.user1"  , $this->app_dir );
		$lng_pagesize = $arr_config_info["pagesize"];
		//取分页信息
		$str_where = "";
		$lng_page = (int)fun_get::get("page");
		$sort = " order by user_id desc";
		//取查询参数
		$arr_search_key = array(
			'key' => fun_get::get("s_key"),
		);

		
		if(cls_config::USER_CENTER=="user.klkkdj") {
			if( $arr_search_key['key'] != '' ) $arr_where_s[] = "user_name like '%" . $arr_search_key['key'] . "%'"; 
			$arr_where = array_merge($arr_where , $arr_where_s);
			if(count($arr_where)>0) $str_where = " where " . implode(" and " , $arr_where);
			$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."user" , $str_where , $lng_page , $lng_pagesize);
			$obj_result = $obj_db->select("SELECT user_id,user_name FROM ".cls_config::DB_PRE."user" . $str_where . $sort . $arr_return['pageinfo']['limit']);
			while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
				$arr_return['list'][] = $obj_rs;
			}
		} else {
			if( $arr_search_key['key'] != '' ) $arr_where_s[] = "user_id='" . $arr_search_key['key'] . "'"; 
			$arr_where = array_merge($arr_where , $arr_where_s);
			if(count($arr_where)>0) $str_where = " where " . implode(" and " , $arr_where);
			$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."sys_user" , $str_where , $lng_page , $lng_pagesize);
			//整合外部系统，就不能直接查表了，这里不支持模糊查询
			$arr_uid = array();
			$obj_result = $obj_db->select("SELECT user_id FROM ".cls_config::DB_PRE."sys_user" . $str_where . $sort . $arr_return['pageinfo']['limit']);
			while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
				$arr_uid[] = $obj_rs['user_id'];
			}
			$arr_user = cls_obj::get("cls_user")->get_user($arr_uid);
			foreach($arr_user as $item => $key) {
				$arr_return['list'][] = array("user_id"=>$key ,"user_name"=>$item);
			}
		}
		if( count($arr_where_s) > 0 ) $lng_issearch = 1;
		$arr_return['issearch'] = $lng_issearch;
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;

	}

	/* 验证登录
	 * 
	 */
	function on_login_verify() {
		$arr_return = array("code" => 0 , "id"=>0 , "msg" => cls_language::get("login_ok"));
		$arr_fields = array(
			"user_name" => fun_get::post("uname"),
			"user_pwd"   => fun_get::post("pwd"),
			"verifycode" => fun_get::get("verifycode"),
			"autologin" => fun_get::get("autologin")
		);
		if(!fun_is::pwd($arr_fields["user_pwd"])) {
			$arr_return["code"] = 7;
			$arr_return["msg"]  =  fun_get::rule_pwd("tips");
			return $arr_return;
		}
		$arr = cls_obj::get("cls_user")->on_login($arr_fields);
		if( $arr["code"] != 0 ) {
			$arr_return = $arr;
			return $arr_return;
		}
		return $arr_return;
	}

	/*
	 * 发送注册验证信息
	 */
	function on_verify_reg() {
		//return array("code" => 0 , "msg" =>"");
		//是否需要验证码
		if( cls_config::get('reg_verify' , 'user') ) {
			$verifycode = fun_get::get("verifycode");
			if(cls_verifycode::on_verify($verifycode) == false) {
				$arr_return["code"] = 11;
				$arr_return["msg"]  = cls_language::get("verify_code_err");
				return $arr_return;
			}
		}
		$type = fun_get::get("type");
		$key = fun_get::get("key");
		$arr_type = array("email" => 3 , "mobile" => 4);

		if($type == 'email') {
			if(!fun_is::com("email"))  return array("code" => 500 , "msg" => "未安装邮件组件，无法发送验证信息");
			if(!fun_is::email($key))  return array("code" => 500 , "msg" => "邮箱格式不正确");
			$type_id = 3;
		} else if($type == 'mobile') {
			if(!fun_is::com("sms"))  return array("code" => 500 , "msg" => "未安装短信组件，无法发送验证信息");
			if(!fun_is::mobile($key))  return array("code" => 500 , "msg" => "手机格式不正确");
			$type_id = 4;
		} else {
			return array("code" => 500 , "msg" => "验证类型不存在");
		}

		$arr_key = tab_sys_verify::get_key(0 , $type_id , $key);
		if($arr_key['code']!=0) return array("code" => 500 , "msg" => "系统繁忙，请稍后再试");
		if($type == 'email') {
			//取邮件内容
			$obj_cont = cls_obj::db()->get_one("select article_title,article_content from " . cls_config::DB_PRE . "article where article_key='emailregwords'");
			if(empty($obj_cont)) {
				$obj_cont['article_title'] = cls_config::get("site_title" , "sys") . "注册验证码";
				$obj_cont['article_content'] = "您的注册验证码为：" . $arr_key['key'];
			} else {
				$obj_cont['article_content'] = fun_get::filter($obj_cont['article_content'] , true);
				$obj_cont['article_content'] = str_replace('{$key}' , $arr_key['key'] , $obj_cont['article_content']);
			}
			$arr = cls_obj::get("cls_com")->email('send' , array('to_mail' => $key , 'title' => $obj_cont['article_title'] , 'content' => $obj_cont['article_content'] ,'save' => 1));
			return $arr;
		} else if($type == 'mobile') {
			$arr = cls_obj::get('cls_com')->sms("on_send" , array("tel"=>$key , "cont" => "注册验证码：" . $arr_key['key'] . "，请在网页上输入此号码，如非本人操作请忽略" ) );
			return $arr;
		} else {
			return array("code" => 500 , "msg" => "验证类型不存在");
		}
	}

	//发送验证短信
	function on_send_sms($tel,$type) {
		if(!fun_is::com("sms"))  return array("code" => 500 , "msg" => "未安装短信组件，无法发送验证信息");
		//if(cls_obj::get("cls_user")->is_login()==false) return array("code" => 500 , "msg" => "需要先登录，才能验证手机号");
		if(!fun_is::mobile($tel))  return array("code" => 500 , "msg" => "手机格式不正确");
		$arr_key = tab_sys_verify::get_key(cls_obj::get("cls_user")->uid , $type , $tel);
		if($arr_key['code']!=0) return array("code" => 500 , "msg" => "系统繁忙，请稍后再试");
		$arr = cls_obj::get('cls_com')->sms("on_send" , array("tel"=>$tel , "cont" => "手机验证码：" . $arr_key['key'] . "，请在网页上输入此号码，如非本人操作请忽略" ) );
		return $arr;
	}

	//取地区
	function get_area($pid) {
		$arr_return = array("list" => array() , "id" => $pid , "depth" => 0);
		$obj_result = cls_obj::db()->select("select * from " . cls_config::DB_PRE . "sys_area where area_pid='" . $pid . "'");
		while($obj_rs = cls_obj::db()->fetch_array($obj_result)) {
			$arr_return['depth'] = $obj_rs['area_depth'];
			$arr_return['list'][] = $obj_rs;
		}
		return $arr_return;
	}

	function get_area_sel($pids) {
		$obj_db = cls_obj::db();
		$arr = array();
		if(empty($pids)) $pids = '0';
		$obj_result = $obj_db->select("select area_pids from " . cls_config::DB_PRE . "sys_area where area_id in(" . $pids . ")");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$arr[] = $obj_rs['area_pids'];
		}
		$ids = implode("," , $arr);
		$arr = explode("," , $ids);
		$arr = array_unique($arr);
		return $arr;
	}

}