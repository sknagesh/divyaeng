<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$drawid=$_GET['drawid'];

$query="SELECT * FROM Material_Inward WHERE Drawing_ID='$drawid' AND Open='1';";
//print("<label for=\"draw\">Select Customer</label>");
print("<td><select name=\"Inward_ID\" id=\"Inward_ID\" class=\"required\">");
echo '<option value="">Select Material_Inward</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Material_Inward_ID'].">";
echo "$row[EX_Challan_NO] - $row[EX_Challan_Date] - $row[Purchase_Ref]</option>";
}
print("</select></td>");


?>