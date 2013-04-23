<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT * FROM Holder ORDER BY Holder_ID ASC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Holder ID</th><th>Tool Holder Description</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Holder_ID]</td><td>$row[Holder_Description]</td></tr>");
	
}
print("</table>");
}
else {
	print("No Brands Added");
}
?>