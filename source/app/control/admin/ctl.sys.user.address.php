<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class ctl_sys_user_address extends mod_sys_user_address {

	//默认浏览页
	function act_default() {
		$this->arr_list = $this->get_pagelist();
		return $this->get_view(); //显示页面
	}
	//编辑 新增 页面 ,有id时为编辑
	function act_edit() {

		$this->editinfo = $this->get_editinfo( fun_get::get('id') );
		return $this->get_view();
	}

	//保存操作,返回josn数据
	function act_save() {
		$arr_return = $this->on_save();
		return fun_format::json($arr_return);
	}

	//彻底删除,返回josn数据
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}

}