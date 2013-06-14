<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$drawid=$_GET['drawid'];

$query="SELECT mi.Material_Inward_ID,EX_Challan_NO,EX_Challan_Date,Purchase_Ref,Purchase_Ref_Date, Drawing_ID,
		Material_Qty,mdq.MI_Drg_Qty_ID, Qty_In_Batch FROM Material_Inward as mi 
		INNER JOIN MI_Drg_Qty as mdq ON mdq.Material_Inward_ID=mi.Material_Inward_ID 
		left outer JOIN BNo_MI_Challans as bmc ON bmc.MI_Drg_Qty_ID=mdq.MI_Drg_Qty_ID 
		WHERE mdq.Drawing_ID='$drawid' AND mi.Open='1';";

		 
//print($query); 
print("<tr><td><label>Select Quantites From Each Open Challan</label></td></tr>");
print("<tr><th>Challan No and Max Batch Quantity</th><th>Batch Quantity</th><th>Heat Codes</th></tr>");

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$j=0;
while ($row = mysql_fetch_assoc($resa))
{
if($row['EX_Challan_NO']!=''){$cno=$row['EX_Challan_NO'].' - '.$row['EX_Challan_Date'];}else{$cno=$row['Purchase_Ref'].' - '.$row['Purchase_Ref_Date'];}

	$maxbqty=$row['Material_Qty']-$row['Qty_In_Batch'];

print("<tr><td>$cno , Max Batch Qty is $maxbqty: </td>");
print("<td><input type=\"hidden\" name=\"MI_Drg_Qty_ID[$j]\" id=\"MI_Drg_Qty_ID[$j]\" value=\"$row[MI_Drg_Qty_ID]\">");
print("<input type=\"text\" name=\"mqty[$j]\" id=\"mqty[$j]\" class=\"number\" max=\"$maxbqty\"></td>");
print("<td><input type=\"text\" name=\"hcode[$j]\" id=\"hcode[$j]\" maxlength=\"100\" ></td>");
$j++;
}



?>