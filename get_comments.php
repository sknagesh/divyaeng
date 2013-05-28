<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$id=$_GET['id'];
$value=$_GET['value'];

$query="SELECT * FROM Dimn_Comment WHERE Desc_ID='$value';";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);
$no=mysql_affected_rows();

if($no!=0)
{
	print("1");
}else{
	print("0");
}

?>