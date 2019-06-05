<?php
class ctl_other_share extends mod_other_share {

	//默认浏览页
	function act_default() {
		$this->arr_list = $this->get_pagelist();
		return $this->get_view();
	}
}