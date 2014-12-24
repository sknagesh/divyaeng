<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());


$q1="SELECT modq.Material_Outward_ID,Date, MO_Drg_Qty_ID, Drawing_NO, Component_Name,DC_NO,Outward_Qty from MO_Drg_Qty as modq
		INNER JOIN Material_Outward as mo ON mo.Material_Outward_ID=modq.Material_Outward_ID
		INNER JOIN Component as comp on comp.Drawing_ID=modq.Drawing_ID
		WHERE mo.Date>'2014-04-01' AND comp.Customer_ID=1 Order By mo.Date Asc;";
$r1=mysql_query($q1,$cxn) or die(mysql_error());
$headerprinted=0;
while($row1=mysql_fetch_assoc($r1))
{

$query="SELECT GR_NO FROM GR_Nos WHERE MO_Drg_Qty_ID=$row1[MO_Drg_Qty_ID];";


//print($query);

$res=mysql_query($query,$cxn) or die(mysql_error());
$r=mysql_num_rows($res);
if($r==0)
{

if($headerprinted==0)
{
print('<table><tr><th>Outward ID</th><th>Part</th><th>Dispatch Date</th><th>DC NO</th><th>Qty</th></tr>');
$headerprinted=1;
}
	print("<tr><td>$row1[MO_Drg_Qty_ID]</td><td>$row1[Drawing_NO] - $row1[Component_Name]</td><td>$row1[Date]</td><td>$row1[DC_NO]</td><td>$row1[Outward_Qty]</td></tr>");

}


}
print('</table>');
?>