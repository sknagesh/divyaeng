<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$insertid=$_GET['iid'];

$ok=0;
$query="SELECT * FROM Insert_Qty WHERE Insert_ID='$insertid';";
//print($query);

$res=mysql_query($query) or die(mysql_error());
$row=mysql_fetch_assoc($res);
$nt=mysql_affected_rows();
if($nt!=0)
{
$textmessage="Stock ".$row['Insert_Qty']." Nos";	
}else{
	$textmessage="This Insert is Not in Stock";
}

print($textmessage);
?>