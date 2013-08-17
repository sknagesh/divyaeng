<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
	
$custid=$_POST['Customer_ID'];
$drawingid=$_POST['Drawing_ID'];
$operationid=$_POST['Operation_ID'];
$inspectorid=$_POST['Operator_ID'];
$batchid=$_POST['Batch_ID'];
$jobno=$_POST['jobno'];
$dimid=$_POST['dimid'];
$date=($_POST['dbdate']);
$observation=$_POST['observation'];
$remark=$_POST['remarks'];
if(isSet($_POST['comment'])){$comment=$_POST['comment'];}else{$comment='';};

$query="INSERT INTO Dimn_Observation (Batch_ID, Operation_ID, Job_NO,Insp_Date,Operator_ID) 
		 VALUES('$batchid','$operationid','$jobno','$date','$inspectorid');";
//print("<br>$query");
$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
$result=mysql_affected_rows($cxn);

$observationid=mysql_insert_id();
$i=0;
$j=0;
while($i<count($dimid))
{

if(($observation[$i]!='')||($comment[$i]!=''))
{
	if($observation[$i]!=''){$oid=$observation[$i];}else{$oid='';}
	if($comment[$i]!=''){$cid=$comment[$i];}else{$cid='';}
$queryo="INSERT INTO Observations (Dimn_Observation_ID, Dimension_ID, Observed_Dimn,Comment_ID,Remarks) 
		 VALUES('$observationid','$dimid[$i]','$oid','$cid','$remark[$i]');";
//print("<br>$queryo");
$reso = mysql_query($queryo, $cxn) or die(mysql_error($cxn));
$j++;
}
$i++;

}
	
$result=mysql_affected_rows($cxn);
print("<p>$j Observations recorded for Job No $jobno<p>");

?>