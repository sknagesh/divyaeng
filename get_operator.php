<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['opeid'])){$opeid=$_GET['opeid'];}else{$opeid="";}
if(isSet($_GET['insp'])){$insp=$_GET['insp'];}else{$insp="";}
if($insp=='')
{
$query="SELECT * FROM Operator;";
}else{
	$query="SELECT * FROM Operator WHERE Qualified_To_Inspect=1;";
}
if($insp=='')
{
print("<label for=\"draw\">Select Operator</label>");
}else{
	print("<label for=\"draw\">Select Inspector</label>");
}
print("<select name=\"Operator_ID\" id=\"Operator_ID\" class=\"required\">");
echo '<option value="">Select Name</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
	if($row['Operator_ID']==$opeid){$sel="selected=\"selected\"";}else{$sel="";}
echo "<option value=".$row['Operator_ID']." $sel >";
echo "$row[Operator_Name]</option>";
}
print("</select>");



?>