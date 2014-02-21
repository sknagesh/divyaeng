<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$opid = $_GET['opid'];
$bid=$_GET['bid'];
$jobno=$_GET['jobno'];

	$qoid="SELECT Dimn_Observation_ID,DATE_FORMAT(Insp_Date,'%d-%m-%Y') as inspdate,Insp_Date FROM Dimn_Observation WHERE Batch_ID='$bid' AND Job_NO='$jobno' AND Operation_ID='$opid';";
//	print($qoid);
	$reso = mysql_query($qoid, $cxn) or die(mysql_error($cxn));
	$rowoid = mysql_fetch_assoc($reso);
	
	$data=$rowoid['inspdate'].'<|>'.$rowoid['Insp_Date'].'<|>'.$rowoid['Dimn_Observation_ID'];
	echo $data;
?>