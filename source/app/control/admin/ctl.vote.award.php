<?php
class ctl_vote_award extends mod_vote_award {

	//Ĭ�����ҳ
	function act_default() {
		//��ҳ�б�
		$this->arr_list = $this->get_pagelist( );
		return $this->get_view(); //��ʾҳ��
	}
	//�༭ ���� ҳ�� ,��idʱΪ�༭
	function act_edit() {
		$this->arr_type = tab_vote_award::get_perms("type");
		$this->editinfo = $this->get_editinfo( fun_get::get('id') );
		return $this->get_view();
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