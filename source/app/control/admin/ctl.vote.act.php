<?php
class ctl_vote_act extends mod_vote_act {

	//Ĭ�����ҳ
	function act_default() {
		//��ҳ�б�
		$this->arr_list = $this->get_pagelist( );
		return $this->get_view(); //��ʾҳ��
	}
	//�༭ ���� ҳ�� ,��idʱΪ�༭
	function act_edit() {

		$this->editinfo = $this->get_editinfo( fun_get::get('id') );
		$this->arr_state = tab_vote_act::get_perms("state");
		$this->arr_group = $this->get_group();
		return $this->get_view();
	}
	function act_excel() {
		$this->on_excel();
	}
	//�������,����josn����
	function act_save() {
		$arr_return = $this->on_save();
		return fun_format::json($arr_return);
	}
	//����ɾ��,����josn����
	function act_delete() {
		$arr_return = $this->on_delete();
		return fun_format::json($arr_return);
	}
}
?>