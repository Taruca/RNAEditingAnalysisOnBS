<?php
// Fill up array with names
$a[1]="Anna";
$a[2]="Brittany";
$a[3]="Cinderella";
$a[4]="Diana";
$a[5]="Eva";
$a[6]="Fiona";
$a[7]="Gunda";
$a[8]="Hege";
$a[9]="Inga";
$a[10]="Johanna";
$a[11]="Kitty";
$a[12]="Linda";
$a[13]="Nina";
$a[14]="Ophelia";
$a[15]="Petunia";
$a[16]="Amanda";
$a[1]="Raquel";
$a[18]="Cindy";
$a[19]="Doris";
$a[20]="Eve";
$a[21]="Evita";
$a[22]="Sunniva";
$a[23]="Tove";
$a[24]="Unni";
$a[25]="Violet";
$a[26]="Liza";
$a[27]="Elizabeth";
$a[28]="Ellen";
$a[29]="Wenche";
$a[30]="Vicky";

//get the q parameter from URL
$q=$_GET["q"];

//lookup all hints from array if length of q>0
if (strlen($q) > 0)
{
$hint="";
for($i=0; $i<count($a); $i++)
  {
  if (strtolower($q)==strtolower(substr($a[$i],0,strlen($q))))
    {
    if ($hint=="")
      {
      $hint=$a[$i];
      }
    else
      {
      $hint=$hint." , ".$a[$i];
      }
    }
  }
}

//Set output to "no suggestion" if no hint were found
//or to the correct values
if ($hint == "")
{
$response="no suggestion";
}
else
{
$response=$hint;
}

//output the response
echo $response;
?>