<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
	
$operationid=$_POST['Operation_ID'];
$batchid=$_POST['Batch_ID'];
$jobno=$_POST['Job_NO'];
$dimid=$_POST['dimid'];
$observation=$_POST['observation'];
$remark=$_POST['remarks'];
$comment=$_POST['comment'];

$obserid=$_POST['obserid']; //id for old measured dimensions, we need to update these dimensions

$observationid=$_POST['observationid'];  //this id contains job_no, date measured, batch id, opid etc

$i=0;
$j=0;
$k=0;
while($i<count($dimid))
{
	if($observation[$i]!=''){$oid=$observation[$i];}else{$oid='';}
	if($comment[$i]!=''){$cid=$comment[$i];}else{$cid='';}

	if($obserid[$i]=='')
	{
	if($oid!=''||$cid!='')
		{
		$queryo="INSERT INTO Observations (Dimn_Observation_ID, Dimension_ID, Observed_Dimn,Comment_ID,Remarks) 
		 VALUES('$observationid','$dimid[$i]','$oid','$cid','$remark[$i]');";
//print("<br>$queryo");
		$reso = mysql_query($queryo, $cxn) or die(mysql_error($cxn));
		$j++;
		}
	}else{
	$queryo="UPDATE Observations SET 
			Observed_Dimn='$oid',
			Comment_ID='$cid',
			Remarks='$remark[$i]' 
		 	WHERE Observation_ID='$obserid[$i]';";
//print("<br>$queryo");
$reso = mysql_query($queryo, $cxn) or die(mysql_error($cxn));
$k++;
	}
$i++;

}
	
$result=mysql_affected_rows($cxn);
print("<p>$j Observations added and $k dimensions updated for Job No $jobno<p>");

?>