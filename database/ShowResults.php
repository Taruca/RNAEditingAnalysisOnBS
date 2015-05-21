<?php  
$databaseLog = 'D:\wamp\www\mycode\rna_editing\logs\database.log';
$infoLog = 'D:\wamp\www\mycode\rna_editing\logs\info.log';

ini_set("memory_limit","1000M");
ini_set('max_execution_time', '0');
$con = mysqli_connect("localhost","taruca","taruca");
if(!$con) {
   error_log('Connect failed:' .mysqli_connect_error() ."\r\n", 3, $databaseLog);
}
else {
  	error_log("Connect successed.\r\n", 3, $databaseLog);
//  	echo "connect db suc <br>";
}
mysqli_select_db($con, "taruca") or die("can not select db");

try {
	$bj22nRNASites = mysqli_query($con, "select id from bj22n_rnavcf");
	$bj22nRNASitesNum = 0;
	while ($row = mysqli_fetch_array($bj22nRNASites)) {
		$bj22nRNASitesNum++;
	}
	$bj22tRNASites = mysqli_query($con, "select id from bj22t_rnavcf"); 
	$bj22tRNASitesNum = 0;
	while ($row = mysqli_fetch_array($bj22tRNASites)) {
		$bj22tRNASitesNum++;
	}
	$RNASitesNum = $bj22nRNASitesNum + $bj22tRNASitesNum;

	$bj22nRNAEditingSites = mysqli_query($con, "select id from bj22n_lrfilter");
	$bj22nRNAEditingSitesNum = 0;
	while ($row = mysqli_fetch_array($bj22nRNAEditingSites)) {
		$bj22nRNAEditingSitesNum++;
	}
	$bj22tRNAEditingSites = mysqli_query($con, "select id from bj22t_lrfilter");
	$bj22tRNAEditingSitesNum = 0;
	while ($row = mysqli_fetch_array($bj22tRNAEditingSites)) {
		$bj22tRNAEditingSitesNum++;
	}
	$RNAEditingSitesNum = $bj22nRNAEditingSitesNum + $bj22tRNAEditingSitesNum;
	$RNANum = array($RNASitesNum, $RNAEditingSitesNum);

	if(!$bj22nRNASites || !$bj22tRNASites ||!$bj22nRNAEditingSites ||!$bj22tRNAEditingSites) {
		throw new Exception("Error execute sql clause in loadDarnedTable().\r\n");	
	}
} catch (Exception $e) {
	error_log($e->getMessage(), 3 ,$infoLog);
}
$str = $RNANum[0] ."/" .$RNANum[1];
echo $str;
?>