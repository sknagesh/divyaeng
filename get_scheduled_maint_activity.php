<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());


$spmid=$_GET['spmid'];

$qs="SELECT * FROM SPM_Desc WHERE SPM_ID='$spmid';";
$res=mysql_query($qs) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{

	$i=0;

	print("<label>Select All Activites Completed</label>");
	print("<table>");
	while($row=mysql_fetch_assoc($res))
	{

		print("<tr><td><input type=\"checkbox\" name=\"pm[$i]\" id=\"pm[$i]\" value=\"$row[SPM_Desc_ID]\">$row[SPM_Desc]</td></tr>");
	$i++;
	}
	print("</table>");
}else{

print("No Maintenance Scheduled for this machine");
}
?>