<?
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$id=$_GET['drawingid'];

if($id!='')
{

$query="SELECT MO_Drg_Qty_ID,Outward_Qty,DATE_FORMAT(Date,'%d/%m/%Y') as dcdt,DC_NO FROM MO_Drg_Qty as modq
		INNER JOIN Material_Outward as mo ON mo.Material_Outward_ID=modq.Material_Outward_ID
		INNER JOIN BNo_MI_Challans as bmc ON bmc.MI_Drg_Qty_ID=modq.MI_Drg_Qty_ID
		INNER JOIN Batch_NO AS bn ON bn.Batch_ID=bmc.Batch_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=modq.Drawing_ID
		WHERE modq.Drawing_ID='$id';";

//print($query);


print("<label>Select Dc No</label>");
print("<select name=\"Mo_Drg_Qty_ID\" id=\"Mo_Drg_Qty_ID\" class=\"required\">");
echo '<option value="">Select DC No</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['MO_Drg_Qty_ID']." >";
echo "DC No: $row[DC_NO] Dated: $row[dcdt] Qty: $row[Outward_Qty] Nos</option>";
}
print("</select>");



}else{

	print("");
}
?>