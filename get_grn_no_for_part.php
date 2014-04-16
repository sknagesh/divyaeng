<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$modq=$_GET['modq'];
$did=$_GET['drawingid'];


$query="SELECT GR_NO,DATE_FORMAT(GR_Date,'%d/%m/%Y') as grd FROM GR_Nos WHERE MO_Drg_Qty_ID=$modq;";

//print($query);

$res=mysql_query($query,$cxn) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{

print('<table><tr><th>GR No</th><th>GR Date</th>');
while($row=mysql_fetch_assoc($res))
{
			print("<tr><td>$row[GR_NO]</td><td>$row[grd]</td></tr>");

}
print('</table>');
}else
{
	print("NO GR No Received For This DC");
}




?>