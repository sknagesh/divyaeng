
<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$toolid=$_GET['toolid'];
$query="SELECT 
			Tool_Dia,Tool_FL,Shank_Dia,Tool_Corner_Rad,Tool_Useful_Length, Tool_OAL,Qty_New_SF,Tool_Remarks,Qty_Resharpened_SF,Qty_New
			 FROM Tool AS t 
			 LEFT OUTER JOIN Tool_Qty as tq ON tq.Tool_ID=t.Tool_ID
			 WHERE t.Tool_ID='$toolid';";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);

$tdia=$row['Tool_Dia'];
$tfl=$row['Tool_FL'];
$tshankdia=$row['Shank_Dia'];
$tcornerrad=$row['Tool_Corner_Rad'];
$tufl=$row['Tool_Useful_Length'];
$toal=$row['Tool_OAL'];
$qsfnew=$row['Qty_New_SF'];
$qsfresh=$row['Qty_Resharpened_SF'];
$tremark=$row['Tool_Remarks'];
$qnew=$row['Qty_New'];

print("<table cellspacing=\"3\" cellpadding=\"5\">
		<tr><th>Tool Dia</th><th>Shank Dia</th><th>Flute Length</th><th>Cr. Radius/Angle</th>
		<th>Neck Length</th><th>Length</th><th>New Tools In shop Floor</th><th>Resharpened Tools In Shopfloor</th><th>New Tools in Stock</th></tr>");
print("<tr><td>$tdia</td><td>$tshankdia</td><td>$tfl</td><td>$tcornerrad</td>
			<td>$tufl</td><td>$toal</td><td align=\"center\">$qsfnew</td><td align=\"center\">$qsfresh</td><td align=\"center\">$qnew</td>

</tr></table>");
if($tremark!='')
{
print("Tool Specific Remarks: $tremark");
}
?>