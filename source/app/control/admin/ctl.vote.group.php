<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */

class ctl_vote_group extends mod_vote_group {

	//默认浏览页
	function act_default() {
		$this->arr_group = tab_vote_group::get_list_layer(0,1,"group_item_id=".$this->admin_item['id']);
		return $this->get_view(); //显示页面
	}
	//保存操作,返回josn数据
	function act_save_all() {
		$arr_return = $this->on_save_all();
		return fun_format::json($arr_return);
	}
}