<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_GET);
if(isSet($_GET['nid'])){$nid=$_GET['nid'];}else{$nid="";}
if(isSet($_GET['sdate'])){$sdate=$_GET['sdate'];}else{$sdate="";}
if(isSet($_GET['edate'])){$edate=$_GET['edate'];}else{$edate="";}



if($nid!='')
{
	
	$mid="AND ncr.NC_ID='$nid'";
}

if($sdate!='')
{
	
	$sdate="AND Start_Date_Time>='$sdate'";
}

if($edate!='')
{
	
	$edate="AND Start_Date_Time<='$edate'";
}



$query="SELECT actl.Activity_Log_ID,actl.Activity_ID, NC_Description,Status,
		DATE_FORMAT(Start_Date_Time,'%d/%m/%Y') as sdt,Component_Name,Drawing_NO,Mfg_Batch_NO,
		actl.Operator_ID,Remarks,Operator_Name,Problem_Description,
		(SELECT GROUP_CONCAT('/logimages/',Image_Path) FROM ActivityLog_Image as ali 
		WHERE ali.Activity_Log_ID=actl.Activity_Log_ID) as ip
		 FROM ActivityLog as actl  
		INNER JOIN Activity as act ON act.Activity_ID=actl.Activity_ID 
		INNER JOIN Operator as ope ON ope.Operator_ID=actl.Operator_ID
		INNER JOIN NonConformance as ncr ON ncr.Activity_Log_ID=actl.Activity_Log_ID
		INNER JOIN NCType AS nct ON nct.NC_ID=ncr.NC_ID
		INNER JOIN NCStatus as ncs ON ncs.NC_Status_ID=ncr.NC_Status_ID
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=ncr.Batch_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=ncr.Batch_ID
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID
		WHERE actl.Activity_ID='15' $mid $sdate $edate
		  ORDER BY Start_Date_Time DESC;";
	
//print("$query<br>");

print("<br><h1>Non Conformance Reports</h1><br>");
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$noofrecords=mysql_affected_rows();
if($noofrecords!=0)
{
	$c="q";
print("<table cellspacing=\"1\" cellborder=\"1\" >");
print("<tr class=\"t\" ><th>Log ID</th><th>NC Type</th><th>NC Status</th><th>NC Date</th><th>Drawing Name and No</th><th>Batch ID</th>
						<th>Reported By</th><th>Brief Description</th><th>Remarks</th><th>Full Report</th></tr>");
while ($row = mysql_fetch_assoc($resa))
{
		

		$id=$row['Activity_Log_ID'];
		$nctype=$row['NC_Description'];
		$ncs=$row['Status'];
		$sdt=$row['sdt'];
		$opename=$row['Operator_Name'];
		$prob=$row['Problem_Description'];	
		$remarks=$row['Remarks'];
		$bid=$row['Mfg_Batch_NO'];
		$dno=$row['Drawing_NO'].' - '.$row['Component_Name'];
		
		if($row['ip']!='')
		{
			$images=explode(',', $row['ip']);
			
			$ip="<table style=\"width:80px\"><tr><td>$activity<td></tr><tr><td>";
			$y=1;
			for($z=0;$z<count($images);$z++)
			{
				$ip.="<a class=\"pdf\" href=\"$images[$z]\" target=\"_NEW\" title=\"View Report in New Tab\">$y&nbsp;&nbsp;&nbsp;  </a>";
				$y++;
			}
			$ip.="</td></tr></table>";			
		}else{
			
			$ip='';
		}
		

print("<tr class=\"$c\"><td>$id</td><td>$nctype</td><td>$ncs</td><td>$sdt</td><td>$dno</td><td>$bid</td><td>$opename</td><td>$prob</td><td>$remarks</td>
	<td align=\"center\">$ip</td></tr>");
if($c=="q"){$c="s";}else{$c="q";}
}
print("</table>");
}else{print("No NC Reports For This Period");}



?>