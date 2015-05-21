<?php  

/*
function readRFile($fp) {
	echo "into readRFile<br>";
	global $con;
	define('EACH_NUM', 1000);
	$array = array();
	$total_line = 0;
	$k = 0;
	if($fp){  
		echo "begin count the num of line<br>";
    	while(stream_get_line($fp, 10000, "\r\n")){  
        	$total_line++;  
    	}
    }
    echo "total linenum: $total_line<br>";
    $num = ceil($total_line/EACH_NUM);
    $mod = fmod($total_line,EACH_NUM);
    echo "num: $num, mod: $mod <br>";
    rewind($fp);
    if (feof($fp)) {echo "file end<br>";} else {echo "file not end<br>";}
	if(empty($array)) {
		echo "into get line";
		for ($i = 1; $i < $num; $i++) { 
			for ($j = 0; $j < EACH_NUM; $j++) {
				$array[$k] = stream_get_line($fp, 10000, "\n");
				$k++;
			}
		}
		for ($a = 0; $a < $mod; $a++) { 
			$array[$k] = stream_get_line($fp, 10000, "\n");
			$k++;
		}
	}
	return $array;
}
*/

function createRepeatRegionsTable($tableName) {
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
			$columnName = array("chrom", "begin", "end", "type");
			$columnParams = array("varchar(30)", "int", "int", "varchar(40)");
			$index = "index(chrom,begin,end)";
			$v = createReferenceTable($tableName, $columnName, $columnParams, $index);
			if (!$v) {
			throw new Exception("Error create repeat regions table\r\n");
			}
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
}

function loadRepeatTable($repeatPath) {
	global $con;
	global $infoLog;
	global $databaseLog;
	ini_set("memory_limit","800M");
	ini_set('max_execution_time', '0');
	error_log("Start loading RepeatMasker file into database...\r\n", 3 ,$infoLog);
	$repeatTable = "repeat_masker";
	try {
		if(!hasEstablishTable($repeatTable)) {
//			echo "ready to create<br>";
			createRepeatRegionsTable($repeatTable);
			setAutoCommit(false);
			$count = 0;
			$fp = fopen($repeatPath, 'r');
			fgets($fp);
			fgets($fp);
			fgets($fp);
/*
			$line = fgets($fp);
			$line1 = trim($line);
			$line2 = preg_replace("/\s(?=\s)/","\\1",$line1);
			echo $line1 ."<br>";
			error_log($line2, 3 ,'D:\wamp\www\mycode\rna_editing\logs\RMP.log');
			$section = explode(" ", $line2);
			for($i=0;$i<14;$i++) {
				echo $section[$i] ."$i<br>";
			}
			echo preg_replace("/\s(?=\s)/","\\1",trim(fgets($fp)));
			echo preg_replace("/\s(?=\s)/","\\1",trim(fgets($fp)));
*/
//			$fileAry = readRFile($fp);
// /*
			while (($line = fgets($fp)) != null) {
//				echo "into while";
				$line1 = trim($line);
				$section = explode(" ", preg_replace("/\s(?=\s)/", "\\1", $line1)); # /[\s]+/
				$v = mysqli_query($con, "insert into $repeatTable(chrom,begin,end,type) values('$section[4]','$section[5]','$section[6]','$section[10]')");
				if (++$count %10000 == 0) {
					commit();
				}
			}
			commit();
// */
			setAutoCommit(true);
			if (!$v) {
			throw new Exception("Error execute sql clause in loadRepeatTable()\n");
			}
		} 
	}catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$databaseLog);
	}
	fclose($fp);
	error_log("End loading RepeatMasker file into database...\r\n", 3 ,$infoLog);
}
?>