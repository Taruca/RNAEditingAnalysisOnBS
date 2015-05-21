<?php  
//Unfinshed!! stringBuilder function
//require path/logpath need to be modified

//require 'DatabaseManager.php';

function createFilterTable($refTable, $tableName) {
	global $databaseLog;
	global $con;
	try {
		deleteTable($tableName);
		$sqlClause = "create table $tableName like $refTable";
		$v = mysqli_query($con, $sqlClause);
		if (!$v) {
			throw new Exception("There is a syntax error for SQL clause:" .$sqlClause ."\n");
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

function createFisherExactTestTable($refTable, $darnedResultTable) {
	global $databaseLog;
	deleteTable($darnedResultTable);
	createFilterTable($refTable, $darnedResultTable);
	try {
		$v = mysqli_query("alter table" .$darnedResultTable ."add level float,add pvalue float,add fdr float");
		if (!$v) {
			throw new Exception("Can not create Fisher Exact Test Table.\n");
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}


function createReferenceTable($tableName, $columnNames, $columnParams, $index) {
	global $databaseLog;
	global $con;
	if($columnNames == null || $columnParams == null || count($columnNames) == 0 || count($columnNames) != count($columnParams)) {
		error_log("Column names and column parameters can not be null or zero-length.", 3 , $databaseLog);
	}
	$stringBuilder = "create table if not exists $tableName($columnNames[0] $columnParams[0]";
	for($i = 1, $len = count($columnNames); $i < $len; $i++) {
		$stringBuilder = $stringBuilder .", $columnNames[$i] $columnParams[$i]";
	}
	$stringBuilder = $stringBuilder .",$index)";
	try {
		$v = mysqli_query($con, $stringBuilder);
		if (!$v) {
			throw new Exception("There is a syntax error for SQL clause: $stringBuilder\n");
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
	return $v;
}


?>