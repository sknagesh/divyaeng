<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_GET);
$dpath="/logimages/";
if(isSet($_GET['mid'])){$mid=$_GET['mid'];}else{$mid="";}
if(isSet($_GET['sdate'])){$sdate=$_GET['sdate'];}else{$sdate="";}
if(isSet($_GET['edate'])){$edate=$_GET['edate'];}else{$edate="";}
if(isSet($_GET['aid'])){$aid=$_GET['aid'];}else{$aid="";}

if($mid!=''){$m=" AND actl.Machine_ID='$mid' ";}else{$m='';}

if($sdate!=''){$sd=" AND Start_Date_Time>='$sdate' ";}else{$sd='';}

if($edate!=''){$ed=" AND Start_Date_Time<='$edate' ";}else{$ed='';}

if($aid!=''){$a=" AND actl.Activity_ID='$aid' ";}else{$a='';}

$query="SELECT actl.Activity_Log_ID,actl.Activity_ID, Machine_Name,
		(SELECT GROUP_CONCAT('/logimages/',Image_Path) FROM ActivityLog_Image as ali 
		WHERE ali.Activity_Log_ID=actl.Activity_Log_ID) as ip,
		DATE_FORMAT(Start_Date_Time,'%d/%m/%Y %h:%i %p') as sdt,
		DATE_FORMAT(End_Date_Time,'%d/%m/%Y %h:%i %p')as edt,
		TIME_FORMAT(TIMEDIFF(End_Date_Time,Start_Date_Time),'%H:%i') as td,
		actl.Operator_ID,Remarks,Operator_Name,Activity_Name FROM ActivityLog as actl  
		INNER JOIN Activity as act ON act.Activity_ID=actl.Activity_ID 
		INNER JOIN Operator as ope ON ope.Operator_ID=actl.Operator_ID
		INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
		WHERE actl.Activity_ID NOT IN(10,15) $m $sd $ed $a  
		 ORDER BY End_Date_Time DESC;";
	
//print("$query<br>");
print("<br><h1>Activity Log DEW/MR/R/15</h1><br>");
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$noofrecords=mysql_affected_rows();
if($noofrecords!=0)
{
	$c="q";
print("<table cellspacing=\"1\" cellborder=\"1\" width=\"100%\">");
print("<tr class=\"t\" ><th>ID</th><th>M/C</th><th>Activity</th><th>Drawing No</th><th>Desc</th><th>Batch ID</th><th>Start Date Time</th><th>End Date Time</th>
		<th>Total Time</th><th>Qty</th><th>Prog. No.</th><th>Operator Name</th><th>Remarks</th></tr>");
while ($row = mysql_fetch_assoc($resa))
{
		
	if($row['Activity_ID']==1)
	{
		$sq="SELECT pro.Batch_ID,Drawing_NO,Mfg_Batch_NO,Component_Name, Operation_Desc,Quantity,pro.Program_NO FROM Production as pro 
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
		$qty=$rr['Quantity'];
		$pno=$rr['Program_NO'];
	}else
		if($row['Activity_ID']==2)
	{
		$sq="SELECT pro.Batch_ID,Drawing_NO,Mfg_Batch_NO,Component_Name, Operation_Desc,Quantity,pro.Program_NO FROM Production as pro 
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
		$qty=$rr['Quantity'];		 
		$pno=$rr['Program_NO'];
	}else
		if($row['Activity_ID']==3)
	{
		$sq="SELECT pro.Batch_ID,Drawing_NO,Mfg_Batch_NO,Component_Name, Operation_Desc,Quantity,pro.Program_NO FROM Production as pro 
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
		$qty=$rr['Quantity'];
		$pno=$rr['Program_NO'];
	}else
		if(($row['Activity_ID']==4)||($row['Activity_ID']==9))
	{
		$sq="SELECT np.Drawing_ID,Drawing_NO,Component_Name,Mfg_Batch_NO,Operation_Description,Quantity,np.Program_NO FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID];";
		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Description'];		
		$batchno=$rr['Mfg_Batch_NO'];
		$qty=$rr['Quantity'];
		$pno=$rr['Program_NO'];
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
		$sq="SELECT np.Drawing_ID,Drawing_NO,Component_Name,Mfg_Batch_NO,Operation_Description,Quantity,np.Program_NO FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID];";

		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Description'];		
		$batchno=$rr['Mfg_Batch_NO'];
		$qty=$rr['Quantity'];
		$pno=$rr['Program_NO'];
	}else
		if($row['Activity_ID']==12)
	{
		$sq="SELECT np.Drawing_ID,Drawing_NO,Component_Name,Mfg_Batch_NO,Operation_Description,Quantity,np.Program_NO FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID];";

		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['Drawing_NO'];
		$compname=$rr['Component_Name'];
		$operationdesc=$rr['Operation_Description'];		
		$batchno=$rr['Mfg_Batch_NO'];
		$qty=$rr['Quantity'];
		$pno=$rr['Program_NO'];
	}else
		{

			$operationdesc='';
			$dno='';
			$opename='';
			$compname='';
			$pno='';
			$qty='';

		}
		
		$id=$row['Activity_Log_ID'];
		$sdt=$row['sdt'];
		$edt=$row['edt'];
		$opename=$row['Operator_Name'];
		$activity=$row['Activity_Name'];
		$td=$row['td'];
		$remarks=$row['Remarks'];
		$machine=$row['Machine_Name'];
		
		if($row['ip']!='')
		{
			$images=explode(',', $row['ip']);
			
			$ip="<table style=\"width:80px\"><tr><td>$batchno<td></tr><tr><td>";
			$y=1;
			for($z=0;$z<count($images);$z++)
			{
				$ip.="<a class=\"pdf\" href=\"$images[$z]\" target=\"_NEW\" title=\"View Image in New Tab\">$y&nbsp;&nbsp&nbsp&nbsp</a>";
				$y++;
			}
			$ip.="</td></tr></table>";			
		}else{
			
			$ip=$batchno;
		}
		
print("<tr class=\"$c\"><td>$id</td><td>$machine</td><td>$activity</td><td style=\"width:130px\">$dno : $compname</td>
		<td style=\"width:150px\">$operationdesc</td><td style=\"width:80px\">$ip</td><td style=\"width:80px\">$sdt</td><td style=\"width:80px\">$edt</td>
		<td align=\"center\" style=\"width:50px\">$td</td>
		<td align=\"center\" style=\"width:40px\">$qty</td>
		<td style=\"width:55px\">$pno</td><td style=\"width:80px\">$opename</td>
		<td><span style=\"max-width:150px;\">$remarks</span></td></tr>");
if($c=="q"){$c="s";}else{$c="q";}
}
print("</table>");
}else{print("No Activity Detected For This Machine");}



?>