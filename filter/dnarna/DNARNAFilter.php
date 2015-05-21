<?php  
function performDNARNAFilter($previousTable, $currentTable, $args) {
	global $infoLog;
	global $con;
	if ($args == null || count($args) == 0) {
		return;
	} else if (count($args) != 2) {
		error_log("Args for DNA-RNA Filter are incomplete, please have a check.\r\n", 3, $infoLog);
	}
	createFilterTable($previousTable, $currentTable);
	error_log("Start performing DNA-RNA Filter...\r\n", 3, $infoLog);
	$dnaVcfTable = $args[0];
	$editingType = $args[1];
	$negativeType = getNegativeStrandEditingType($editingType);
	try {
		$num1 = substr($editingType, 0, 1);
		$num2 = substr($negativeType, 0, 1);
		$v = mysqli_query($con, "insert into $currentTable select * from $previousTable where exists (select chrom from $dnaVcfTable 
			where ($dnaVcfTable.chrom=$previousTable.chrom and $dnaVcfTable.pos=$previousTable.pos and (
				$dnaVcfTable.ref='$num1' or $dnaVcfTable.ref='$num2')))");
		if(!$v) {
			throw new Exception("Error execute sql clause in performDNARNAFilter\r\n");	
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3, $infoLog);
	}
	error_log("End performing DNA-RNA Filter...\r\n", 3, $infoLog);
}
?>