<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());


$mid=$_GET['mid'];

$q="SELECT * FROM Scheduled_PM WHERE Machine_ID='$mid';";

$resm=mysql_query($q) or die(mysql_error());
print("<table cellspacing=\"1\" border=\"1\"><tr><th>Maintenance Title</th><th>Maintenance Activites</th>");
while($rowm=mysql_fetch_assoc($resm))
{
print("<tr><td>$rowm[SPM_Title]</td><td>");

$qs="SELECT * FROM SPM_Desc WHERE SPM_ID='$rowm[SPM_ID]';";
$res=mysql_query($qs) or die(mysql_error());
$r=mysql_num_rows($res);
	while($row=mysql_fetch_assoc($res))
	{

		print("$row[SPM_Desc],");
	}
	print("</td></tr>");

}
print("</table>");
?>