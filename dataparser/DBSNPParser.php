<?php  
function createDBSNPTable($tableName) {
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
		if (!$rs) {
			$columnName = array("chrom", "pos");
			$columnParams = array("varchar(30)", "int");
			$index = "index(chrom,pos)";
			$v = createReferenceTable($tableName, $columnName, $columnParams, $index);
			if (!$v) {
			throw new Exception("Error create dbSNP table\n");
			}
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

function loadDbSNPTable($dbSNPPath) {
	global $con;
	global $infoLog;
	global $databaseLog;
	ini_set("memory_limit","800M");
	ini_set('max_execution_time', '0');

	error_log("Start loading dbSNP file into database...\n", 3 ,$infoLog);
	$dbSNPTable = "dbsnp_database";
	try {
		if (!hasEstablishTable($dbSNPTable)) {
			createDBSNPTable($dbSNPTable);
			$count = 0;
			$fp = fopen($dbSNPPath, 'r');
			while ($line = fgets($fp) != null) {
				if (strpos($line, "#") === 0) {
					$count++;
				} else {
					break;
				}
			}
			fclose($fp);
			$v = mysqli_query($con, "load data local infile '$dbSNPPath' into table $dbSNPTable 
				fields terminated by '\t' lines terminated by '\n' IGNORE $count LINES");
			if (!$v) {
				throw new Exception("Error execute sql clause in loadDbSNPTable().\n");
			}
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$infoLog);
	}
	error_log("End loading dbSNP file into database...\n", 3 ,$infoLog);
}
?>