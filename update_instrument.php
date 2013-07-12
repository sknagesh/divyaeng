<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$iid=$_POST['Instrument_ID'];
$instno=$_POST['instno'];
$instdesc=$_POST['instdesc'];
if(isSet($_POST['caldatedb'])){$caldate=$_POST['caldatedb'];}else{$caldatedb='';}
if(isSet($_POST['make'])){$make=$_POST['make'];}else{$make='';}
if(isSet($_POST['range'])){$range=$_POST['range'];}else{$range='';}
if(isSet($_POST['aerror'])){$aerror=$_POST['aerror'];}else{$aerror='';}
if(isSet($_POST['lc'])){$lc=$_POST['lc'];}else{$lc='';}
if(isSet($_POST['calfreq'])){$calfreq=$_POST['calfreq'];}else{$calfreq='';}
if(isSet($_POST['remarks'])){$remarks=$_POST['remarks'];}else{$remarks='';}


$query="UPDATE Instrument 
	SET Instrument_SLNO='$instno',
 		Instrument_Description='$instdesc',
 		Calibration_Date='$caldate',
 		Calibration_Frequency='$calfreq',
 		Make='$make',
 		Instrument_Range='$range',
 		Acceptable_Error='$aerror',
 		Least_Count='$lc',
 		Remarks='$remarks' WHERE Instrument_ID=$iid;";

 		

//print("<br>Query is '$query'");

$result = mysql_query($query, $cxn) or die(mysql_error($cxn));
printf("No of Records Added is: %d\n", mysql_affected_rows());


?>