<?php  
function peformQualityControlFilter($previousTable, $currentTable, $args) {
	global $infoLog;
	global $con;
	global $databaseLog;
	if (!$con) {
		error_log("database disconnected.\r\n", 3, $databaseLog);
	}
//	set_time_limit(0);
	$COMMIT_COUNTS_PER_ONCE = 10000;
	if ($args == null || count($args) == 0) {
			return;
		} elseif (count($args) != 2) {
			error_log("Args for Quality Control Filter are incomplete, please have a check. \r\n", 3 ,$infoLog);
	}
	$quality = (double)($args[0]);
	$depth = (int)($args[1]);
	createFilterTable($previousTable, $currentTable);
	error_log("Start performing Quality Control Filter...\r\n", 3, $infoLog); //need timerfunction
	try {
//		error_log("into try\r\n", 3, $infoLog);
		$count = 0;
		$str = array("CHROM", "POS", "AD");
		$rs = query2($previousTable, $str, null, null);
//		error_log("2\r\n", 3, $infoLog);
		$i = 0;
		while($row = mysqli_fetch_array($rs)) {
//或许row需强转成string
			if($row[2] != null) {
				$siteBean = new SiteBean;
				$siteBean->SiteBean1($row[0], $row[1]);
				$siteBean->setAd($row[2]);
				$siteBeans[$i] = $siteBean;
				$i = $i + 1;
			}
		}
		setAutoCommit(false);
//		error_log("$i\r\n", 3, $infoLog);
		for ($j = 0; $j < $i; $j++) { 
			$str = $siteBeans[$j]->getAd();
			$section = explode("/", $str);
			$ref_n = (int)($section[0]);
			$alt_n = (int)($section[1]);
			$pos = $siteBeans[$j]->getPos();
			$chr = $siteBeans[$j]->getChr();
			if ($ref_n + $alt_n >=$depth) {
				$v = mysqli_query($con, "insert into $currentTable (select * from $previousTable where filter='PASS' 
					and pos=$pos and qual>=$quality and chrom='$chr')");
				if (++$count % $COMMIT_COUNTS_PER_ONCE == 0) {
					commit();
				}
			}
		}
		commit();
		setAutoCommit(true);
		if (!$v) {
			throw new Exception("Error execute sql clause in QualityControlFilter:performFilter().\r\n");
		}
	} catch (Exception $e) {
		error_log($e->getMessage(), 3 ,$infoLog);
	}
	error_log("End performing Quality Control Filter...\r\n", 3 ,$infoLog);
}

function getQCFName() {
	return "qcfilter";
}
?>