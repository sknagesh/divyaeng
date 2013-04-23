<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$query="SELECT * FROM Instrument;";
//print("<label for=\"draw\">Select Customer</label>");
print("<td><select name=\"Instrument_ID\" id=\"Instrument_ID\" class=\"required\">");
echo '<option value="">Select Instrument</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Instrument_ID'].">";
echo "$row[Instrument_SLNO] - $row[Instrument_Description]</option>";
}
print("</select></td>");

?>