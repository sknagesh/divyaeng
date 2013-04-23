<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT * FROM Instrument ORDER BY Instrument_ID ASC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Instrument ID</th><th>Serial No</th><th>Description</th><th>Calibration Date</th><th>Next Calibration Date</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Instrument_ID]</td><td>$row[Instrument_SLNO]</td><td>$row[Instrument_Description]</td><td>$row[Calibration_Date]</td><td>$row[Next_Calibration_Date]</td></tr>");
	
}
print("</table>");
}
else {
	print("No Instruments Added");
}
?>