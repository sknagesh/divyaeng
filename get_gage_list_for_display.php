<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT * FROM Gage ORDER BY Gage_Desc ASC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Gage ID</th><th>Gage Description</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Gage_ID]</td><td>$row[Gage_Desc]</td></tr>");
	
}
print("</table>");
}
else {
	print("No Gages Added Yet");
}
?>