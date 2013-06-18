<?php
include('dewdb.inc');

$cxn1 = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
$cxn2 = mysql_connect($dewhost,$dewname,$dewpswd,true) or die(mysql_error());
mysql_select_db('ShopLog',$cxn1) or die("error opening db: ".mysql_error());
mysql_select_db('Divyaeng',$cxn2) or die("error opening db: ".mysql_error());


//print_r($_POST);


$sf=$_POST['sf'];
$soid=$_POST['soid'];
$ddid=$_POST['dDrawing_ID'];
$u=0;
for($i=0;$i<count($soid);$i++)
{
if(isSet($sf[$i])){
$q="SELECT Operation_Desc FROM Operation WHERE Operation_ID='$soid[$i]';";
	print("<br>$q");
$res = mysql_query($q, $cxn1) or die(mysql_error($cxn1));
$row=mysql_fetch_assoc($res);
$qd="INSERT INTO Operation (Drawing_ID,Operation_Desc) VALUES('$ddid','$row[Operation_Desc]');";
print("<br>$qd");
$resd = mysql_query($qd, $cxn2) or die(mysql_error($cxn2));
$u++;
}

	
}
print("<br>updated $u descriptions");
?>