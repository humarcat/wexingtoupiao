<?php
include dirname(__FILE__) . "/qrcode/qrlib.php";
class cls_qrcode {
	static function png( $url , $size = 5 , $is_replace = false) {
		$filename = md5($url) . '-' . $size;
		$file = "/qrcode/" . date("Y-m") . "/" . $filename . ".png";
		$pic = KJ_DIR_UPLOAD_UEL . $file;
		$file = KJ_DIR_UPLOAD . $file;
		if(file_exists($file) && $is_replace) return array('url' => $pic , 'path' => $file);
		fun_file::dir_create(dirname($file));
		QRcode::png($url , $file , "H", $size, 2);
		return array('url' => $pic , 'path' => $file);
	}
}