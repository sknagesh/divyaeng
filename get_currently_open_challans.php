<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());


$query="SELECT EX_Challan_NO,EX_Challan_Date,mi.Material_Inward_ID,Material_Qty,Drawing_NO,Customer_Name_Short,
		Component_Name,DATEDIFF(CURDATE(),EX_Challan_Date) as age,
		(select sum(Outward_Qty) from MO_Drg_Qty where Drawing_ID=midq.Drawing_ID and MI_Drg_Qty_ID=midq.MI_Drg_Qty_ID) as dqty 
		FROM Material_Inward as mi INNER JOIN MI_Drg_Qty as midq on midq.Material_Inward_ID=mi.Material_Inward_ID
		INNER JOIN Component as comp on comp.Drawing_Id=midq.Drawing_ID 
		INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID   
		WHERE mi.Open=1";

//print($query);

print("<h2>Currently Open Challans</h2>");
$res=mysql_query($query,$cxn) or die(mysql_error());
print('<table border="1px"><tr><th>Customer</th><th>Challan No</th><th>Challan Date</th><th>Drawing</th>
	<th>Qty</th><th>Dispatched Qty</th><th>Age</th>');
while($row=mysql_fetch_assoc($res))
{
	print("<tr><td>$row[Customer_Name_Short]</td><td>$row[EX_Challan_NO]</td><td>$row[EX_Challan_Date]</td>
	<td>$row[Drawing_NO], $row[Component_Name]</td><td>$row[Material_Qty]</td><td>$row[dqty]</td><td>$row[age]</td>");
}
print('</table>');



?>