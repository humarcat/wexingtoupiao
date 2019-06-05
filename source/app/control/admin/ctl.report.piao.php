<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class ctl_report_piao extends mod_report_piao {

	//默认浏览页
	function act_default() {
		//订单量统计
		$this->report = $this->piao_num();
		$this->mode = fun_get::get("mode");
		return $this->get_view(); //显示页面
	}
}