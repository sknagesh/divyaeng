<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$custid=$_GET['cid'];
$query="SELECT * FROM Component WHERE Customer_ID='$custid';";

print("<table><tr><th>Drwing ID</th><th>Drwing NO</th><th>Component Name</th><th>Drwing Rev</th></tr>");
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
	print("<tr><td>$row[Drawing_ID]</td><td>$row[Drawing_NO]</td><td>$row[Component_Name]</td><td>$row[Drawing_Rev]</td></tr>");
	
}
print("</table>");



?>