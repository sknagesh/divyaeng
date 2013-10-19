
<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$mid=$_GET['mid'];

$amid=explode(',', $mid);
$month=$amid[0];
$year=$amid[1];




$query="SELECT DC_NO,Date,Component_Name,Drawing_NO,Mfg_Batch_NO,Commited_Date,Delivery_comments,
DATEDIFF(Commited_Date,Date) as dd FROM MO_Drg_Qty as modq
		INNER JOIN Material_Outward as mo ON mo.Material_Outward_ID=modq.Material_Outward_ID
		INNER JOIN BNo_MI_Challans as bmc ON bmc.MI_Drg_Qty_ID=modq.MI_Drg_Qty_ID
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=modq.Drawing_ID
		WHERE MONTH(Date)=$month AND YEAR(Date)=$year;";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));

print("<table><tr class=\"tt\"><th>Dispatch Date</th><th>Comited Date</th><th>DC NO</th><th>Drawing No</th><th>Component Name</th><th>Batch NO</th><th>Remarks</th></tr>");
$c="ss";
while($row=mysql_fetch_assoc($resa))
{

	if($row['dd']<0){$c="qq";}else{$c="ss";}

	print("<tr class=\"$c\"><td>$row[Date]</td><td>$row[Commited_Date]</td><td>$row[DC_NO]</td><td>$row[Drawing_NO]</td><td>$row[Component_Name]</td>
				<td>$row[Mfg_Batch_NO]</td><td>$row[Delivery_Comments]</td></tr>");
	


}
print("</table>");


?>