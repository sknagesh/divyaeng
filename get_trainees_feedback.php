<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$tid=$_GET['tid'];

$q="SELECT Trainee_ID FROM Trainee_Feedback WHERE Training_Plan_Id='$tid';";

$res=mysql_query($q) or die(mysql_error());
$r=mysql_affected_rows();

if($r!=0)
{
print("<select id=\"Trainee_ID\" name=\"Trainee_ID\" class=\"required\">");
print("<option value=\"\">Select Trainee Name</option>");
while($row=mysql_fetch_assoc($res))
{
$qn="SELECT Operator_Name FROM Operator WHERE Operator_ID='$row[Trainee_ID]';";
$resn=mysql_query($qn) or die(mysql_error());
$name=mysql_fetch_assoc($resn);

print("<option value=\"$row[Trainee_ID]\">$name[Operator_Name]</option>");
}
print("</select>");
}

?>