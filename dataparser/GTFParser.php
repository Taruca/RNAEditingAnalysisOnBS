<?php  
function createSpliceJunctionTable($tableName) {
	global $databaseLog;
	global $infoLog;
	try {
		$tableLists = getCurrentTables("taruca");
		$v = in_array($tableName, $tableLists);
		if (!$v) {
			$rs = false;
		}else {
			$rs = true;
		}
		if (!$rs) { //existTable($tableName)
			$columnName = array("chrom", "ref", "type", "begin", "end", "score", "strand", "frame", "info");
			$columnParams = array("varchar(30)", "varchar(30)", "varchar(10)", "int", "int", "float(8,6)", "varchar(1)", "varchar(1)", "varchar(100)");
			$index = "index(chrom,type)";
			$v = createReferenceTable($tableName, $columnName, $columnParams, $index);
			if (!$v) {
			throw new Exception("Error create Splice Junction table\n");
			}
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

function loadSpliceJunctionTable($spliceJunctionPath) {
//	echo $spliceJunctionPath;
	global $con;
	global $infoLog;
	global $databaseLog;
	ini_set("memory_limit","800M");
	ini_set('max_execution_time', '0');
	error_log("Start loading Gene Annotation File into database...\n", 3 ,$infoLog);
	$spliceJunctionTable = "splice_junction";
	if(!hasEstablishTable($spliceJunctionTable)) {
		createSpliceJunctionTable($spliceJunctionTable);
		try {
			$v = mysqli_query($con, "load data local infile '$spliceJunctionPath' into table $spliceJunctionTable fields terminated 
				by '\t' lines terminated by '\n'");
			if (!$v) {
			throw new Exception("Error execute sql clause in loadSpliceJunctionTable()\n");
			}
		} catch (Exception $e) {
			error_log($e->getMessage(), 3 ,$databaseLog);
		}
	}
	error_log("End loading Gene Annotation File into database...\n", 3 ,$infoLog);
}
?>