<?php  
//finished, wating to be tested
function getNegativeStrandEditingType($editingType) {
//	echo "into getNe<br>";
	if ($editingType == null || strlen($editingType) != 2) {
//		echo "into Neif<br>";
		return null;
	}
	$type = $editingType;
	return getNegativeStrandBase(substr($type, 0, 1)) .getNegativeStrandBase(substr($type, 1, 1));
}

function getNegativeStrandBase($type) {
	switch ($type) {
		case 'A':
			return 'T';
		case 'G':
			return 'C';
		case 'T':
			return 'A';
		case 'C':
			return 'G';
		default:
			return 'T';
	}
}
?>
