<?php
/* 快捷订餐系统之多店版
 * 版本号：3.5
 * 官网：http://www.kjcms.com
 * 2016-06-29
 */
//定义常用变量与函数
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
if(@date_default_timezone_get() != "Asia/Shanghai") date_default_timezone_set("Asia/Shanghai");
define('TIME', time());
//定义默认加载文件路径
set_include_path(get_include_path().PATH_SEPARATOR.KJ_DIR_LIB."/cls");
set_include_path(get_include_path().PATH_SEPARATOR.KJ_DIR_LIB."/fun");
set_include_path(get_include_path().PATH_SEPARATOR.KJ_DIR_LIB."/tab");

if(isset($_SERVER['QUERY_STRING'])) fun_format::get($_SERVER['QUERY_STRING']);
if(MAGIC_QUOTES_GPC) {
	$_REQUEST = fun_format::new_stripslashes($_REQUEST);
	$_POST = fun_format::new_stripslashes($_POST);
	$_GET = fun_format::new_stripslashes($_GET);
	if($_COOKIE) $_COOKIE = fun_format::new_stripslashes($_COOKIE);
}

//系统类自动加载函数
function __autoload($msg_cls){
	$arr_x=explode("_",$msg_cls);
	if(count($arr_x)<2) return;
	$str_filename=implode(".",$arr_x).".php";
	include $str_filename;
}