<?php  
//Unfinshed!!
//function existTable use which database

function setAutoCommit($autoCommit) {
	global $con;
	global $databaseLog;
	try {
		$v = mysqli_autocommit($con,$autoCommit);
		if (!$v) {
			throw new Exception("Can not set commit automatically.\n");
		}
	} catch (SQLException $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

function commit() {
	global $con;
	global $databaseLog;
	try {
		$v = mysqli_commit($con);
		if (!$v) {
			throw new Exception("Error commit to database.\n");
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

function calRowCount ($tableName) {
	global $con;
	$result = mysqli_query($con, "select count(1) from $tableName");
	if ($result != null && next($result)) {
		return mysqli_fetch_array($result);
	} else {
		return 0;
	}
	
}

function hasEstablishTable($darnedTable) {
	global $databaseLog;
	try {
		$v = calRowCount($darnedTable);
		if ($v > 0) {
		return $v;
		}
		else {
			throw new Exception(" \n");
			return false;
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
	
}

function createDatabase($databaseName) {
	try {
		$v = mysqli_query("create database if not exists" .$databaseName);
		if (!$v) {
			throw new Exception('Error create database:' .$databaseName);
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

/*
function getColumnNames($database, $tableName) {
	$columnNames = 
	$rs = 
}
*/

function getCurrentTables($database) {
	global $con;
	$v = mysqli_query($con,"use $database");
	if(!$v) {
		error_log("can not use database: $database when getCurrentTables.", 3 ,$databaseLog);
	}
	$rs = mysqli_query($con, "show tables");
	$i = 0;
	while ($row = mysqli_fetch_array($rs)) {
		$tableLists[$i] = $row;
		$i = $i +1;
	}
	return $tableLists;
}


function deleteTable($tableName) {
	global $con;
	global $databaseLog;
	try {
		$v = mysqli_query($con, "drop table if exists $tableName");
		if (!$v) {
			throw new Exception('Error delete table:' .$tableName ."\n");
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

/*
function deleteTableAndFilters($database, $sampleName) {

}

function getSampleName($tableName) {

}

function useDatabase($databaseName) {

}
*/

function insertClause($sql) {
	global $con;
	try {
		$v = mysqli_query($con, "$sql");
	if (!$v) {
		throw new Exception("Can not insert into data table.\n");
	}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
	return $v;
}

function query1($queryClause) {
		global $con;
		global $databaseLog;
		try {
			$v = mysqli_query($con, "$queryClause");
		if (!$v) {
			throw new Exception("Can not get query results...\n");
		}
		} catch (Exception $e) {
			error_log($e->getMessage(), 3 ,$databaseLog);
		}
		return $v;
}

function query2($table, $columns, $selection, $selectionArgs) {
		
		global $con;
		global $databaseLog;
		global $infoLog;
//		error_log("into query2\r\n", 3, $infoLog);
		$sql = "select ";
		if ($columns == null || count($columns) == 0 || $columns[0] === "*") {
			$sql = $sql ." * ";
		} else {
			$sql = $sql .$columns[0];
			for ($i = 1, $len = count($columns); $i < $len; $i++) { 
				$sql = $sql ."," .$columns[$i];
			}
		}
		$sql = $sql ." from " .$table;
//		error_log("$sql\r\n", 3, $infoLog);
		try {
			if($selection == null || $selectionArgs == null || strlen($selectionArgs) == 0) {
				$v = mysqli_query($con, "$sql");
//				echo "into if<br>";
			} else {
				$sql = $sql ." WHERE " .$selection;
				//此处存疑；
				//
				for ($i=1, $len = strlen($selectionArgs); $i <=$len ; $i++) { 
					$sql = $sql .$selectionArgs[i - 1];
				}
				$v = mysqli_query($con, "$sql");
//				echo "into else<br>";
			}
		if (!$v) {
			throw new Exception("here is a syntax error: " .$sql);
		}
		} catch (Exception $e) {
			error_log($e->getMessage(), 3 ,$databaseLog);
		}
//		echo $sql;
		return $v;
}

/*
function query() {
	global $infoLog;
	error_log("into query\r\n", 3, $infoLog);
	$args = func_get_args();
//	echo $args[0], $args[1][0], $args[1][1], $args[1][2];
	
	function query1($queryClause) {
		global $con;
		global $databaseLog;
		try {
			$v = mysqli_query($con, "$queryClause");
		if (!$v) {
			throw new Exception("Can not get query results...\n");
		}
		} catch (Exception $e) {
			error_log($e->getMessage(), 3 ,$databaseLog);
		}
		return $v;
	}

	function query2($table, $columns, $selection, $selectionArgs) {
		
		global $con;
		global $databaseLog;
		global $infoLog;
		error_log("into query2\r\n", 3, $infoLog);
		$sql = "select ";
		if ($columns == null || count($columns) == 0 || $columns[0] === "*") {
			$sql = $sql ." * ";
		} else {
			$sql = $sql .$columns[0];
			for ($i = 1, $len = count($columns); $i < $len; $i++) { 
				$sql = $sql ."," .$columns[$i];
			}
		}
		$sql = $sql ." from " .$table;
		error_log("$sql\r\n", 3, $infoLog);
		try {
			if($selection == null || $selectionArgs == null || strlen($selectionArgs) == 0) {
				$v = mysqli_query($con, "$sql");
//				echo "into if<br>";
			} else {
				$sql = $sql ." WHERE " .$selection;
				//此处存疑；
				//
				for ($i=1, $len = strlen($selectionArgs); $i <=$len ; $i++) { 
					$sql = $sql .$selectionArgs[i - 1];
				}
				$v = mysqli_query($con, "$sql");
//				echo "into else<br>";
			}
		if (!$v) {
			throw new Exception("here is a syntax error: " .$sql);
		}
		} catch (Exception $e) {
			error_log($e->getMessage(), 3 ,$databaseLog);
		}
//		echo $sql;
		return $v;
	}

	if (func_num_args() == 1) {
		$result = query1($args[0]);
		return $result;
	} elseif(func_num_args() == 4) {
		$result = query2($args[0], $args[1], $args[2], $args[3]);
		return $result;
	}
}
*/

//don't use it
function existTable($tableName) {
//	echo "into existTable";
	$tableLists = getCurrentTables("taruca");
	$v = strstr($tableLists, $tableName);
	if (!$v) {
		$rs = false;
	}else {
		$rs = true;
	}
	return $rs;
}

?>