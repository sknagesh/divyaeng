<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$cid=$_GET['cid'];

$q="SELECT * FROM Cust_Clarification WHERE Clarification_ID='$cid';";

$res=mysql_query($q) or die(mysql_error());
$r=mysql_affected_rows();

if($r!=0)
{
$row=mysql_fetch_assoc($res);

	$ccpdfpath='/enquiry/'.$row['PDF_Path'];
	print("<a class=\"pdf\" href=\"$ccpdfpath\" target=\"_NEW\" title=\"Opens Part Clarification request in a new TAB\">Clarification Request</a>");

}

?>