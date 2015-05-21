<?php  
//waiting to test
function performLikelihoodRateFilter($previousTable, $currentTable, $args) {
	global $infoLog;
	global $con;
	if ($args ==null || count($args) == 0) {
		return;
	} else if (count($args) != 2) {
		error_log("Args for Likelihood Rate Test Filter are incomplete, please have a check.\r\n", 3, $infoLog);		
	}
	$dnaVcfTable = $args[0];
	$threshold = (double)($args[1]);
	createFilterTable($previousTable, $currentTable);
	error_log("Start performing Likelihood Rate Test Filter... \r\n", 3, $infoLog);
		$queryClause = "select $previousTable.chrom,$previousTable.pos,$previousTable.AD," .
			"" ."$dnaVcfTable.qual from $previousTable,$dnaVcfTable where $previousTable.chrom=$dnaVcfTable.chrom and 
			$previousTable.pos=$dnaVcfTable.pos";
		$rs = query1($queryClause);
		$i = 0;
		while($row = mysqli_fetch_array($rs)) {
			$chr = (string)$row[0];
			$pos = (int)$row[1];
			$ad = (string)$row[2];
			$qual = (float)$row[3];
			$pb = new SiteBean;
			$pb -> SiteBean1($chr, $pos);
			$pb -> setAd($ad);
			$pb -> setQual($qual);
			$siteBeans[$i] = $pb;
			$i++;
		}
//		echo "rownum" .$i ."<br>";
		setAutoCommit(false);
		$count = 0;
		for ($j = 0; $j < $i; $j++) {
//			echo "into count";
			$str = $siteBeans[$j] -> getAd();
			$section = explode("/", $str);
			$ref = (int)$section[0];
			$alt = (int)$section[1];
			if($ref + $alt > 0) {
				$f_ml = 1.0 * $ref / ($ref + $alt);
				$y = pow($f_ml, $ref) * pow(1 - $f_ml, $alt);
				$y = log($y) / log(10.0);
				$judge = $y + ($siteBeans[$j] -> getQual()) / 10.0;
				if ($judge >= $threshold) {
					$siteChr = $siteBeans[$j] -> getChr();
					$sitePos = $siteBeans[$j] -> getPos();
					insertClause("insert into $currentTable select * from $previousTable where chrom='" .$siteChr 
						."' and pos=" .$sitePos);
					if (++$count % 10000 == 0) {
						commit();
					}
				}
			}
		}
		commit();
		setAutoCommit(true);
	error_log("End performing Likelihood Rate Test Filter... \r\n", 3, $infoLog);
}
?>