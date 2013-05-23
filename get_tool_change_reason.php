<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['ncid'])){$ncid=$_GET['ncid'];}else{$ncid="";}
$query="SELECT * FROM Tool_Change_Reasons;";
print("<label for=\"draw\">Select Tool Change Reason</label>");
print("<select name=\"Reason_ID\" id=\"Reason_ID\" class=\"required\">");
echo '<option value="">Select Change Reason</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
	if($row['Reason_ID']==$ncid){$sel="selected=\"selected\"";}else{$sel="";}
echo "<option value=".$row['Reason_ID']." $sel >";
echo "$row[Reason]</option>";
}
print("</select>");



?>