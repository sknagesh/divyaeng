<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$operationid=$_GET['operationid'];
$query="SELECT actl.Activity_Log_ID,Machine_Name,prod.Operation_ID,Remarks,Operator_Name,ope.Drawing_ID,
		DATE_FORMAT(Start_Date_Time,'%d/%m/%Y %h:%i %p') as sdt,Mfg_Batch_NO,
		DATE_FORMAT(End_Date_Time,'%d/%m/%Y %h:%i %p')as edt,Component_Name,Drawing_NO,
		 prod.Program_NO,prod.Batch_ID FROM Production AS prod
		 INNER JOIN ActivityLog as actl ON actl.Activity_log_ID=prod.Activity_Log_ID
		INNER JOIN Operation as ope ON ope.Operation_ID=prod.Operation_ID
		 INNER JOIN Component AS comp ON comp.Drawing_ID=ope.Drawing_ID
		 INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
		 INNER JOIN Operator as oper ON actl.Operator_ID=oper.Operator_ID
		 INNER JOIN Batch_NO as bn ON bn.Batch_ID=prod.Batch_ID 
		  WHERE prod.Operation_ID='$operationid' ORDER BY End_Date_Time DESC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
	$c="q";
print("<table cellspacing=\"1\">");
print("<tr class=\"t\"><th>Log ID</th><th>Machine</th><th>Drawing No and Name</th><th>Batch NO</th><th>Program NO</th><th>Operator</th><th>Start Date and Time</th><th>End Date and Time</th><th>Remarks</th></tr>");
while($row=mysql_fetch_assoc($res))
{

print("<tr class=\"$c\"><td>$row[Activity_Log_ID]</td><td>$row[Machine_Name]</td><td>$row[Drawing_NO]  $row[Component_Name]</td><td>$row[Mfg_Batch_NO]</td><td>$row[Program_NO]</td><td>$row[Operator_Name]</td><td>$row[sdt]</td><td>$row[edt]</td><td>$row[Remarks]</td></tr>");
	if($c=="q"){$c="s";}else{$c="q";}
}
print("</table>");
}
else {
	print("No Operations Added For this Drawing Yet");
}
?>