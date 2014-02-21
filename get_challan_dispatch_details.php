<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$cid=$_GET['cid'];


$query="SELECT EX_Challan_NO,DATE_FORMAT(EX_Challan_Date,'%d/%m/%Y') as ecd,mi.Material_Inward_ID,Material_Qty,Drawing_NO,Customer_Name_Short,
		Component_Name, Purchase_Ref, DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prd,midq.Drawing_ID
		FROM Material_Inward as mi INNER JOIN MI_Drg_Qty as midq on midq.Material_Inward_ID=mi.Material_Inward_ID
		INNER JOIN Component as comp on comp.Drawing_Id=midq.Drawing_ID 
		INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID   
		WHERE mi.Material_Inward_ID=$cid";

//print($query);

print("<h2>Order Register (Completed) DEW/PRD/R/03 Rev 1 Dated 01/07/2013</h2>");
$res=mysql_query($query,$cxn) or die(mysql_error());

print('<table><tr><th>ID</th><th>Challan No</th><th>Challan Date</th><th>Drawing</th>
	<th>Qty Received</th>');
while($row=mysql_fetch_assoc($res))
{
	if($row['EX_Challan_NO']==''){$cn=$row['Purchase_Ref'];$cnd=$row['prd'];}else{$cn=$row['EX_Challan_NO'];$cnd=$row[ecd];}
	print("<tr class=\"q\"><td>$row[Material_Inward_ID]</td><td>$cn</td><td>$cnd</td>
	<td>$row[Drawing_NO], $row[Component_Name]</td><td>$row[Material_Qty]</td></tr><tr>");

		$q1="SELECT *,DC_NO,DATE_FORMAT(Date,'%d/%m/%Y') as dcdate,DATE_FORMAT(Commited_Date,'%d/%m/%Y') as cd,DATE_FORMAT(Deposit_Date,'%d/%m/%Y') as dd,Mfg_Batch_NO from Material_Outward as mo
		INNER JOIN MO_Drg_Qty as modq ON modq.Material_Outward_ID=mo.Material_Outward_ID
		INNER JOIN MI_Drg_Qty as midq ON midq.MI_Drg_Qty_ID=modq.MI_Drg_Qty_ID
		INNER JOIN BNo_MI_Challans as bmc ON bmc.MI_Drg_Qty_ID=midq.MI_Drg_Qty_ID
		INNER JOIN Batch_NO as bn ON bn.Batch_ID=bmc.Batch_ID
		WHERE midq.Material_Inward_ID=$row[Material_Inward_ID] AND midq.Drawing_ID=$row[Drawing_ID];";
		$r1=mysql_query($q1,$cxn) or die(mysql_error());
		$rn=mysql_num_rows($r1);
		if($rn!=0)
		{
		print("<tr class=\"s\"><td>Dispatch Details</td></tr>");
		print('<table cellspacing="2px"><tr class="s"><th>Comited Date</th><th>Deposit Date</th><th>Dispatch Date</th><th>DC No</th><th>Batch NO</th><th>Qty</th></tr>');
		while($row1=mysql_fetch_assoc($r1))
			{
			print("<tr class=\"s\"><td>$row1[cd]</td><td>$row1[dd]</td><td>$row1[dcdate]</td><td>$row1[DC_NO]</td><td>$row1[Mfg_Batch_NO]</td><td>$row1[Outward_Qty]</td></tr>");
			}
//			print("</table>");
		}

print("</tr>");
}
print('</table>');



?>