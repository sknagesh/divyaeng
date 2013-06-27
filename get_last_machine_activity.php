<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$mid=$_GET['mid'];
//print($mid);


$query="SELECT Activity_Log_ID,actl.Activity_ID, Machine_ID,Remarks,
		DATE_FORMAT(Start_Date_Time,'%d/%m/%y %h:%i %p') as sdt,DATE_FORMAT(End_Date_Time,'%d/%m/%y %h:%i %p')as edt,actl.Operator_ID,Remarks,
		Operator_Name,Activity_Name FROM ActivityLog as actl  
		INNER JOIN Activity as act ON act.Activity_ID=actl.Activity_ID 
		INNER JOIN Operator as ope ON ope.Operator_ID=actl.Operator_ID
		WHERE Machine_ID='$mid' AND actl.Activity_ID NOT IN(10,15) ORDER BY End_Date_Time DESC LIMIT 3;";
//print("$query<br>");
print("<br><h1>Last Three Log Entries for this Machine</h1><br>");
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$noofrecords=mysql_affected_rows();
if($noofrecords!=0)
{
print("<table cellspacing=\"5\" cellborder=\"2\" border=\"1\">");
print("<tr><th>Activity</th><th>Drawing No</th><th>Desc</th><th>Batch NO</th><th>Start Date Time</th><th>End Date Time</th><th>Operator Name</th><th>Remarks</th></tr>");
while ($row = mysql_fetch_assoc($resa))
{
		
	if($row['Activity_ID']==1)
	{
		$sq="SELECT pro.Batch_ID,Drawing_NO,Mfg_Batch_NO,Component_Name, Operation_Desc FROM Production as pro 
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=pro.Batch_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID
		INNER JOIN Operation as ope ON ope.Operation_ID=pro.Operation_ID 
		 WHERE pro.Activity_Log_ID=$row[Activity_Log_ID];";
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Desc'];
		$batchno=$rr['Mfg_Batch_NO'];
	}else
		if($row['Activity_ID']==2)
	{
		$sq="SELECT pro.Batch_ID,Drawing_NO,Mfg_Batch_NO,Component_Name, Operation_Desc FROM Production as pro 
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=pro.Batch_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID
		INNER JOIN Operation as ope ON ope.Operation_ID=pro.Operation_ID 
		 WHERE pro.Activity_Log_ID=$row[Activity_Log_ID];";
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Desc'];
		$batchno=$rr['Mfg_Batch_NO'];
		 
	}else
		if($row['Activity_ID']==3)
	{
		$sq="SELECT pro.Batch_ID,Drawing_NO,Mfg_Batch_NO,Component_Name, Operation_Desc FROM Production as pro 
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=pro.Batch_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID
		INNER JOIN Operation as ope ON ope.Operation_ID=pro.Operation_ID 
		 WHERE pro.Activity_Log_ID=$row[Activity_Log_ID];";
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Desc'];
		$batchno=$rr['Mfg_Batch_NO'];

		 
	}else
		if($row['Activity_ID']==4)
	{
		$sq="SELECT np.Drawing_ID,Drawing_NO,Component_Name,Mfg_Batch_NO,Operation_Description FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID];";
		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Description'];		
		$batchno=$rr['Mfg_Batch_NO'];
		}else
		if($row['Activity_ID']==5)
	{
		$sq="SELECT Problem_Desc FROM Maintenance WHERE Activity_Log_ID=$row[Activity_Log_ID];";
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$operationdesc=$rr['Problem_Desc'];
		$compname="";
		$dno='';
	}else
		if($row['Activity_ID']==11)
	{
		$sq="SELECT np.Drawing_ID,Drawing_NO,Component_Name,Mfg_Batch_NO,Operation_Description FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID];";

		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Description'];		
		$batchno=$rr['Mfg_Batch_NO'];
	}else
		if($row['Activity_ID']==12)
	{
		$sq="SELECT np.Drawing_ID,Drawing_NO,Component_Name,Mfg_Batch_NO,Operation_Description FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID];";

		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Description'];		
		$batchno=$rr['Mfg_Batch_NO'];
	}else
		{

			$operationdesc='';
			$dno='';
			$opename='';
			$compname='';

		}
		
		
		$sdt=$row['sdt'];
		$edt=$row['edt'];
		$opename=$row['Operator_Name'];
		$activity=$row['Activity_Name'];

print("<tr><td>$activity</td><td>$dno - $compname</td><td>$operationdesc</td><td>$batchno</td><td>$sdt</td><td>$edt</td><td>$opename</td><td>$row[Remarks]</td></tr>");

}
print("</table>");
}else{print("No Activity Detected For This Machine");}



?>
