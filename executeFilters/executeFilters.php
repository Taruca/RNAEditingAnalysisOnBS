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
require '../untils/RandomStringGenerator.php';
require '../database/DatabaseManager.php';
require '../database/TableCreator.php';
require '../datatypes/SiteBean.php';
require '../filter/denovo/EditingTypeFilter.php';
require '../filter/denovo/QualityControlFilter.php';
require '../filter/denovo/RepeatRegionsFilter.php';
require '../filter/denovo/SpliceJunctionFilter.php';
require '../filter/denovo/KnownSNPFilter.php';
require '../filter/dnarna/DNARNAFilter.php';
require '../filter/dnarna/LikelihoodrateFilter.php';

$et = $_GET["et"];
$q = $_GET["q"];
$dp = $_GET["dp"];
$sjl = $_GET["sjl"];
$rd = $_GET["rd"];

#/*
//EditingTypeFilter
$previousETTable1 = "bj22n_rnavcf";
$previousETTable2 = "bj22t_rnavcf";
$currentETTable1 = "bj22n_etfilter";
$currentETTable2 = "bj22t_etfilter";
$argsET = $et;
performEditingTypeFilter($previousETTable1, $currentETTable1, $argsET);
performEditingTypeFilter($previousETTable2, $currentETTable2, $argsET);
#*/

#/*
//QualityControlFilter
$previousQCTable1 = "bj22n_etfilter";
$previousQCTable2 = "bj22t_etfilter";
$currentQCTable1 = "bj22n_qcfilter";
$currentQCTable2 = "bj22t_qcfilter";
$argsQC = array($q, $dp);
peformQualityControlFilter($previousQCTable1, $currentQCTable1, $argsQC);
peformQualityControlFilter($previousQCTable2, $currentQCTable2, $argsQC);
#*/

#/*
//RepeatRegionsFilter
$previousRRTable1 = "bj22n_qcfilter";
$previousRRTable2 = "bj22t_qcfilter";
$currentRRTable1 = "bj22n_rrfilter";
$currentRRTable2 = "bj22t_rrfilter";
$argsRR = array(0);
performRepeatRegionsFilter($previousRRTable1, $currentRRTable1, $argsRR);
performRepeatRegionsFilter($previousRRTable2, $currentRRTable2, $argsRR);
#*/

#/*
//Splice-junction filter
$previousSJTable1 = "bj22n_rrfilter";
$previousSJTable2 = "bj22t_rrfilter";
$currentSJTable1 = "bj22n_sjfilter";
$currentSJTable2 = "bj22t_sjfilter";
$argsSJ = array($sjl);
performSpliceJunctionFilter($previousSJTable1, $currentSJTable1, $argsSJ);
performSpliceJunctionFilter($previousSJTable2, $currentSJTable2, $argsSJ);
#*/

#/*
//dbSNPfilter
$previousSNPTable1 = "bj22n_sjfilter";
$previousSNPTable2 = "bj22t_sjfilter";
$currentSNPTable1 = "bj22n_snpfilter";
$currentSNPTable2 = "bj22t_snpfilter";
$argsSNP = array(0);
performKnownSNPFilter($previousSNPTable1, $currentSNPTable1, $argsSNP);
performKnownSNPFilter($previousSNPTable2, $currentSNPTable2, $argsSNP);
#*/

#/*
//DNARNAFilter
$previousDRTable1 = "bj22n_snpfilter";
$previousDRTable2 = "bj22t_snpfilter";
$currentDRTable1 = "bj22n_drfilter";
$currentDRTable2 = "bj22t_drfilter";
$argsDR1 = array("bj22n_dnavcf", $et);
$argsDR2 = array("bj22t_dnavcf", $et);
performDNARNAFilter($previousDRTable1, $currentDRTable1, $argsDR1);
performDNARNAFilter($previousDRTable2, $currentDRTable2, $argsDR2);
#*/

//Likelihood ratio test
$previousLRTable1 = "bj22n_drfilter";
$previousLRTable2 = "bj22t_drfilter";
$currentLRTable1 = "bj22n_lrfilter";
$currentLRTable2 = "bj22t_lrfilter";
$argsLR1 = array("bj22n_dnavcf", $rd);
$argsLR2 = array("bj22t_dnavcf", $rd);
performLikelihoodrateFilter($previousLRTable1, $currentLRTable1, $argsLR1);
performLikelihoodrateFilter($previousLRTable2, $currentLRTable2, $argsLR2);

?>