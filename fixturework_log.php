<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$activityid=$_POST['Activity_ID'];
$machineid=$_POST['Machine_ID'];
$sdatetime=$_POST['sdatedb'];
$edatetime=$_POST['edatedb'];
$drgid=$_POST['Drawing_ID'];
$batchid=$_POST['Batch_ID'];
$operatorid=$_POST['Operator_ID'];
if(isSet($_POST['remark'])){$remark=$_POST['remark'];}else{$remarks="";}
if(isSet($_POST['op'])){$op=$_POST['op'];}else{$op="";}
if(isSet($_POST['qty'])){$qty=$_POST['qty'];}else{$qty="";}
if(isSet($_POST['pgno'])){$pgno=$_POST['pgno'];}else{$pgno="";}


$query="INSERT INTO ActivityLog (Activity_ID,
								Machine_ID,
								Start_Date_Time,
								End_Date_Time,
								Operator_ID,
								Remarks) ";
$query.="VALUES('$activityid',
				'$machineid',
				'$sdatetime',
				'$edatetime',
				'$operatorid',
				'$remark');";

print("<br>$query");

$res=mysql_query($query) or die(mysql_error());
$lastid=mysql_insert_id();

$pquery="INSERT INTO NonProduction (Activity_Log_ID,
								Drawing_ID,
								Operation_Description,
								Program_NO,
								Quantity,
								Batch_ID) ";
$pquery.="VALUES('$lastid',
				'$drgid',
				'$op',
				'$pgno',
				'$qty',
				'$batchid');";

print("<br>$pquery");
$result=mysql_query($pquery) or die(mysql_error());
$ok=mysql_affected_rows();
if($ok!=0)
{
	print("Added one Row in to NonProduction Log and Log ID is $lastid");
}else{
	print("Error adding into NonProduction Log");
}


?>