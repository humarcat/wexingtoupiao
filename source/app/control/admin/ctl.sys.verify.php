<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */

class ctl_sys_verify extends mod_sys_verify {

	//默认浏览页
	function act_default() {
		$this->arr_type = tab_sys_verify::get_perms('type');
		$this->arr_state = tab_sys_verify::get_perms('state');
		$url_type = fun_get::get("url_type");
		if(empty($url_type)) {
			foreach($this->arr_type as $item => $key) {
				$url_type = $key;break;
			}
		}
		$this->type = $url_type;

		$this->arr_list = $this->get_list($url_type);
		return $this->get_view(); //显示页面
	}

	//清除日志
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}
}