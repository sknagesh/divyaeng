<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$imid=$_GET['imid'];

$query="SELECT Material_Qty FROM Material_Inward WHERE Material_Inward_ID='$imid';";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);

$inqty=$row['Material_Qty'];

$queryb="SELECT Batch_Qty From Batch_NO WHERE Material_Inward_ID='$imid';";

$res = mysql_query($queryb, $cxn) or die(mysql_error($cxn));
$r=mysql_num_rows($res);
$bqty=0;
if($r!=0)
{
while($rowb = mysql_fetch_assoc($res))
{
	$bqty+=$rowb['Batch_Qty'];
	
}
}
$maxbqty=$inqty-$bqty;




$queryp="SELECT mi.Drawing_ID,Material_Qty, EX_Challan_NO,EX_Challan_Date,Purchase_Ref,
		Material_Code Drawing_NO, Component_Name, 
		(SELECT GROUP_CONCAT(Mfg_Batch_NO,' : ',Batch_Qty) FROM Batch_NO as bn WHERE bn.Material_Inward_ID=mi.Material_Inward_ID) AS Batches 
		FROM Material_Inward as mi INNER JOIN Component as comp ON comp.Drawing_ID=mi.Drawing_ID WHERE mi.Material_Inward_ID='$imid';"; 





$res=mysql_query($queryp) or die(mysql_error());
$r=mysql_num_rows($res);

print("Balance Quantity is $maxbqty Nos");

if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Comp Name & Drawing No</th><th>Challan No</th><th>Challan Date</th><th>Purchase Ref</th><th>Quantity Received</th>
		<th>Batch No and Batch Quantity</th></tr>");

		
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Drawing_NO] : $row[Component_Name]</td><td>$row[EX_Challan_NO]</td>
			<td>$row[EX_Challan_Date]</td><td>$row[Purchase_Ref]</td><td>$row[Material_Qty]</td>
		<td>$row[Batches]</td></tr>");
	
}
print("</table>");
}

?>				