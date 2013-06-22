<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);

$drawingid=$_POST['Drawing_ID'];
$activityid=$_POST['Activity_ID'];
$machineid=$_POST['Machine_ID'];
$operationid=$_POST['Operation_ID'];
$sdatetime=$_POST['edatedb'];
$operatorid=$_POST['Operator_ID'];
$reason=$_POST['Reason_ID'];
$bno=$_POST['Batch_ID'];

if(isSet($_POST['jobno'])){$jobno=$_POST['jobno'];}else{$jobno="";}

$oldtid=$_POST['Tool_ID1'];
$newtid=$_POST['Tool_ID2'];

if(isSet($_POST['Insert_ID1'])){$oldinsid=$_POST['Insert_ID1'];}else{$oldinsid="";}
if(isSet($_POST['Insert_ID2'])){$newinsid=$_POST['Insert_ID2'];}else{$newinsid="";}

if(isSet($_POST['iqty1'])){$iqty1=$_POST['iqty1'];}else{$iqty1="";}
if(isSet($_POST['iqty2'])){$iqty2=$_POST['iqty2'];}else{$iqty2="";}

$tconditionold=$_POST['newtool1'];
$tconditionnew=$_POST['newtool2'];

if(isSet($_POST['remark'])){$remarks=$_POST['remark'];}else{$remarks="";}


$query="INSERT INTO ActivityLog (Activity_ID,
								Machine_ID,
								Start_Date_Time,
								End_Date_Time,
								Operator_ID,
								Remarks) ";
$query.="VALUES('$activityid',
				'$machineid',
				'$sdatetime',
				'$sdatetime',
				'$operatorid',
				'$remarks');";

//print("<br>$query");

$res=mysql_query($query) or die(mysql_error());
$lastid=mysql_insert_id();

$pquery="INSERT INTO ToolChange (Activity_Log_ID,
								Batch_ID,
								Operation_ID,
								Original_Tool_ID,
								Original_Insert_ID,
								Original_Ins_Qty,
								Changed_Tool_ID,
								Changed_Insert_ID,
								Changed_Ins_Qty,
								Original_Tool_Condition,
								New_Tool_Condition,
								Reason_ID,
								Job_NO) ";
$pquery.="VALUES('$lastid',
				'$bno',
				'$operationid',
				'$oldtid',
				'$oldinsid',
				'$iqty1',
				'$newtid',
				'$newinsid',
				'$iqty2',
				'$tconditionold',
				'$tconditionnew',
				'$reason',
				'$jobno');";

print("<br>$pquery");
$result=mysql_query($pquery) or die(mysql_error());
$ok=mysql_affected_rows();
if($ok!=0)
{
	print("Added one Row in to Tool Change Log and Log ID is $lastid");
}else{
	print("Error adding into Tool Change Log");
}


?>