<?php
/* versionbeta:name
 * versionbeta:number
 * versionbeta:site
 * versionbeta:pubtime
 */
class ctl_index extends mod_index{
	function act_default(){
		$this->arr_list = $this->get_act_list();
		$this->arr_group = tab_vote_act::get_perms("group");
		return $this->get_view();
	}
	function act_award(){
		$this->num = $this->get_award_num();
		return $this->get_view();
	}
	function act_award_lottery(){
		$this->arr_list = $this->get_award_lottery();
		return $this->get_view();
	}
	function act_yao(){
		$arr = $this->get_award_yao();
		return fun_format::json($arr);
	}
	function act_award_lottery_save(){
		$arr_list = $this->award_lottery_save();
		return fun_format::json($arr_list);
	}
	function act_wx_share() {
		$url = urldecode($_GET['url']);
		$arr = cls_weixin::sign_js($url);
		return fun_format::json($arr);
	}

	function act_list_more(){
		$arr_list = $this->get_act_list();
		return fun_format::json($arr_list);
	}
	function act_detail(){
		$this->detail = $this->get_detail();
		return $this->get_view();
	}
	function act_pm(){
		$arr_list = $this->get_pm_list();
		$this->arr_list = $arr_list;
		$total = array();
		$obj_one = cls_obj::db()->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_item_id='" . $this->obj_item['item_id'] . "'");
		$total['votes'] = $obj_one['num'];
		$total['acts'] = count($arr_list);
		$obj_one = cls_obj::db()->get_one("select sum(act_hits) as 'num' from " . cls_config::DB_PRE . "vote_act where act_item_id='" . $this->obj_item['item_id'] . "'");
		$total['hits'] = $obj_one['num'];		
		$this->total = $total;
		return $this->get_view();
	}
	function act_vote() {
		$arr = $this->on_vote();
		return fun_format::json($arr);
	}
	function act_intro() {
		return $this->get_view();
	}
	function act_join(){
		//$str_where =  "act_user_id='" . cls_obj::get("cls_user")->uid . "'";
		$editinfo = cls_obj::db()->edit(cls_config::DB_PRE . "vote_act");
		$editinfo["pics"] = empty($editinfo["act_pics"]) ? array() : explode("|" , $editinfo["act_pics"]);
		$arr_state = tab_vote_act::get_perms('state');
		$editinfo['state'] = array_search( $editinfo['act_state'] , $arr_state);
		$this->editinfo = $editinfo;
		$this->arr_group = $this->get_group();
		return $this->get_view();
	}
	function act_join_save() {
		$arr = $this->on_join_save();
		return fun_format::json($arr);
	}
}