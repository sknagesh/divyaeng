<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//$drawid=$_GET['drawid'];

$query="SELECT EX_Challan_NO,DATE_FORMAT(EX_Challan_Date,'%d/%m/%Y') as cdate,Purchase_Ref,
		DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prf,Material_Qty,Qty_In_Batch,
		Mfg_Batch_NO,Batch_Remarks,DATE_FORMAT(Commited_Date,'%d/%m/%Y') as comitdate,
		Drawing_NO,Component_Name FROM Material_Inward AS mi
		INNER JOIN MI_Drg_Qty AS mdq ON mdq.Material_Inward_ID=mi.Material_Inward_ID 
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.MI_Drg_Qty_ID=mdq.MI_Drg_Qty_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID WHERE Batch_Under_Progress='1';";
//print($query);

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$r=mysql_num_rows($resa);
if($r!='')
{
	$c="q";
print("<table cellspacing=\"1\" cellborder=\"1\" >");
print("<tr class=\"t\"><th>Drawing NO and Name</th><th>Challan No</th><th>Challan Date</th><th>Purchase Ref</th>
			<th>Purchase Ref Date</th><th>Quantity Received</th><th>Qty Used In Batch</th><th>Batch No</th><th>Commitment Date</th><th>Batch Remarks</th></tr>");

	while($row = mysql_fetch_assoc($resa))
	{
		if($row['cdate']=='00/00/0000'){$edt='';}else{$edt=$row['cdate'];}
		if($row['prf']=='00/00/0000'){$prf='';}else{$prf=$row['prf'];}
	print("<tr class=\"$c\"><td>$row[Drawing_NO] $row[Component_Name]</td>
							<td>$row[EX_Challan_NO]</td><td>$edt</td>
							<td>$row[Purchase_Ref]</td><td>$prf</td>
							<td>$row[Material_Qty]</td>
							<td>$row[Qty_In_Batch]</td>
							<td>$row[Mfg_Batch_NO]</td>
							<td>$row[comitdate]</td>
							<td>$row[Batch_Remarks]</td></tr>");
if($c=="q"){$c="s";}else{$c="q";}
	}
print("</table>");

}else{
	
	print("There Ar No Open Batches");
}
?>				