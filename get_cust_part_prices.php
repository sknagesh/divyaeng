<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$custid=$_GET['custid'];
$query="SELECT * FROM Component WHERE Customer_ID='$custid' ORDER BY Drawing_NO;";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$r=mysql_num_rows($resa);
if($r!=0)
{
$c="q";
print("<table cellspacing=\"1\" cellborder=\"1\">");
print("<tr  class=\"t\" ><th>Drawing ID</th><th>Drawing NO</th><th>Component Name</th><th>Price</th></tr>");
while ($row = mysql_fetch_assoc($resa))
{
	if($row['Part_Price']!=0)
	{
	print("<tr class=\"$c\"><td>$row[Drawing_ID]</td>
				<td>$row[Drawing_NO]</td>
				<td>$row[Component_Name]</td>
				<td>$row[Part_Price]</td>
				</tr>");
	if($c=="q"){$c="s";}else{$c="q";}
}

}
print("</table>");
}


?>