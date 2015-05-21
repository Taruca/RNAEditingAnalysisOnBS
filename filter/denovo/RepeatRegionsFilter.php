<?php  
function performRepeatRegionsFilter($previousTable, $currentTable, $args) {
	global $infoLog;
	global $con;
//	ini_set('max_execution_time', '0');
	createFilterTable($previousTable, $currentTable);
	error_log("Start performing Repeat Regions Filter...\r\n", 3, $infoLog);
	$repeatTable = "repeat_masker";
	try {
		$sqlClause = "insert into " .$currentTable ." select * from " .$previousTable ." where not exists (select * from " 
					.$repeatTable ." where (" .$repeatTable .".chrom= " .$previousTable .".chrom and  " .$repeatTable .".begin<=" 
						.$previousTable .".pos and " .$repeatTable .".end>=" .$previousTable .".pos)) ";
		echo $sqlClause;
		$a = mysqli_query($con, $sqlClause);
		if(!$a) {
			throw new Exception("Error execute sqla clause in performRepeatRegionsFilter\r\n");	
		}
		error_log("Start finding sites in Alu Regions...\r\n", 3, $infoLog);
		$tempTable = getRandomString(10);
		$b = mysqli_query($con, "create temporary table $tempTable like $currentTable");
		if(!$b) {
			throw new Exception("Error execute sqlb clause in performRepeatRegionsFilter\r\n");	
		}
		$c = mysqli_query($con, "insert into  $tempTable select * from $previousTable where exists (select chrom from $repeatTable 
			where $repeatTable.chrom = $previousTable.chrom and $repeatTable.begin<=$previousTable.pos and 
			$repeatTable.end>=$previousTable.pos and $repeatTable.type='SINE/Alu')");
		if(!$c) {
			throw new Exception("Error execute sqlc clause in performRepeatRegionsFilter\r\n");	
		}
		$d = mysqli_query($con, "update $tempTable set alu='T'");
		if(!$d) {
			throw new Exception("Error execute sqld clause in performRepeatRegionsFilter\r\n");	
		}
		$e = mysqli_query($con, "insert into $currentTable select * from $tempTable");
		if(!$e) {
			throw new Exception("Error execute sql clause in performRepeatRegionsFilter\r\n");	
		}
		deleteTable($tempTable);
		error_log("End finding sites in Alu Regions...\r\n", 3, $infoLog);
	} catch (Exception $e) {
		error_log($e->getMessage(), 3, $infoLog);
	}
	error_log("End performing Repeat Regions Filter...\r\n", 3, $infoLog);
}
?>