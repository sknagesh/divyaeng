<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_GET['drawingid'];
//print_r($_POST);
$query="SELECT * FROM Operation WHERE Drawing_ID='$drawingid';";

print("<label for=\"draw\">Select Operation</label>");
print("<select name=\"dOperation_ID\" id=\"dOperation_ID\" class=\"required\">");
echo '<option value="">Select Operation</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
	if($row['Operation_ID']==$did){$sel="selected=\"selected\"";}else{$sel="";}
echo "<option value=".$row['Operation_ID']." $sel >";
echo "$row[Operation_Desc]</option>";
}
print("</select>");



?>