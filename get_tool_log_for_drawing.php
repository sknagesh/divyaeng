<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['drawingid'])){$drawingid=$_GET['drawingid'];}else{$drawingid="";}
if(isSet($_GET['bid'])){$bid=$_GET['bid'];}else{$bid="";}

if($drawingid!='')
{
$didd="WHERE mdq.Drawing_ID='$drawingid'";
}else{
$didd="";
}


if($bid!="")
{
$batch="AND tc.Batch_ID='$bid'";
}else{
$batch="";
}

$query="SELECT Machine_Name,tb.Brand_Description as obd,cb.Brand_Description as cbd,
				Mfg_Batch_NO,Operation_Desc,Reason,Operator_Name,Remarks,New_Tool_condition,
				Original_Tool_Condition,Start_Date_Time,Component_Name,Drawing_NO,ot.Tool_Desc as tod,
				ct.Tool_Desc as ctd FROM ToolChange AS tc
		 		INNER JOIN ActivityLog as actl ON actl.Activity_log_ID=tc.Activity_Log_ID
		 		INNER JOIN Operation AS ope ON ope.Operation_ID=tc.Operation_ID 
		 		INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
		 		INNER JOIN Operator as oper ON oper.Operator_ID=actl.Operator_ID
		 		INNER JOIN Tool_Change_Reasons as tcr ON tcr.Reason_ID=tc.Reason_ID
		 		INNER JOIN Batch_NO as bi ON bi.Batch_ID=tc.Batch_ID
		 		INNER JOIN Tool as ot ON ot.Tool_ID=tc.Original_Tool_ID
		 		INNER JOIN Tool as ct ON ct.Tool_ID=tc.Changed_Tool_ID
		 		INNER JOIN Tool_Brand as tb ON tb.Brand_ID=ot.Brand_ID
		 		INNER JOIN Tool_Brand as cb ON cb.Brand_ID=ct.Brand_ID
		 		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=tc.Batch_ID
				INNER JOIN MI_Drg_Qty AS mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID 
				INNER JOIN Component AS comp ON comp.Drawing_ID=mdq.Drawing_ID
		  		$didd $batch ORDER BY Start_Date_Time DESC;";

//print("$query<br>");

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
	$c="q";
print("<table cellspacing=\"1\">");
print("<tr class=\"t\"><th>Machine</th><th>Drawing No and Name</th><th>Operation</th><th>Batch NO</th><th>Changed By</th><th>Change Date and Time</th>
			<th>Original Tool</th><th>Changed Tool</th><th>Reason For Change</th><th>Remarks</th></tr>");
while($row=mysql_fetch_assoc($res))
{

print("<tr class=\"$c\">
			<td>$row[Machine_Name]</td><td>$row[Drawing_NO] $row[Component_Name]</td>
			<td>$row[Operation_Desc]</td><td>$row[Mfg_Batch_NO]</td><td>$row[Operator_Name]</td><td>$row[Start_Date_Time]</td>
			<td>$row[obd] Make $row[tod]</td><td>$row[cbd] Make $row[ctd]</td><td>$row[Reason]</td><td>$row[Remarks]</td></tr>");
	if($c=="q"){$c="s";}else{$c="q";}
}
print("</table>");
}
else {
	print("No tools Changed For This Component/Batch");
}
?>
