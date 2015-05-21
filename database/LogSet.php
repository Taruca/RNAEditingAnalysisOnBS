<?php  
function setLog($logpath) {
	ini_set('error_reporting', 'E_ALL');
	ini_set('display_errors', 'Off');
	ini_set('log_errors', 'on');
	ini_set('log_errors_max_len', '1024');
	ini_set('error_log', $logpath);
	//'D:\wamp\www\mycode\rna_editing\logs\info.log'
}
?>