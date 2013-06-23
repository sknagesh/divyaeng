<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawid=$_GET['drawingid'];

$query="SELECT EX_Challan_NO,mi.Material_Inward_ID,Material_Qty,
		(select sum(Outward_Qty) from MO_Drg_Qty where Drawing_ID=131 and Material_Inward_ID=mi.Material_Inward_ID) as dqty 
		FROM Material_Inward as mi INNER JOIN MI_Drg_Qty as midq on midq.Material_Inward_ID=mi.Material_Inward_ID 
		WHERE midq.Drawing_ID='$drawid'";
 
// print($query);

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$r=mysql_num_rows($resa);
if($r!='')
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Challan No</th><th>Challan Date</th><th>Quantity Received</th><th>Qty Already Dispatched</th>
		<th>Dispatch Qty</th><th>Remarks</th></tr>");
$i=0;
	while($row = mysql_fetch_assoc($resa))
	{
		if($row['dqty']!=''){$adqty=$row['dqty'];}else{$adqty=0;}
		$mqty=$row['Material_Qty']-$adqty;
print("<tr><td>$row[EX_Challan_NO]</td><td>$row[cdate]</td><td>$row[Material_Qty]</td>
	<td>$adqty</td><td>
	<input type=\"text\" id=\"dqty[$i]\" name=\"dqty[$i]\" size=\"25\" max=\"$mqty\"></td>
	 <td><input type=\"text\" id=\"remark[$i]\" name=\"remark[$i]\" size=\"75\" >
	 <input type=\"hidden\" id=\"miid[$i]\" name=\"miid[$i]\" value=\"$row[Material_Inward_ID]\"></td>
	</tr>");
$i++;
	}
print("</table>");

}else{
	
	print("No open Challens for this Drawing");
}
?>				