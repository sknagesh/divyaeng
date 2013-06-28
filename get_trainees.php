<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$q="SELECT * FROM Operator;";

$res=mysql_query($q) or die(mysql_error());
$r=mysql_affected_rows();

if($r!=0)
{
print("<table cellspacing=\"1\"><tr>");
$i=0;
$j=1;
while($row=mysql_fetch_assoc($res))
{
print("<td><input type=\"checkbox\" id=\"trainee\" name=\"trainee[$i]\" value=\"$row[Operator_ID]\">$row[Operator_Name]</td>");
$i++;$j++;

if($j>4)
	{print("</tr><tr>");$j=1;}
}
print("</table>");
}

?>