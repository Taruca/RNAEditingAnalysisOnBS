<?php  
function createRefSeqGeneTable($tableName) {
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
			$columnName = array("bin", "name", "chrom", "strand", "txStart", "txEnd", "cdsStart", "cdsEnd","exonCount", 
				"exonStarts", "exonEnds", "score", "name2", "cdsStartStat", "cdsEndStat", "exonFrames");
			$columnParams = array("int", "varchar(255)", "varchar(255)", "varchar(1)", "int", "int", "int", "int", "int", 
				"longblob", "longblob", "int", "varchar(255)", "varchar(8)", "varchar(8)", "longblob");
			$index = "index(chrom,txStart,txEnd)";
			$v = createReferenceTable($tableName, $columnName, $columnParams, $index);
			if (!$v) {
			throw new Exception("Error create RefSeqGene table\r\n");
			}
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

function loadRefSeqGeneTable($refSeqGenePath) {
	error_log("Start loading Ref Seq Gene File into database...\r\n", 3 ,$infoLog);
	$refseqGeneTableName = "reference_gene";
	if (!hasEstablishTable($refseqGeneTableName)) {
		createRefSeqGeneTable($refseqGeneTableName);
		try {
			$v = mysqli_query($con, "load data local infile '" .$refSeqGenePath ."' into table " .$refseqGeneTableName ." fields terminated" 
				."by '\t' lines terminated by '\n'");
			if (!$v) {
			throw new Exception("Error execute sql clause in loadRefSeqGeneTable().\r\n");
			}
		} catch (Exception $e) {
			error_log($e->getMessage(), 3 ,$infoLog);
		}
	}
	error_log("End loading Ref Seq Gene File into database...\r\n", 3 ,$infoLog);
}
?>