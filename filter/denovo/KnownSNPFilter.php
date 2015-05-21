<?php  
function performKnownSNPFilter($previousTable, $currentTable, $args) {
	global $infoLog;
	global $con;
	error_log("Start performing Known SNP Filter...\r\n", 3, $infoLog);
	createFilterTable($previousTable, $currentTable);
	$dbSnpTable = "dbsnp_database";
	try {
		$v = mysqli_query($con, "insert into $currentTable select * from $previousTable where not exists (select chrom from 
			$dbSnpTable where ($dbSnpTable.chrom=$previousTable.chrom and $dbSnpTable.pos=$previousTable.pos))");
		if(!$v) {
			throw new Exception("Error execute sql clause in performKnownSNPFilter\r\n");	
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3, $infoLog);
	}
	error_log("End performing Known SNP Filter...\r\n", 3, $infoLog);
}
?>