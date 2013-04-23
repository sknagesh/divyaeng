<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_GET['drawingid'];

$query="SELECT Program_NO,Operation_Desc,Clamping_Time,Machining_Time,GROUP_CONCAT(Fixture_NO) as fno FROM Operation as op 
		LEFT OUTER JOIN Ope_Fixt_Map as ofm ON ofm.Operation_ID=op.Operation_ID WHERE Drawing_ID='$drawingid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Operation Description</th><th>Program NO</th><th>Clamping Time</th><th>Machining Time</th><th>Fixture Number</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Operation_Desc]</td><td>$row[Program_NO]</td><td>$row[Clamping_Time]</td><td>$row[Machining_Time]</td><td>$row[fno]</td></tr>");
	
}
print("</table>");
}
else {
	print("No Operations Added For this Drawing Yet");
}
?>