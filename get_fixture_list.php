<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$query="SELECT DISTINCT Fixture_NO as fno FROM Ope_Fixt_Map;";

print("<select name=\"Fixture_Name\" id=\"Fixture_Name\" class=\"required\">");
echo '<option value="">Select Fixture</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
$v=$row['fno'];
print("<option value='$v'>");
print($row['fno']."</option>");
//echo "<option value=".$row['fno']." >";
//echo "$row[fno]</option>";
}
print("</select>");



?>