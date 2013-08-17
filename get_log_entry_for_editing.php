<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_GET);
$lid=$_GET['lid'];

$qact="SELECT Activity_ID FROM ActivityLog WHERE Activity_Log_ID='$lid';";

$resa = mysql_query($qact, $cxn) or die(mysql_error($cxn));
$aid=mysql_fetch_assoc($resa);
$activityid=$aid['Activity_ID'];

if(($activityid==1)||($activityid==2)||($activityid==3)||($activityid==14))
{

	$q="SELECT prod.Activity_Log_ID, mdq.Drawing_ID,prod.Batch_ID,Activity_ID,Machine_ID, comp.Customer_ID,Operation_ID,Start_Date_Time,End_Date_Time,
	Operator_ID, DATE_FORMAT(Start_Date_Time,'%d-%m-%Y %H:%i:%s %p') as sdt, DATE_FORMAT(End_Date_time,'%d-%m-%Y %H:%i:%s %p') as edt,
	Quantity,Remarks,Program_NO,
	(Select GROUP_CONCAT(CONCAT(AL_Image_ID, ',' ,Image_Path)) FROM ActivityLog_Image WHERE Activity_LOG_ID='$lid')as ali
	 FROM Production as prod INNER JOIN ActivityLog as actl ON actl.Activity_Log_ID=prod.Activity_Log_ID 
	INNER JOIN BNo_MI_Challans as bmc ON bmc.Batch_ID=prod.Batch_ID
	INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
	INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID 
	INNER JOIN Customer as cust on cust.Customer_ID=comp.Customer_ID 
	WHERE prod.Activity_Log_ID='$lid';";


	$resa = mysql_query($q, $cxn) or die(mysql_error($cxn));

	$noofrecords=mysql_affected_rows();
	if($noofrecords!=0)
		{
		$row=mysql_fetch_assoc($resa);
		$mid=$row['Machine_ID'];
		$cid=$row['Customer_ID'];
		$did=$row['Drawing_ID'];
		$oid=$row['Operation_ID'];
		$opeid=$row['Operator_ID'];
		$sdt=$row['sdt'];
		$edt=$row['edt'];
		$qty=$row['Quantity'];
		$remark=$row['Remarks'];
		$pno=$row['Program_NO'];
		$sdtdb=$row['Start_Date_Time'];
		$edtdb=$row['End_Date_Time'];
		$actid=$row['Activity_ID'];
		$bid=$row['Batch_ID'];
		$ali=$row['ali'];
		$data=$activityid."<|>".$mid."<|>".$cid."<|>".$did."<|>".$oid."<|>".$opeid."<|>".$sdt."<|>".$edt."<|>".$qty."<|>".$remark."<|>".$pno."<|>".$sdtdb."<|>".$edtdb."<|>".$actid."<|>".$bid."<|>".$ali;
	
		}else	
		{
			$data="";
		}


} else if($activityid==5)
	{

	$q="SELECT maint.Activity_Log_ID,Activity_ID,Machine_ID, SPM_ID,Start_Date_Time,End_Date_Time,Operator_ID,Sch_Prev_Maint_IDs,
	DATE_FORMAT(Start_Date_Time,'%d-%m-%Y %h:%i:%s %p') as sdt, DATE_FORMAT(End_Date_time,'%d-%m-%Y %h:%i:%s %p') as edt,
	Service_Engr_Name,Problem_Desc,Maintenance_Desc,Spares_Used,maint.Maintenance_Type_ID,Remarks,
	(Select GROUP_CONCAT(CONCAT(AL_Image_ID, ',' ,Image_Path)) FROM ActivityLog_Image WHERE Activity_LOG_ID='$lid')as ali
	 FROM Maintenance as maint INNER JOIN ActivityLog as actl ON actl.Activity_Log_ID=maint.Activity_Log_ID 
	INNER JOIN Maintenance_Type as mty on mty.Maintenance_Type_ID=maint.Maintenance_Type_ID 
	WHERE maint.Activity_Log_ID='$lid';";
		
	$resa = mysql_query($q, $cxn) or die(mysql_error($cxn));

	$noofrecords=mysql_affected_rows();
	if($noofrecords!=0)
		{
		$row=mysql_fetch_assoc($resa);
		$mid=$row['Machine_ID'];
		$opeid=$row['Operator_ID'];
		$sdt=$row['sdt'];
		$edt=$row['edt'];
		$sename=$row['Service_Engr_Name'];
		$pdesc=$row['Problem_Desc'];
		$maintdesc=$row['Maintenance_Desc'];
		$spares=$row['Spares_Used'];
		$mtypeid=$row['Maintenance_Type_ID'];
		$remark=$row['Remarks'];
		$sdtdb=$row['Start_Date_Time'];
		$edtdb=$row['End_Date_Time'];
		$actid=$row['Activity_ID'];
		$ali=$row['ali'];
		$spmid=$row['Sch_Prev_Maint_IDs'];
		if($spmid!='')
		{$sid=explode(',',$spmid);

		$qs="SELECT SPM_ID FROM SPM_Desc WHERE SPM_Desc_ID=$sid[0];";
		//print($qs);
		$ress = mysql_query($qs, $cxn) or die(mysql_error($cxn));		
		$s=mysql_fetch_assoc($ress);
		$spmid=$s['SPM_ID'];
		$q2="SELECT * FROM SPM_Desc WHERE SPM_ID=".$spmid.";";
		$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$spmt='';
$l=0;
	while($row2=mysql_fetch_assoc($r2))
	{
$c='';
	for($h=0;$h<count($sid);$h++)
	{
	if($sid[$h]==$row2['SPM_Desc_ID']){$c="checked=checked";}	

	}
		$spmt.='<input type="checkbox" name="spmdesc['.$l.']" value="1"'.$c.'><input type="hidden" name="spmid['.$l.']" value="'.$row2['SPM_Desc_ID'].'">'.$row2['SPM_Desc'].'<br>';
		$l++;
	}
	


		}
		$data=$activityid."<|>".$mid."<|>".$opeid."<|>".$sdt."<|>".$edt."<|>".$sename."<|>".$pdesc."<|>".$maintdesc."<|>".$spares."<|>".$mtypeid."<|>".$remark."<|>".$sdtdb."<|>".$edtdb."<|>".$actid."<|>".$ali."<|>".$spmt."<|>".$spmid;
	
		}else 	
		{
			$data="";
		}

} else if($activityid==8) //idle time log
	{

	$q="SELECT actl.Activity_Log_ID,Activity_ID,Machine_ID, Start_Date_Time,End_Date_Time,Operator_ID,np.Idle_ID,
	DATE_FORMAT(Start_Date_Time,'%d-%m-%Y %h:%i:%s %p') as sdt, DATE_FORMAT(End_Date_time,'%d-%m-%Y %h:%i:%s %p') as edt,
	Remarks FROM ActivityLog as actl 
	INNER JOIN NonProduction as np ON np.Activity_Log_ID=actl.Activity_Log_ID 
	LEFT OUTER JOIN Idle_Reason as ir ON ir.Idle_ID=np.Idle_ID WHERE actl.Activity_Log_ID='$lid';";
		
	$resa = mysql_query($q, $cxn) or die(mysql_error($cxn));

	$noofrecords=mysql_affected_rows();
	if($noofrecords!=0)
		{
		$row=mysql_fetch_assoc($resa);
		$mid=$row['Machine_ID'];
		$opeid=$row['Operator_ID'];
		$sdt=$row['sdt'];
		$edt=$row['edt'];
		$remark=$row['Remarks'];
		$sdtdb=$row['Start_Date_Time'];
		$edtdb=$row['End_Date_Time'];
		$actid=$row['Activity_ID'];
		if($row['Idle_ID']!='')
		{

			$q8="SELECT * From Idle_Reason;";
			$r8 = mysql_query($q8, $cxn) or die(mysql_error($cxn));
			
$irs="<label>Reason For Machine Idle</label>";
$irs.="<select name=\"Idle_ID\" id=\"Idle_ID\" class=\"required\">";
$irs.='<option value="">Select Reason</option>';
while ($row8 = mysql_fetch_assoc($r8))
{
	if($row8['Idle_ID']==$row['Idle_ID']){$sel="selected=selected";}else{$sel='';}
$irs.="<option value=".$row8['Idle_ID']." ".$sel.">";
$irs.="$row8[Idle_Reason]</option>";
}
$irs.="</select>";


		}
		$data=$activityid."<|>".$mid."<|>".$opeid."<|>".$sdt."<|>".$edt."<|>".$remark."<|>".$sdtdb."<|>".$edtdb."<|>".$actid."<|>".$irs;
	
		}else 	
		{
			$data="";
		}

}else if(($activityid==4)||($activityid==11))
{

	$q="SELECT prod.Activity_Log_ID, mdq.Drawing_ID,prod.Batch_ID,Activity_ID,Machine_ID, comp.Customer_ID,Operation_Description,Start_Date_Time,End_Date_Time,
	Operator_ID, DATE_FORMAT(Start_Date_Time,'%d-%m-%Y %h:%i:%s %p') as sdt, DATE_FORMAT(End_Date_time,'%d-%m-%Y %h:%i:%s %p') as edt,
	Quantity,Remarks,Program_NO,
	(Select GROUP_CONCAT(CONCAT(AL_Image_ID, ',' ,Image_Path)) FROM ActivityLog_Image WHERE Activity_LOG_ID='$lid')as ali
	 FROM NonProduction as prod INNER JOIN ActivityLog as actl ON actl.Activity_Log_ID=prod.Activity_Log_ID 
	INNER JOIN BNo_MI_Challans as bmc ON bmc.Batch_ID=prod.Batch_ID
	INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
	INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID 
	INNER JOIN Customer as cust on cust.Customer_ID=comp.Customer_ID 
	WHERE prod.Activity_Log_ID='$lid';";


	$resa = mysql_query($q, $cxn) or die(mysql_error($cxn));

	$noofrecords=mysql_affected_rows();
	if($noofrecords!=0)
		{
		$row=mysql_fetch_assoc($resa);
		$mid=$row['Machine_ID'];
		$cid=$row['Customer_ID'];
		$did=$row['Drawing_ID'];
		$oid=$row['Operation_Description'];
		$opeid=$row['Operator_ID'];
		$sdt=$row['sdt'];
		$edt=$row['edt'];
		$qty=$row['Quantity'];
		$remark=$row['Remarks'];
		$pno=$row['Program_NO'];
		$sdtdb=$row['Start_Date_Time'];
		$edtdb=$row['End_Date_Time'];
		$actid=$row['Activity_ID'];
		$bid=$row['Batch_ID'];
		$ali=$row['ali'];
		$data=$activityid."<|>".$mid."<|>".$cid."<|>".$did."<|>".$oid."<|>".$opeid."<|>".$sdt."<|>".$edt."<|>".$qty."<|>".$remark."<|>".$pno."<|>".$sdtdb."<|>".$edtdb."<|>".$actid."<|>".$bid."<|>".$ali;
	
		}else	
		{
			$data="";
		}


}else{
		print("inside nothing");
	$data='Not a defined actgivity';
}
//print($q);


print($data);

?>
