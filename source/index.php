<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
require "inc.php";
if(!file_exists(KJ_DIR_DATA."/install.inc")){
	fun_base::url_jump("install.php");
	exit;
}
if(!cls_obj::get("cls_user")->is_login() && isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] == 'weixinlogin') {
	$arr = cls_obj::get('cls_com')->userapi("login_token" , array("jump_url"=>'' , "plat" => 'wx'));
}

cls_app::on_load("default" , "default");