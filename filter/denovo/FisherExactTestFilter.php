<?php  
class PValueInfo extends SiteBean {
	$isInDarnedDB = false;
	$refCount = 0;
	$altCount = 0;

	function PValueInfo($chr, Spos, $id, $ref, $alt, $qual, $filter, $info, 
		$gt, $ad, $dp, $gq, $pl, $alu) {
		parent::SiteBean2($chr, Spos, $id, $ref, $alt, $qual, $filter, $info, 
		$gt, $ad, $dp, $gq, $pl, $alu);
	}

	function setInDarnedDB($isInDarnedDB) {
		$this->isInDarnedDB = $isInDarnedDB; 
	}

	function toString() {
		$str = "'" .getChr() ."'," .getPos() .",'" .getId() ."','" .getRef() ."','" .getAlt() ."'," .getQual() .",'" .getFilter() ."'," 
		."'" .getInfo() ."','" .getGt() ."','" .getAd() ."','" .getDp() ."','" .getGq() ."','" .getPl() ."','" .getIsAlu() ."'";
		return $str;
	}
}

function getExpectedInfo($refTable, $editingType) {
	global $infoLog;
	$darnerTable = "darned_database";
	try {
		$rs = query($refTable, null, null, null);
		$i = 0;
		while ($row = mysqli_fetch_array($rs)) {
			$info = new PValueInfo;
			$info->PvalueInfo( (string)$row[0], (int)$row[1], (string)$row[2], substr((string)$row[3], 0, 1), substr((string)$row[4], 0, 1), 
				(float)$row[5], (string)$row[6], (string)$row[7], (string)$row[8], (string)$row[9], (string)$row[10], (string)$row[11], 
				(string)$row[12], (string)$row[13] );
			$sections = explode("/", $info->getAd());
			$info->refCount = (int)($sections[0]);
			$info->altCount = (int)($sections[1]);
			$valueInfos[$i] = $info;
			$i++;
		}

		$negativeType = getNegativeStrandEditingType($editingType);
		$editingTypes = str_split($editingType);
		$negativeTypes = str_split($negativeType);
		$stringBuilder = "select " .$refTable .".* from " .$refTable ." INNER JOIN " .$darnerTable ." ON " .$refTable .".chrom=" .$darnerTable 
			.".chrom AND " .$refTable .".pos=" .$darnerTable .".coordinate AND (" .$darnerTable ."inchr='" .$editingTypes[0] ."' AND " .$darnerTable 
			.".inrna='" .$editingTypes[1] ."' OR " .$darnerTable .".inchr='" .$negativeTypes[0] ."' AND " .$darnerTable .".inrna='" .$negativeTypes[1] 
			."')";
		error_log($stringBuilder ."\r\n", 3, $infoLog);
		$rs = query($stringBuilder);
		error_log($stringBuilder ."\r\n", 3, $infoLog);
		while($row = mysqli_fetch_array($rs)) {
			for ($j = 0; $j < $i; $j++) {
				if(strcmp($valueInfos[$j]->getChr(), (string)$row[0]) && $valueInfos[$j] == (int)$row[1]) {
					$valueInfos[$j]->setInDarnedDB(true);
					break;
				}
			}
		}
		return $valueInfos;
	} catch (Exception $e) {
		return null;
	}
}

function excuteFETFilter($previousTable, $fetResultTable, $refAlt) {
	global $infoLog;
	error_log("Start performing Fisher's Exact Test Filter...\r\n", 3, $infoLog);
	$valueInfos = getExpectedInfo($previousTable, $refAlt);
	$knownAlt = 0;
	$knownRef = 0;
	for($i = 0; $i < count($valueInfos); $i++) {
		if($valueInfos[$i]->isInDarnedDB) {
			$knownAlt += $valueInfos[$i]->altCount;
			$knownRef += $valueInfos[$i]->refCount;
		} else {
			$knownRef += ($valueInfos[$i]->altCount) + ($valueInfos[$i]->refCount);
		}
	}
	$knownAlt = round($knownAlt / count($valueInfos));
	$knownRef = round($knownRef / count($valueInfos));
}
?>