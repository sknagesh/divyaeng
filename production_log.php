<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);

$drawingid=$_POST['Drawing_ID'];
$activityid=$_POST['Activity_ID'];
$machineid=$_POST['Machine_ID'];
$operationid=$_POST['Operation_ID'];
$sdatetime=$_POST['sdatedb'];
$edatetime=$_POST['edatedb'];
$progno=$_POST['pno'];
$operatorid=$_POST['Operator_ID'];
$qty=$_POST['qty'];
$bno=$_POST['Batch_ID'];
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

$pquery="INSERT INTO Production (Activity_Log_ID,
								Operation_ID,
								Program_NO,
								Quantity,
								Batch_ID) ";
$pquery.="VALUES('$lastid',
				'$operationid',
				'$progno',
				'$qty',
				'$bno');";

//print("<br>$pquery");
$result=mysql_query($pquery);
if(!$result)
{
$q="DELETE FROM ActivityLog WHERE Activity_Log_ID='$lastid';";
$rd=mysql_query($q) or die(mysql_error());
	
}else{
$ok=mysql_affected_rows();
}
if($ok!=0)
{
	print("Added one Row in to Production Log with Batch ID $bno and Log ID is $lastid");
}else{
	print("Error adding into Production Log");
}


?>