<?php
class ctl_vote_award_lottery extends mod_vote_award_lottery {

	//Ĭ�����ҳ
	function act_default() {
		//��ҳ�б�
		$this->arr_type = tab_vote_award::get_perms("type");
		$this->arr_list = $this->get_pagelist( );
		return $this->get_view(); //��ʾҳ��
	}
	function act_send() {
		$arr_return = $this->on_send();
		return fun_format::json($arr_return);
	}
	//����ɾ��,����josn����
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}
}