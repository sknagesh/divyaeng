<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$opid=$_GET['opid'];

$query="SELECT Gage_Desc FROM Operation_Gage as og
INNER JOIN Gage as g ON g.Gage_ID=og.Gage_ID WHERE og.Operation_ID=$opid;";

//print($query);


$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);

if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\" bgcolor=\"#7FFFD4\">");
print("<tr><th>Gage Description</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Gage_Desc]</td></tr>");
	
}
print("</table>");
}
else {
	print("No Gages are added for this Operation");
}
?>