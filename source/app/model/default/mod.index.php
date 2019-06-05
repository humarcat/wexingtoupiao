<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
class mod_index extends inc_mod_default {
	function get_pm($arr_piao , $piao) {
		for($i = 0 ; $i < count($arr_piao) ; $i++) {
			if($piao>=$arr_piao[$i]) return $i+1;
		}
		return count($arr_piao);
	}
	function get_award_num() {
		$uid = cls_obj::get("cls_user")->uid;
		$where = " where record_usetime=0 and (record_user_id='" . $uid . "' or record_sid='" . cls_obj::get("cls_session")->id . "') and record_item_id='" . $this->obj_item['item_id'] . "'";
		if(empty($uid)) {
			$where .= " and record_sid='" . cls_obj::get("cls_session")->id . "'";
		} else {
			$where .= " and record_user_id='" . $uid . "'";
		}
		$obj_one = cls_obj::db()->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_award_record" . $where);
		$num = (!empty($obj_one) && $obj_one['num']>0) ? $obj_one['num'] : 0;
		return $num;
	}
	function get_award_yao() {
		$arr_return = array("code" => 0 , "msg" => "" , "id" =>0 , "num" => 0 , "id" => 0 , "name" => '' , "type" => 0 , "lotteryid" => 0);
		$arr_return['num'] = $this->get_award_num();
		if($arr_return['num']<1) return $arr_return;//机会用完
		$obj_record = cls_obj::db()->get_one("select record_id from " . cls_config::DB_PRE . "vote_award_record where record_user_id='" . cls_obj::get("cls_user")->uid . "' and record_usetime=0 and record_item_id='" . $this->obj_item['item_id']  . "'");
		if(empty($obj_record)) return $arr_return;//没有机会
		$arr_lottery = $this->on_lottery();
		//记录抽奖
		cls_obj::db()->on_exe("update " . cls_config::DB_PRE . "vote_award_record set record_usetime='" . TIME . "',record_usedate='" . date("Y-m-d H:i:s") . "' where record_id='" . $obj_record['record_id'] . "'");
		$arr_return['id'] = $arr_lottery['id'];
		$arr_return['name'] =$arr_lottery['name'];
		$arr_return['type'] = $arr_lottery['type'];
		$arr_return['num']--;
		$arr_return['lotteryid'] =$arr_lottery['lotteryid'];
		if($arr_return['num']<0) $arr_return['num'] = 0;
		return $arr_return;

	}
	function award_lottery_save() {
		$id = (int)fun_get::get("id");
		$arr_fields = array(
			"lottery_name" => fun_get::get("name"),
			"lottery_tel" => fun_get::get("tel"),
			"lottery_address" => fun_get::get("address"),
		);
		$arr = cls_obj::db()->on_update(cls_config::DB_PRE . "vote_award_lottery",$arr_fields,"lottery_id='" . $id . "'"); 
		return $arr;
	}
	function on_lottery() {
		$arr_return = array("code" => 0 , "msg" => "" , "id" =>0 , "num" => 0 , "id" => 0 , "name" => '' , "type" => 0 , "lotteryid" => 0);
		$obj_db = cls_obj::db();
		$rnd = rand(1,10000)/100;
		$rate = 0;
		$uid = cls_obj::get("cls_user")->uid;
		$obj_voucher = array();
		$obj_result = $obj_db->select("select award_id,award_name,award_num,award_rate,award_user_num,award_type,award_lottery from " . cls_config::DB_PRE . "vote_award where award_item_id='" . $this->obj_item['item_id'] . "'");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$rate += $obj_rs['award_rate'];
			if($rnd<=$rate) {
				if($obj_rs['award_num']<=$obj_rs['award_lottery']) return $arr_return;
				//判断是否领取过此礼物
				if($obj_rs['award_user_num']>0) {
					$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_award_lottery where lottery_user_id='" . $uid . "' and lottery_award_id='" . $obj_rs['award_id'] . "'");
					if(!$obj_one && $obj_rs['award_user_num']<=$obj_one['num']) return $arr_return;
				}
				$obj_db->begin("voucher");
				$arr_fields = array(
					"lottery_item_id" => $this->obj_item['item_id'],
					"lottery_award_id" => $obj_rs['award_id'],
					"lottery_user_id" => $uid,
					"lottery_datetime" => date("Y-m-d"),
				);
				$arr_msg = $obj_db->on_insert(cls_config::DB_PRE."vote_award_lottery",$arr_fields);
				if($arr_msg['code'] != 0) {
					$obj_db->rollback("voucher");
					return $arr_return;
				}
				$arr_return['lotteryid'] = $obj_db->insert_id();
				$arr = $obj_db->on_exe("update " . cls_config::DB_PRE . "vote_award set award_lottery=award_lottery+1 where award_id='" . $obj_rs['award_id'] . "' and award_lottery<award_num");
				if($arr_msg['code'] != 0 || $obj_db->affected_rows()<1) {
					echo "kdkdk";exit;
					return $arr_return;
				}
				$obj_db->commit("voucher");
				$arr_return['id'] = $obj_rs['award_id'];
				$arr_return['name'] =$obj_rs['award_name'];
				$arr_return['type'] = $obj_rs['award_type'];
				return $arr_return;
			}
		}
		return $arr_return;
	}
	function get_act_list() {
		$group = fun_get::get("s_group");
		$py = fun_get::get("s_ping");
		$s_key = fun_get::get("s_key");
		$arr_where = array("act_item_id='" . $this->obj_item['item_id'] . "' and act_state>0");
		$arr_return = array("list" => array());
		if(!empty($group)) $arr_where[] = "act_group='" . $group . "'";
		if(!empty($s_key)) $arr_where[] = "act_name like '%" . $s_key . "%'";
		if(!empty($py)) {
			switch($py) {
				case "A-G":
					$arr_where[] = "left(act_ping,1) in('A','B','C','D','E','F','G')";
					break;
				case "H-N":
					$arr_where[] = "left(act_ping,1) in('H','I','J','K','M','N','L')";
					break;
				case "O-U":
					$arr_where[] = "left(act_ping,1) in('O','P','Q','R','S','T','U')";
					break;
				case "V-Z":
					$arr_where[] = "left(act_ping,1) in('V','W','X','Y','Z')";
					break;
			}
		}
		$obj_db = cls_obj::db();
		$arr_piao = cls_cache::get("aoo_vote_act","vote",1);
		if(empty($arr_piao)) {
			$obj_result = $obj_db->select("select act_vote FROM ".cls_config::DB_PRE."vote_act order by act_vote desc");
			while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
				if(!empty($arr_piao) && $obj_rs['act_vote']==$arr_piao[count($arr_piao)-1]) continue;
				$arr_piao[] = $obj_rs['act_vote'];
			}
			cls_cache::set($arr_piao , "aoo_vote_act","vote");
		}
		$str_where = empty($arr_where) ? "" : " where " . implode(" and " , $arr_where);
		$lng_page = (int)fun_get::get("page");
		$lng_pagesize = 20;
		$prevote = -1;
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."vote_act" , $str_where , $lng_page , $lng_pagesize);
		$pm = ($arr_return["pageinfo"]['page']-1)*$lng_pagesize;
		$obj_result = $obj_db->select("select act_id,act_name,act_vote,act_ext1,act_ext2,act_pic FROM ".cls_config::DB_PRE."vote_act " . $str_where . " order by act_vote desc" . $arr_return['pageinfo']['limit']);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$obj_rs['act_pic'] = fun_get::html_url($obj_rs['act_pic']);
			
			$obj_rs['pm'] = $this->get_pm($arr_piao,$obj_rs['act_vote']);
			$arr_return["list"][] = $obj_rs;
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}
	function get_award_lottery() {
		$obj_db = cls_obj::db();
		$arr_where = array("lottery_item_id='" . $this->obj_item['item_id'] . "' and lottery_user_id='" . cls_obj::get("cls_user")->uid . "'");
		$arr_return = array("list" => array());
		$str_where = empty($arr_where) ? "" : " where " . implode(" and " , $arr_where);
		$obj_result = $obj_db->select("select a.*,b.award_name FROM ".cls_config::DB_PRE."vote_award_lottery a left join " . cls_config::DB_PRE . "vote_award b on a.lottery_award_id=b.award_id " . $str_where . " order by lottery_id desc");
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$arr_return["list"][] = $obj_rs;
		}
		return $arr_return;
	}
	function get_group() {
		$arr_list = array();
		$obj_result= cls_obj::db()->select("select * from " . cls_config::DB_PRE . "vote_group where group_item_id='" . $this->obj_item['item_id'] . "' order by group_sort,group_id");
		while($obj_rs = cls_obj::db()->fetch_array($obj_result)) {
			$arr_list[] = $obj_rs;
		}
		return $arr_list;
	}

	function get_detail() {
		$id = (int)fun_get::get("id");
		if(empty($id)) fun_base::url_jump("?");
		cls_obj::db()->on_exe("update " . cls_config::DB_PRE . "vote_act set act_hits=act_hits+1 where act_id='" . $id . "'");
		$detail = cls_obj::db()->edit(cls_config::DB_PRE . "vote_act" , "act_id='" . $id . "'");
		$detail['pics'] = empty($detail['act_pics']) ? array() : explode("|" , $detail['act_pics']);

		$arr_piao = cls_cache::get("aoo_vote_act","vote",1);
		if(empty($arr_piao)) {
			$obj_result = cls_obj::db()->select("select act_vote FROM ".cls_config::DB_PRE."vote_act order by act_vote desc");
			while( $obj_rs = cls_obj::db()->fetch_array($obj_result) ) {
				if(!empty($arr_piao) && $obj_rs['act_vote']==$arr_piao[count($arr_piao)-1]) continue;
				$arr_piao[] = $obj_rs['act_vote'];
			}
			cls_cache::set($arr_piao , "aoo_vote_act","vote");
		}
		
		$detail['pm'] = $this->get_pm($arr_piao,$detail['act_vote']);
		return $detail;
	}
	function on_vote() {
		$uid = cls_obj::get("cls_user")->uid;
		$id = (int)fun_get::get("id");
		//if(empty($uid)) return array("code" => 500 , "msg" => "请登录后再来参与投票");
		$obj_item = $this->obj_item;
		$obj_db = cls_obj::db();
		//查看活动
		if(empty($obj_item) || $obj_item['item_id']<1) return array("code" => 500 , "msg" => "活动不存在");
		if($obj_item['item_start_time']>date("Y-m-d H:i:s")) return array("code" => 500 , "msg" => "活动将于" . $obj_item['item_start_time'] ."开始，请稍后再来.");
		if($obj_item['item_end_time']<date("Y-m-d H:i:s")) return array("code" => 500 , "msg" => "活动已于" . $obj_item['item_end_time'] ."结束，谢谢关注.");
		$share_num = 0;
		//是否分享
		if($this->obj_item['item_share']>0) {
			if($this->obj_item['item_day_limit']>0) {
				$obj_share = $obj_db->get_one("select share_id from " . cls_config::DB_PRE . "other_share where share_user_id='" . $uid . "' and share_date='" . date("Y-m-d") . "' and share_key like '%.vote" . $obj_item['item_id'] . "'");
			} else {
				$obj_share = $obj_db->get_one("select share_id from " . cls_config::DB_PRE . "other_share where share_user_id='" . $uid . "' and  and share_key like '%.vote" . $obj_item['item_id'] . "'");
			}
			if(!empty($obj_share) && !empty($obj_share['share_id'])) {
				if($obj_item['item_day_limit']>0) $obj_item['item_day_limit']++;
				if($obj_item['item_limit']>0) $obj_item['item_limit']++;
			}
		}
		//当天限制
		if($this->obj_item['item_day_limit']>0) {
			if($uid>0) {
				$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_user_id='" . $uid . "' and piao_date='" . date('Y-m-d') . "' and piao_item_id='" . $obj_item['item_id'] . "'");
			} else {
				$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_sid='" . cls_obj::get("cls_session")->id . "' and piao_date='" . date('Y-m-d') . "' and piao_item_id='" . $obj_item['item_id'] . "'");
			}
			if($obj_one && $obj_one['num']>=$obj_item['item_day_limit']) {
				if($this->obj_item['item_day_limit']==1) return array("code" => 500 , "msg" => "今天您已投过票了");
				return array("code" => 500 , "msg" => "每人每天只能投" . $this->obj_item['item_day_limit'] . "票");
			}
		}
		//投票总限制
		if($this->obj_item['item_limit']>0) {
			if($uid>0) {
				$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_user_id='" . $uid . "' and piao_item_id='" . $obj_item['item_id'] . "'");
			} else {
				$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_sid='" . cls_obj::get("cls_session")->id . "' and piao_item_id='" . $obj_item['item_id'] . "'");
			}
			if($obj_one && $obj_one['num']>=$obj_item['item_limit']) {
				if($this->obj_item['item_limit']==1) return array("code" => 500 , "msg" => "您已投过票了");
				return array("code" => 500 , "msg" => "每人只能投" . $this->obj_item['item_limit'] . "票");
			}
		}	
		//重复限制
		if($this->obj_item['item_isrepeat']==0) {
			if($this->obj_item['item_day_limit']>0) {
				if($uid>0) {
					$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_user_id='" . $uid . "' and piao_item_id='" . $obj_item['item_id'] . "' and piao_date='" . date('Y-m-d') . "' and piao_act_id='" . $id . "'");
				} else {
					$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_sid='" . cls_obj::get("cls_session")->id . "' and piao_item_id='" . $obj_item['item_id'] . "' and piao_date='" . date('Y-m-d') . "' and piao_act_id='" . $id . "'");
				}
			} else {
				if($uid>0) {
					$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_user_id='" . $uid . "' and piao_item_id='" . $obj_item['item_id'] . "' and piao_act_id='" . $id . "'");
				} else {
					$obj_one = $obj_db->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "vote_piao where piao_sid='" . cls_obj::get("cls_session")->id . "' and piao_item_id='" . $obj_item['item_id'] . "' and piao_act_id='" . $id . "'");
				}
			}
			if($obj_one && $obj_one['num']>0) return array("code" => 500 , "msg" => "不能重复投票");
		}

		$obj_db->begin("vote");
		$arr = $obj_db->on_exe("update " . cls_config::DB_PRE . "vote_act set act_vote=act_vote+1 where act_id='" . $id . "'");
		if($arr['code'] != 0) {
			$obj_db->rollback("vote");
			return $arr;
		}
		$arr_fields = array(
			"piao_act_id"=>$id,
			"piao_user_id"=>$uid,
			"piao_item_id"=>$obj_item['item_id'],
			"piao_date"=>date("Y-m-d"),
			"piao_datetime"=>date("Y-m-d H:i:s"),
			"piao_ip"=> fun_get::ip(),
			"piao_sid"=>cls_obj::get("cls_session")->id,
		);
		$arr = $obj_db->on_insert(cls_config::DB_PRE."vote_piao",$arr_fields);
		if($arr['code'] != 0) {
			$obj_db->rollback("vote");
			return $arr;
		}
		if($this->obj_item['item_award']==1) {//奖励抽奖
			$arr_fields = array(
				"record_item_id" => $this->obj_item['item_id'],
				"record_user_id" => $uid,
				"record_addtime" => TIME,
				"record_adddate" => date("Y-m-d"),
				"record_sid"=>cls_obj::get("cls_session")->id,
			);
			$arr_msg = $obj_db->on_insert(cls_config::DB_PRE."vote_award_record",$arr_fields);
		}
		$obj_db->commit("vote");
		return $arr;
	}
	function on_join_save() {
		$arr_return = array("code" => 0 , "id"=>0 , "msg" => cls_language::get("save_ok"));
		$obj_one = cls_obj::db()->get_one("select act_id from " . cls_config::DB_PRE . "vote_act where act_user_id='" . cls_obj::get("cls_user")->uid . "' and act_item_id='" . $this->obj_item['item_id'] . "'");
		$id = empty($obj_one) ? 0 : $obj_one['act_id'];
		$arr_fields = array(
			"act_id" => $id,
			"act_name"=>fun_get::post("act_name"),
			"act_ext2"=>fun_get::post("act_ext2"),
			"act_beta"=>fun_get::post("act_beta"),
			"act_group"=>fun_get::post("act_group"),
			"act_linkname"=>fun_get::post("act_linkname"),
			"act_linktel"=>fun_get::post("act_linktel"),
			"act_item_id"=>$this->obj_item['item_id'],
			"act_user_id"=>cls_obj::get("cls_user")->uid,
		);
		$arr_pics = array();
		$arr_pic = fun_get::get("pic",array());
		foreach($arr_pic as $item) {
			if(!empty($item)) $arr_pics[] = $item;
		}
		$arr_fields['act_pics'] = implode("|",$arr_pics);
		$arr_fields['act_pic'] = (count($arr_pics)>0) ? $arr_pics[0] : '';
		$arr_ping = cls_pinyin::get($arr_fields['act_name'] , cls_config::DB_CHARSET);
		$arr_fields["act_ping"] = $arr_ping["style3"];
		$arr = tab_vote_act::on_save($arr_fields);
		if($arr['code']==0) {
			if(isset($arr['id'])) $arr_return['id'] = $arr['id'];
		} else {
			$arr_return['code'] = $arr['code'];
			$arr_return['msg']  = $arr['msg'];
		}
		return $arr_return;
	}
	function get_pm_list() {
		$obj_db = cls_obj::db();
		$arr_list = array();
		$pm = 1;
		$vote = 0;
		$obj_result = $obj_db->select("select act_id,act_name,act_vote from " . cls_config::DB_PRE . "vote_act where act_item_id='" . $this->obj_item['item_id'] . "' and act_state>0 order by act_vote desc");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			if($obj_rs['act_vote']<$vote) $pm++;
			$obj_rs['pm'] = $pm;
			$vote = $obj_rs['act_vote'];
			$arr_list[] = $obj_rs;
		}
		return $arr_list;
	}
}