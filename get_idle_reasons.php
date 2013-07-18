<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$supid=$_GET['sid'];
$query="SELECT * FROM Idle_Reason;";

//print($query);
print("<label>Reason For Machine Idle</label>");
print("<select name=\"Idle_ID\" id=\"Idle_ID\" class=\"required\">");
echo '<option value="">Select Reason</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Idle_ID'].">";
echo "$row[Idle_Reason]</option>";
}
print("</select></td></tr>");



?>