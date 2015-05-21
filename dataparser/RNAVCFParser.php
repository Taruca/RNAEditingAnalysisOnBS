<?php  
function parseMultiRNAVCFFile($vcfPath) {
	global $infoLog;
	global $con;
	$altColumn = 4;
	$infoColumn = 7;
	$formatColumnIndex = 8;
	$columnLength = 0;
	error_log("Start parsing RNA VCF file...\r\n", 3, $infoLog);
	try {
		$fp = fopen($vcfPath, 'r');
		setAutoCommit(false);
		$lineCount = 0;
		$hasEstablishTable = false;
		while (($line1 = fgets($fp)) != null) {
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
				throw new Exception("There are no samples in this vcf file.\r\n");
			}
			$sections = explode("\t", $line);

			for ($i = $formatColumnIndex + 1; $i < $columnLength; $i++) {
				if (!strpos($sections[$i], ".")) {
					$contain = false;
					$rr = 1;
				} else {
					$contain = true;
					$rr = 2;
				}
				if (strcmp($sections[$altColumn], ".") == 0 || $contain) {
					continue;
				}
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
						$tableName[$j] = $sampleNames[$j] ."_" ."rnavcf";
						deleteTable($tableName[$j]);
//						echo "tableName:" .$tableName[$j] ."<br>";
//						echo "tableBuilders:" .$tableBuilders ."<br>";
						$v = mysqli_query($con, "create table " .$tableName[$j] ."($tableBuilders)");
						if (!$v) {
							throw new Exception("Error create dnatable.\r\n");		
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
						throw new Exception("Error execute sql clause:  $sqlClause\r\n");
				}
				if (++$lineCount % 10000 == 0) {
					commit();
				}
			}
		}
		commit();
		setAutoCommit(true);
	} catch (Exception $e) {
		error_log($e->getMessage(), 3, $infoLog);
	}
	error_log("End parsing RNA VCF file...\r\n", 3, $infoLog);
}
?>