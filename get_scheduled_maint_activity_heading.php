<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());


$mid=$_GET['mid'];

$q="SELECT * FROM Scheduled_PM WHERE Machine_ID='$mid';";

$res=mysql_query($q) or die(mysql_error());

print("<label>Select Scheduled Maintenance</label>");
print("<select name=\"SPM_ID\" id=\"SPM_ID\" class=\"required\">");
echo '<option value="">Select Maintenance Schedule</option>';
while ($row = mysql_fetch_assoc($res))
{
echo "<option value=".$row['SPM_ID'].">";
echo "$row[SPM_Title]</option>";
}
print("</select>");


?>