<?php
class ctl_vote_award_lottery extends mod_vote_award_lottery {

	//默认浏览页
	function act_default() {
		//分页列表
		$this->arr_type = tab_vote_award::get_perms("type");
		$this->arr_list = $this->get_pagelist( );
		return $this->get_view(); //显示页面
	}
	function act_send() {
		$arr_return = $this->on_send();
		return fun_format::json($arr_return);
	}
	//彻底删除,返回josn数据
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}
}