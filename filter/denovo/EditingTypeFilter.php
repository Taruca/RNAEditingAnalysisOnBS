<?php  
//require path/logpath require path need to be modified
//no Timer function

//require 'DatabaseManager.php';
//require 'TableCreator.php';
//require 'NegativeType.php';

function performEditingTypeFilter($previousTable, $currentTable, $args) {
	global $infoLog;
	if ($args == null || strlen($args) == 0) {
//		echo "into if <br>";
		return;
	} elseif (count($args) != 1) {
//			echo "into elseif<br>";
			error_log("Args for Editing Type Filter are incomplete, please have a check. \r\n", 3 ,$infoLog);
	}
//	echo "out ifelse <br>";

	createFilterTable($previousTable, $currentTable);
	error_log("Start executing Editing Type Filter...\r\n", 3, $infoLog); //need timerfunction
	$refAlt = $args;
	$refAlt2 = getNegativeStrandEditingType($refAlt);

//	echo "getNe done<br>";
//	echo $refAlt ."1<br>";
//	echo $refAlt2 ."2<br>";
	$sql11 = substr($refAlt, 0, 1);
	$sql12 = substr($refAlt, 1);
	$sql21 = substr($refAlt2, 0, 1);
	$sql22 = substr($refAlt2, 1);
//	echo "$sql11 11<br>";
//	echo "$sql12 12<br>";
//	echo "$sql21 13<br>";
//	echo "$sql22 14<br>";

	$sql1 = "insert into $currentTable select * from $previousTable WHERE REF='$sql11' AND ALT='$sql12' AND GT!='0/0'";
	try {
		$v = insertClause($sql1);
		if (!$v) {
			throw new Exception('There is a syntax error for SQL clause:' .$sql1 ."\r\n");
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$infoLog);
	}

//	echo "sql1 done <br>";
	$sql2 = "insert into $currentTable select * from $previousTable WHERE REF='$sql21' AND ALT='$sql22' AND GT!='0/0'";
	try {
		$v = insertClause($sql2);
		if (!$v) {
			throw new Exception('There is a syntax error for SQL clause:' .$sql2 ."\r\n");
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$infoLog);
	}
	error_log("End executing Editing Type Filter...\r\n", 3, $infoLog); //need timerfunction
}

function getETFName() {
	return "etfilter";
}
?>