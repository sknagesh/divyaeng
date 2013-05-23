<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$toolid=$_GET['tid'];

$ok=0;
$query="SELECT Qty_New,Qty_Resharpened,Qty_New_SF,Qty_Resharpened_SF FROM Tool_Qty WHERE Tool_ID='$toolid';";
//print($query);

$res=mysql_query($query) or die(mysql_error());
$row=mysql_fetch_assoc($res);
$nt=mysql_affected_rows();
if($nt!=0)
{
$textmessage="New Stock ".$row['Qty_New']." Resharp. Stock ".$row['Qty_Resharpened']. "NT in SF ". $row['Qty_New_SF'];	
}else{
	$textmessage="This Tool is Not in Stock";
}

print($textmessage);
?>