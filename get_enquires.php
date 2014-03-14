<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$q="SELECT * FROM Enquiry_Record;";

$res=mysql_query($q) or die(mysql_error());
$r=mysql_affected_rows();

if($r!=0)
{
print("<p><label>Select Enquiry</label>");
print("<select id=\"Enquiry_ID\" name=\"Enquiry_ID\" class=\"required\">");
print("<option value=\"\">Select Enquiry</option>");
while($row=mysql_fetch_assoc($res))
{
print("<option value=\"$row[Enquiry_ID]\">$row[Customer_Name] : $row[Drawing_NO]</option>");
}
print("</select></p>");
}

?>