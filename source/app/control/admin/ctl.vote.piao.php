<?php
class ctl_vote_piao extends mod_vote_piao {

	//默认浏览页
	function act_default() {
		//分页列表
		$this->arr_list = $this->get_pagelist( );
		return $this->get_view(); //显示页面
	}
}