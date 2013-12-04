<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$tid=$_GET['tid'];

$query="SELECT Tool_ID_1,Tool_Desc, Tool_Dia, Tool_Type_ID,Brand_Description,count(Operation_ID) as NOP FROM Ope_Tool as ot
		INNER JOIN Tool AS t ON t.Tool_Id=ot.Tool_Id_1 
		INNER JOIN Tool_Brand AS tb ON tb.Brand_ID=t.Brand_ID 
		GROUP BY Tool_ID_1 ORDER BY Tool_Dia, NOP, Tool_Type_ID Desc;";

//print($query);




$res=mysql_query($query) or die(mysql_error());

print("<table border=\"1\" cellspacing=\"1\" id=\"ttble\">");
print("<tr><th>Tool ID</th><th>Tool Description</th><th>Make</th><th>No of Operations</th></tr>");
$i=0;
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td><input type=\"radio\" name=\"tinfo\" class=\"tinfo\" id=\"tinfo[$i]\" value=\"$row[Tool_ID_1]\"></input></td>
		<td>$row[Tool_Desc]</td><td>$row[Brand_Description]</td><td>$row[NOP]</td></tr>
		<tr><td colspan=\"12\"><div id=\"$i\"></div></td></tr>");
	$i++;

}
print("</table>");
?>