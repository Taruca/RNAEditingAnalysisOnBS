<?php  
function performSpliceJunctionFilter($previousTable, $currentTable, $args) {
	global $infoLog;
	global $con;

	if ($args == null || $args == 0) {
		return;
	} else if (count($args) != 1) {
		error_log("Args for Splice Junction Filter are incomplete, please have a check.\r\n", 3, $infoLog);
	}
	createFilterTable($previousTable, $currentTable);
	error_log("Start performing Splice Junction Filter...\r\n", 3, $infoLog);
	$spliceJunctionTable = "splice_junction";
	$edge = (int)$args[0];
	try {
		$v = mysqli_query($con, "insert into $currentTable select * from $previousTable where not exists (select chrom from 
			$spliceJunctionTable where ($spliceJunctionTable.type='CDS' and $spliceJunctionTable.chrom=$previousTable.chrom 
				and (($spliceJunctionTable.begin<$previousTable.pos+$edge and $spliceJunctionTable.begin>$previousTable.pos-$edge) 
					or ($spliceJunctionTable.end<$previousTable.pos+$edge and $spliceJunctionTable.end>$previousTable.pos-$edge))))");
		if(!$v) {
			throw new Exception("Error execute sql clause in performSpliceJunctionFilter\r\n");	
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3, $infoLog);
	}
	error_log("End performing Splice Junction Filter...\r\n", 3, $infoLog);
}
?>