<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawid=$_GET['drawid'];

$query="SELECT EX_Challan_NO,DATE_FORMAT(EX_Challan_Date,'%d/%m/%Y') as cdate,Purchase_Ref,
		DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prf,Material_Qty,Qty_In_Batch,
		Mfg_Batch_NO,Batch_Remarks FROM Material_Inward AS mi
		INNER JOIN MI_Drg_Qty AS mdq ON mdq.Material_Inward_ID=mi.Material_Inward_ID 
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.MI_Drg_Qty_ID=mdq.MI_Drg_Qty_ID 
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID WHERE mdq.Drawing_ID='$drawid' and Batch_Under_Progress='1';";
//print($query);

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$r=mysql_num_rows($resa);
if($r!='')
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Challan No</th><th>Challan Date</th><th>Purchase Ref</th>
			<th>Purchase Ref Date</th><th>Quantity Received</th><th>Qty Used In Batch</th><th>Batch No</th><th>Batch Remarks</th></tr>");

	while($row = mysql_fetch_assoc($resa))
	{
print("<tr><td>$row[EX_Challan_NO]</td><td>$row[cdate]</td><td>$row[Purchase_Ref]</td>
			<td>$row[prf]</td><td>$row[Material_Qty]</td><td>$row[Qty_In_Batch]</th><th>$row[Mfg_Batch_NO]</th><td>$row[Batch_Remarks]</td></tr>");

	}
print("</table>");

}else{
	
	print("No open batches for this Drawing");
}
?>				