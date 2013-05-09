<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$query="SELECT * FROM Maintenance_Type;";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
print("<label>Select Maintenance Type</label>");
print("<td><select name=\"Maintenance_Type_ID\" id=\"Maintenance_Type_ID\" class=\"required\">");
echo '<option value="">Select Maintenance Type</option>';
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Maintenance_Type_ID'].">";
echo "$row[Maintenance_Description]</option>";
}
print("</select></td>");



?>