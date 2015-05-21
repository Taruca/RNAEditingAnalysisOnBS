<?php  
$databaseLog = 'D:\wamp\www\mycode\rna_editing\logs\database.log';
$infoLog = 'D:\wamp\www\mycode\rna_editing\logs\info.log';
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

require 'database/LogSet.php';
require 'untils/NegativeType.php';
require 'database/DatabaseManager.php';
require 'database/TableCreator.php';
require 'dataparser/DARNEDParser.php';

loadDarnedTable('E:/My Documents/College/thesis design/materials/data/hg19_darned.txt');
?>