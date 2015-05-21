<?php  
function parseMultiDNAVCFFile($vcfPath) {
	echo "into parseMultiVCFFile<br>";
	global $infoLog;
	global $databaseLog;
	global $con;
	$refColumn = 3;
	$altColumn = 4;
	$filterColumn = 6;
	$formatColumnIndex = 8;
	$columnLength = 0;
	error_log("Start parsing DNA VCF file...\n", 3, $infoLog);
	//for test
//	$numof = 0;
//	$cnum = 0;
//	$dnum = 0;
//	$pnum = 0;
//	$anum = 0;
//	$linenum = 0;
	try {
//		echo "begin to open file in $vcfPath<br>";
		$fp = fopen($vcfPath, 'r');
//		echo fgets($fp) ."<br>";
		setAutoCommit(false);
		$lineCount = 0;
		$hasEstablishTable = false;
		while (($line1 = fgets($fp)) != null) {
//			$linenum++;
			$line = trim($line1);
			if (strpos($line, "##") === 0) {
//				$numof++;
				continue;
			}
			if (strpos($line, "#") === 0) {
//				echo "explode #<br>";
				$columnStrings = explode("\t", substr($line, 1));
				$columnLength = count($columnStrings);
				$sampleNamesLength = $columnLength - $formatColumnIndex - 1;
//				echo "columnLength:" .$columnLength ."<br>";
//				echo "sampleNamesLength:" .$sampleNamesLength ."<br>";
				for($j = 0; $j < $sampleNamesLength; $j++) {
					$sampleNames[$j] = $columnStrings[$formatColumnIndex + 1 + $j];
				}
//				echo "sampleNamesNum:" .$j ."<br>";
//				$sampleNames = $columnStrings[$formatColumnIndex + 1];
				$tableBuilders = "$columnStrings[0] varchar(30),$columnStrings[1] int,$columnStrings[2] varchar(30),
				$columnStrings[3] varchar(5),$columnStrings[4] varchar(5),$columnStrings[5] float(10,2),
				$columnStrings[6] text,$columnStrings[7] text,";
				continue;
			}
			if ($sampleNames == null) {
				throw new Exception("There are no samples in this vcf file.");
			}
			$sections = explode("\t", $line);
			//			for test
/*				if (strcmp($sections[$altColumn], ".")  != 0) {
					$dnum++;
				}
				if (strcasecmp($sections[$filterColumn], "PASS") != 0) {
					$pnum++;
				}
				if (strcasecmp($sections[$refColumn], "A") != 0) {
					$anum++;
				}
*/

			for ($i = $formatColumnIndex + 1; $i < $columnLength; $i++) {
//用== 代替equals？
				if (!strpos($sections[$i], ".")) {
					$contain = false;
					$rr = 1;
				} else {
					$contain = true;
					$rr = 2;
				}

				if ( strcmp($sections[$altColumn], ".") != 0 || $contain || strcasecmp($sections[$filterColumn], "PASS") != 0 || strcasecmp($sections[$refColumn], "A") != 0) { // 
					continue;
				}
//				echo "2<br>";
				$formatColumns = explode(":", $sections[$formatColumnIndex]);
				$formatLength = count($formatColumns);
				$dataColumns = explode(":", str_replace(",", "/", $sections[$i]));
				$dataColumnLength = count($dataColumns);
				if ($formatLength != $dataColumnLength) {
					continue;
				}

				if (!$hasEstablishTable) {
//					echo "!hasEstablishTable<br>";
					for ($j = 0;$j < $formatLength;$j++) {
						$formatColumn = $formatColumns[$j];
						$tableBuilders = $tableBuilders .$formatColumn ." text,";
//						echo "formatColumn: " .$formatColumn ."<br>";
					}
					$tableBuilders = $tableBuilders ."alu varchar(1) default 'F',index(chrom,pos)";
					for ($j = 0, $len = count($sampleNames); $j < $len; $j++) {
						$tableName[$j] = $sampleNames[$j] ."_" ."dnavcf";
						deleteTable($tableName[$j]);
//						echo "tableName:" .$tableName[$j] ."<br>";
//						echo "tableBuilders:" .$tableBuilders ."<br>";
						$v = mysqli_query($con, "create table " .$tableName[$j] ."($tableBuilders)");
						if (!$v) {
							throw new Exception("Error create dnatable.");		
						}
					}
					commit();
					$hasEstablishTable = true;
				}

				$sqlClause = "insert into " .$tableName[$i - $formatColumnIndex -1] ."(";
				for ($j = 0; $j < $formatColumnIndex; $j++) {
					$sqlClause = $sqlClause .$columnStrings[$j] .",";
				}
				for ($j = 0; $j < $formatLength; $j++) {
					$formatColumn = $formatColumns[$j];
					$sqlClause = $sqlClause .$formatColumn .",";
				}	
				$sqlClause = substr($sqlClause, 0, strlen($sqlClause)-1);
				$sqlClause = $sqlClause .") values('";
				if ( strpos($sections[0], "ch") === 0 && !(strpos($sections[0], "chr") === 0) ) {
					$str = str_replace("ch", "chr", $sections[0]) ."'";
					$sqlClause = $sqlClause .$str;
				} else if (strlen($sections[0]) < 3) {
					$sqlClause = $sqlClause ."chr" .$sections[0] ."'";
				} else {
					$sqlClause = $sqlClause .$sections[0] ."'";
				}
				for ($j = 1; $j < $formatColumnIndex; $j++) {
					$sqlClause = $sqlClause .",'" .$sections[$j] ."'";
				}
				for ($j = 0; $j < count($dataColumns); $j++) {
					$dataColumn = $dataColumns[$j];
					$sqlClause = $sqlClause .",'" .$dataColumn ."'";
				}
				$sqlClause = $sqlClause .")";
//				echo "sqlClause: " .$sqlClause ."<br>";
				$v = mysqli_query($con, $sqlClause);
				if (!$v) {
						throw new Exception("Error execute sql clause:  $sqlClause");
				}
				if (++$lineCount % 10000 == 0) {
					commit();
				}
			}
//			echo "2";
		}
//		echo $linenum ." linenum<br>";
//		echo $cnum ." cnum<br>";
//		echo $dnum ." dnum<br>";
//		echo $pnum ." pnum<br>";
//		echo $anum ." anum<br>";
//		echo $sampleNames[0] ."<br>";
//		echo $sampleNames[1] ."<br>";
//		echo "##: $numof<br>";
		echo "out while<br>";
		commit();
		setAutoCommit(true);
	} catch (Exception $e) {
		error_log($e->getMessage(), 3, $infoLog);
	} 
	error_log("messageEnd parsing DNA VCF file...", 3, $infoLog);
}
?>