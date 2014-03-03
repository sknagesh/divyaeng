<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_GET);
$dpath="/logimages/";
if(isSet($_GET['mid'])){$mid=$_GET['mid'];}else{$mid="";}
if(isSet($_GET['aid'])){$aid=$_GET['aid'];}else{$aid="";}


if($mid!=''){$m=" AND actl.Machine_ID='$mid' ";}else{$m='';}

if($aid!=''){$a=" AND actl.Activity_ID='$aid' ";}else{$a='';}

//if($stext!=''){$st="AND (Remarks LIKE '%$stext%')";}else{$st='';}
$jsonArray = array();
$query="SELECT actl.Activity_Log_ID,actl.Activity_ID, Machine_Name,
		Start_Date_Time as sdt,End_Date_Time as edt,
		actl.Operator_ID,Remarks,Operator_Name,Activity_Name FROM ActivityLog as actl  
		INNER JOIN Activity as act ON act.Activity_ID=actl.Activity_ID 
		INNER JOIN Operator as ope ON ope.Operator_ID=actl.Operator_ID
		INNER JOIN Machine as mach ON mach.Machine_ID=actl.Machine_ID
		WHERE actl.Activity_ID NOT IN(10,15) $m $a  
		 ORDER BY End_Date_Time DESC $lim;";

//print("$query<br>");
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$noofrecords=mysql_affected_rows();
if($noofrecords!=0)
{

while ($row = mysql_fetch_assoc($resa))
{
		
	if($row['Activity_ID']==1)
	{


		$sq="SELECT Drawing_NO as dno,Operation_Desc as opd,Quantity FROM Production as pro 
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=pro.Batch_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID
		INNER JOIN Operation as ope ON ope.Operation_ID=pro.Operation_ID 
		 WHERE pro.Activity_Log_ID=$row[Activity_Log_ID];";
//		 print("$sq");
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['dno'];
		$operationdesc=$rr['opd'];
		$qty=$rr['Quantity'];
		$color="Green";
	}else
		if($row['Activity_ID']==2)
	{
		
		$sq="SELECT Drawing_NO as dno,Operation_Desc as opd,Quantity FROM Production as pro 
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=pro.Batch_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID
		INNER JOIN Operation as ope ON ope.Operation_ID=pro.Operation_ID 
		 WHERE pro.Activity_Log_ID=$row[Activity_Log_ID];";
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['dno'];
		$operationdesc=$rr['opd'];
		$qty=$rr['Quantity'];
		$color="Light Green";

	}else
		if($row['Activity_ID']==3)
	{

		$sq="SELECT Drawing_NO as dno,Operation_Desc as opd,Quantity FROM Production as pro 
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=pro.Batch_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID
		INNER JOIN Operation as ope ON ope.Operation_ID=pro.Operation_ID 
		 WHERE pro.Activity_Log_ID=$row[Activity_Log_ID];";
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['dno'];
		$operationdesc=$rr['opd'];
		$qty=$rr['Quantity'];
		$color="Yellow";

	}else
		if(($row['Activity_ID']==4)||($row['Activity_ID']==9))
	{


		$sq="SELECT np.Drawing_ID,Drawing_NO as dno,Operation_Description as opd,Quantity FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID] ;";
		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['dno'];
		$operationdesc=$rr['opd'];		
		$qty=$rr['Quantity'];
		$color="BDB76B";
		}else
		if($row['Activity_ID']==5)
	{
		$sq="SELECT Maintenance_Description,maint.Maintenance_Type_ID FROM Maintenance as maint
		INNER JOIN Maintenance_Type as mt ON mt.Maintenance_Type_ID=maint.Maintenance_Type_ID WHERE Activity_Log_ID=$row[Activity_Log_ID];";
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$operationdesc=$rr['Maintenance_Description'];
		$dno='';
		$qty='';
		$color="Blue";
	}else
			if($row['Activity_ID']==8)
	{
		$sq="SELECT *,Idle_Reason FROM NonProduction as np
		INNER JOIN Idle_Reason as ir ON ir.Idle_ID=np.Idle_ID WHERE Activity_Log_ID=$row[Activity_Log_ID];";
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		if($rr['Idle_ID']!='')
		{
		$operationdesc=$rr['Idle_Reason'];
		}else{
			$operationdesc='';
		}
		$dno='';
		$qty='';
		$color="Orange";
	}else

		if($row['Activity_ID']==11)
	{


		$sq="SELECT np.Drawing_ID,Drawing_NO as dno,Operation_Description as opd,Quantity FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID];";

		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['dno'];
		$operationdesc=$rr['opd'];		
		$qty=$rr['Quantity'];
		$color="Magenta";
	}else
		if($row['Activity_ID']==12)
	{


		$sq="SELECT np.Drawing_ID,Drawing_NO as dno,Operation_Description as opd,Quantity FROM NonProduction as np
		INNER JOIN Component as comp ON comp.Drawing_ID=np.Drawing_ID 
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=np.Batch_ID 
		WHERE Activity_Log_ID=$row[Activity_Log_ID];";

		
		$res = mysql_query($sq, $cxn) or die(mysql_error($cxn));
		$rr=mysql_fetch_assoc($res);
		$dno=$rr['dno'];
		$operationdesc=$rr['opd'];		
		$qty=$rr['Quantity'];
	}else
		{

			$operationdesc='';
			$dno='';
			$qty='';
			$color="White";


		}
		
		$id=$row['Activity_Log_ID'];
		$sdt=$row['sdt'];
		$edt=$row['edt'];
		$activity=$row['Activity_Name'];
		$machine=$row['Machine_Name'];
		$remarks=$row['Remarks'];
		$aname=$row['Activity_Name'];


 $buildjson = array('title' => "$row[Machine_Name] - $aname - $dno - $operationdesc  Remarks: $remarks",'id' => "$id", 'start' => "$sdt",'end' => "$edt", 'allDay' => false,'backgroundColor' => "$color");

 // Adds each array into the container array
 array_push($jsonArray, $buildjson);

}

echo json_encode($jsonArray);

}else{print("No Activity Detected For This Machine");}


?>