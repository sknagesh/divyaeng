<?php
include('dewdb.inc');

$cxn1 = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
$cxn2 = mysql_connect($dewhost,$dewname,$dewpswd,true) or die(mysql_error());
mysql_select_db('Process',$cxn1) or die("error opening db: ".mysql_error());
mysql_select_db('Divyaeng',$cxn2) or die("error opening db: ".mysql_error());


print_r($_POST);


$soid=$_POST['sOperation_ID'];
$doid=$_POST['dOperation_ID'];
$q="SELECT * FROM Ope_Tools WHERE Operation_ID='$soid';";
print("<br>$q");
$res = mysql_query($q, $cxn1) or die(mysql_error($cxn1));
$n=0;
while($row=mysql_fetch_assoc($res))
{
	$qd="INSERT INTO Ope_Tool 
	(Operation_ID,
	Tool_ID_1,
	Insert_ID_1,
	Holder_ID_1,
	Tool_ID_2,
	Insert_ID_2,
	Ope_Tool_Desc,
	Ope_Tool_OH,
	Ope_Tool_Life)
	VALUES ('$doid','$row[Tool_ID_1]','$row[Ope_Insert_ID]',
	'$row[Holder_ID]','$row[Tool_ID_2]','','$row[Ope_Tool_Desc]','$row[Ope_Tool_OH]','$row[Ope_Tool_Life]');";
	print("<br>$qd");
	$resd = mysql_query($qd, $cxn2) or die(mysql_error($cxn2));
	$n++;
}


print("<br>added $n tools");

?>