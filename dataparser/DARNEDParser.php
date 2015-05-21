<?php  
function createDARNEDTable($tableName) {
	global $databaseLog;
	global $infoLog;
	global $con;
	try {
		$tableLists = getCurrentTables("taruca");
		$v = in_array($tableName, $tableLists);
		if (!$v) {
			$rs = false;
		}else {
			$rs = true;
		}
		if (!$rs) {
			$columnName = array("chrom", "coordinate", "strand", "inchr", "inrna");
			$columnParams = array("varchar(30)","int", "varchar(5)", "varchar(5)", "varchar(5)");
			$index = "index(chrom,coordinate)";
			$v = createReferenceTable($tableName, $columnName, $columnParams, $index);
			if (!$v) {
			throw new Exception("Error create DNRNED table\n");
			}
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

function loadDarnedTable($darnedPath) {
	global $infoLog;
	global $con;
	error_log("Start loading DARNED file into database...\r\n", 3 ,$infoLog);
	$darnedTable = "darned_database";
	if(!hasEstablishTable($darnedTable)) {
		createDARNEDTable($darnedTable);
		try {
			$count = 0;
			setAutoCommit(false);
			$fp = fopen($darnedPath, 'r');
			fgets($fp);
			while (($line = fgets($fp)) != null) {
				$line1 = trim($line);
				$section = explode("\t", $line1);
				$stringBulider = "insert into " .$darnedTable ."(chrom,coordinate,strand,inchr,inrna) values(";
				for ($i = 0; $i < 5; $i++) {
					if ($i == 0) {
						$stringBulider = $stringBulider ."'chr" .$section[$i] ."',";
					} else if ($i == 4) {
						$sec = str_replace("I", "G", $section[$i]);
						$stringBulider = $stringBulider ."'" .$sec ."'";
					} else if ($i == 1) {
						$stringBulider = $stringBulider .$section[$i] .",";
					} else {
						$stringBulider = $stringBulider ."'" .$section[$i] ."',";
					}
				}
				$stringBulider = $stringBulider .")";
				$v = mysqli_query($con, $stringBulider);
				if (!$v) {
				throw new Exception("Error execute sql clause in loadDarnedTable().\r\n");
				}
				if (++$count % 10000 == 0) {
					commit();
				}
				commit();
				setAutoCommit(true);
			}
		} catch (Exception $e) {
			error_log($e->getMessage(), 3 ,$infoLog);
		}
	}
	error_log("End loading DARNED file into database...\r\n", 3 ,$infoLog);
}
?>