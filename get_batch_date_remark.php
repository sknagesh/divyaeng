<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$bid=$_GET['bid'];

$query2="SELECT Deposit_Date,DATE_FORMAT(Deposit_Date,'%d-%m-%Y') as dd,Delivery_Comments FROM Batch_NO WHERE Batch_ID='$bid';";

//print($query2);

$resa2 = mysql_query($query2, $cxn) or die(mysql_error($cxn));
$row=mysql_fetch_assoc($resa2);
$data=$row['dd'].'<|>'.$row['Deposit_Date'].'<|>'.$row['Delivery_Comments'];
echo $data;



?>
