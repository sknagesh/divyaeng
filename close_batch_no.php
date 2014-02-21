<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$batchid=$_POST['Batch_ID'];
$ddatedb=$_POST['ddatedb'];
if(isSet($_POST['dcomm'])){$dcomm=$_POST['dcomm'];}else{$dcomm='';}

$query="UPDATE Batch_NO SET Batch_Under_Progress='0',Deposit_Date='$ddatedb',Delivery_Comments='$dcomm' WHERE Batch_ID='$batchid';";
//print("<br>Query is '$query'");
$result = mysql_query($query, $cxn) or die(mysql_error($cxn));
printf("No of Records Updated is: %d\n", mysql_affected_rows());


?>