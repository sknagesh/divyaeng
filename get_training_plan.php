<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$q="SELECT SUBSTRING(Training_Desc,1,15) as td,Training_Start_Date,Training_Plan_ID FROM Training_Plan;";

$res=mysql_query($q) or die(mysql_error());
$r=mysql_affected_rows();

if($r!=0)
{
print("<select id=\"Training_Plan_ID\" name=\"Training_Plan_ID\" class=\"required\">");
print("<option value=\"\">Select Training Plan</option>");
while($row=mysql_fetch_assoc($res))
{
print("<option value=\"$row[Training_Plan_ID]\">$row[td] ... $row[Training_Start_Date]</option>");
}
print("</select>");
}

?>