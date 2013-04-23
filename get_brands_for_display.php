<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT * FROM Tool_Brand;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Brand ID</th><th>Brand Name</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Brand_ID]</td><td>$row[Brand_Description]</td></tr>");
	
}
print("</table>");
}
else {
	print("No Brands Added");
}
?>