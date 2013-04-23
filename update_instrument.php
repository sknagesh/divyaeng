<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$iid=$_POST['Instrument_ID'];
$instno=$_POST['instno'];
$instdesc=$_POST['instdesc'];
$caldate=$_POST['caldatedb'];
$nextcaldate=$_POST['nextcaldatedb'];


$query="UPDATE Instrument 
	SET Instrument_SLNO='$instno',
 		Instrument_Description='$instdesc',
 		Calibration_Date='$caldate',
 		Next_Calibration_Date='$nextcaldate' WHERE Instrument_ID=$iid;";

 		

//print("<br>Query is '$query'");

$result = mysql_query($query, $cxn) or die(mysql_error($cxn));
printf("No of Records Added is: %d\n", mysql_affected_rows());


?>