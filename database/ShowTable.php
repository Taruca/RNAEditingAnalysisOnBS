<?php  
$databaseLog = 'D:\wamp\www\mycode\rna_editing\logs\database.log';
$infoLog = 'D:\wamp\www\mycode\rna_editing\logs\info.log';

ini_set("memory_limit","1000M");
ini_set('max_execution_time', '0');
$con = mysqli_connect("localhost","taruca","taruca");
if(!$con) {
   error_log('Connect failed:' .mysqli_connect_error() ."\r\n", 3, $databaseLog);
}
else {
  	error_log("Connect successed.\r\n", 3, $databaseLog);
//  	echo "connect db suc <br>";
}
mysqli_select_db($con, "taruca") or die("can not select db");

echo "<center><table border='1' cellspacing='0' cellspadding='0'>" 
	."<tr>" 
	."<th>CHROM</th>"
	."<th>POS</th>"
	."<th>ID</th>"
	."<th>REF</th>"
	."<th>ALT</th>"
	."<th>QUAL</th>"
	."<th>FILTER</th>"
	."<th>INFO</th>"
	."<th>GT</th>"
	."<th>AD</th>"
	."<th>DP</th>"
	."<th>GQ</th>"
	."<th>PL</th>"
	."<th>ALU</th>"
	."</tr>";

$result = mysqli_query($con, "select * from bj22t_lrfilter");

while ($row = mysqli_fetch_array($result)) {
	echo "<tr>"
		."<td>" .$row['CHROM'] ."</td>"
		."<td>" .$row['POS'] ."</td>"
		."<td>" .$row['ID'] ."</td>"
		."<td>" .$row['REF'] ."</td>"
		."<td>" .$row['ALT'] ."</td>"
		."<td>" .$row['QUAL'] ."</td>"
		."<td>" .$row['FILTER'] ."</td>"
		."<td>" .$row['INFO'] ."</td>"
		."<td>" .$row['GT'] ."</td>"
		."<td>" .$row['AD'] ."</td>"
		."<td>" .$row['DP'] ."</td>"
		."<td>" .$row['GQ'] ."</td>"
		."<td>" .$row['PL'] ."</td>"
		."<td>" .$row['alu'] ."</td>"
		."</tr>";
}
echo "</table></center>";

?>