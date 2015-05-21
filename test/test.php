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

require 'database/LogSet.php';
require 'untils/NegativeType.php';
require 'untils/RandomStringGenerator.php';
require 'database/DatabaseManager.php';
require 'database/TableCreator.php';
require 'datatypes/SiteBean.php';
require 'filter/denovo/EditingTypeFilter.php';
require 'filter/denovo/QualityControlFilter.php';
require 'filter/denovo/RepeatRegionsFilter.php';
require 'filter/denovo/SpliceJunctionFilter.php';
require 'filter/denovo/KnownSNPFilter.php';
require 'filter/dnarna/DNARNAFilter.php';
require 'filter/dnarna/LikelihoodrateFilter.php';

//$logpath = 'D:\wamp\www\mycode\rna_editing\logs\info.log';
//setlog($logpath);

/*
$previousTable = "bj22n_rnavcf";
$currentTable = "testtable";
$args = "AG";
performEditingTypeFilter($previousTable, $currentTable, $args);
*/

/*
$previousTable = "testtable";
$currentTable = "testtableQCF";
$args = array("20", "6");
peformQualityControlFilter($previousTable, $currentTable, $args);
*/

/*
$previousTable = "testtableQCF";
$currentTable = "testtableREF";
$args = array(0);
performRepeatRegionsFilter($previousTable, $currentTable, $args);
*/

/*
$previousTable = "testtableREF";
$currentTable = "testtableSJF";
$args = array("2");
performSpliceJunctionFilter($previousTable, $currentTable, $args);
*/

/*
$previousTable = "testtableSJF";
$currentTable = "testtableKSnpF";
$args = array(0);
performKnownSNPFilter($previousTable, $currentTable, $args);
*/

/*
$previousTable = "testtableKSnpF";
$currentTable = "testtableDRF";
$dnavcfTableName = "bj22n_dnavcf";
$args = array($dnavcfTableName, "AG");
performDNARNAFilter($previousTable, $currentTable, $args);
*/

#/*
$previousTable = "testtableDRF";
$currentTable = "testtableLRF";
$dnavcfTableName = "bj22n_dnavcf";
$args = array($dnavcfTableName, "4");
performLikelihoodrateFilter($previousTable, $currentTable, $args);
#*/
echo "sucessed llr<br>";
?>