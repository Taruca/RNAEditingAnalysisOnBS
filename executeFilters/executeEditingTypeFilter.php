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

require '../database/LogSet.php';
require '../untils/NegativeType.php';
require '../database/DatabaseManager.php';
require '../database/TableCreator.php';
require '../filter/denovo/EditingTypeFilter.php';

$q=$_GET["q"];
//error_log($q ."\r\n", 3, $databaseLog);
$previousTable1 = "bj22n_rnavcf";
$previousTable2 = "bj22t_rnavcf";
$currentTable1 = "bj22n_etfilter";
$currentTable2 = "bj22t_etfilter";
$args = $q;
performEditingTypeFilter($previousTable1, $currentTable1, $args);
performEditingTypeFilter($previousTable2, $currentTable2, $args);
?>