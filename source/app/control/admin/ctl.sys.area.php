<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */

class ctl_sys_area extends mod_sys_area {

	//默认浏览页
	function act_default() {
		if(fun_is::set("url_pid")) {
			$this->this_pid = (int)fun_get::get("url_pid");
		} else {
			$this->this_pid = intval(cls_config::get("area_city_id" , "meal"));
		}
		$this->this_path = $this->get_path( $this->this_pid );
		$this->arr_list = $this->sql_list($this->this_pid);
		return $this->get_view(); //显示页面
	}
	//移动显示页
	function act_move_open() {
		$this->area_select_html = $this->get_area_select();
		return $this->get_view(); //显示页面
	}
	//保存操作,返回josn数据
	function act_save() {
		$arr_return = $this->on_save();
		return fun_format::json($arr_return);
	}
	//保存操作,返回josn数据
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}

}