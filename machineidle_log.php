<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$activityid=$_POST['Activity_ID'];
$machineid=$_POST['Machine_ID'];
$sdatetime=$_POST['sdatedb'];
$edatetime=$_POST['edatedb'];
$operatorid=$_POST['Operator_ID'];
if(isSet($_POST['Idle_ID'])){$idleid=$_POST['Idle_ID'];}else{$idleid='';}
if(isSet($_POST['remark'])){$remark=$_POST['remark'];}else{$remark="";}


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

//print("<br>$query");

$res=mysql_query($query) or die(mysql_error());
$lastid=mysql_insert_id();

$ok=mysql_affected_rows();

if($ok!=0)
{

$q2="INSERT INTO NonProduction (Activity_Log_ID,Idle_ID) VALUES('$lastid','$idleid');";
$res=mysql_query($q2) or die(mysql_error());
	print('<p style="font-size:12px;color:green">Added one Row in to Idle Time Log with Batch ID $bno and Log ID is $lastid</p>');
}else{
	print('<p style="font-size:12px;color:red">Error adding into Production Log</p>');
}

?>