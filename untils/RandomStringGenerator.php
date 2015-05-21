<?php  
	function getRandomString($length) {
		if ($length < 1) {
			return null;
		}
		$letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$lettersLength = strlen($letters);
		for ($i=0; $i < $length; $i++) { 
			$randBuffer[$i] = $letters[rand(0,$lettersLength - 1)];
		}
		$randString = implode($randBuffer);
		return $randString;
	}
?>