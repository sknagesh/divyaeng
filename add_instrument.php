<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$instno=$_POST['instno'];
$instdesc=$_POST['instdesc'];
$caldate=$_POST['caldatedb'];
$nextcaldate=$_POST['nextcaldatedb'];


$query="INSERT INTO Instrument ";
$query.="(Instrument_SLNO, Instrument_Description, Calibration_Date, Next_Calibration_Date) ";
$query.=" VALUES('$instno','$instdesc', '$caldate', '$nextcaldate');";
print("<br>Query is '$query'");

$result = mysql_query($query, $cxn) or die(mysql_error($cxn));
printf("No of Records Added is: %d\n", mysql_affected_rows());


?>