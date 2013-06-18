<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
//mysql_select_db('ShopLog',$cxn) or die("error opening db: ".mysql_error());
mysql_select_db('Process',$cxn) or die("error opening db: ".mysql_error());
$custid=$_GET['custid'];
//$query="SELECT * FROM Component WHERE Customer_ID='$custid';";
$query="SELECT * FROM Part WHERE Customer_ID='$custid';";
print("<label for=\"draw\">Select Drawing</label>");
print("<select name=\"sDrawing_ID\" id=\"sDrawing_ID\" $rcr>");
echo '<option value="">Select Drawing</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
	if($row['Drawing_ID']==$did){$sel="selected=\"selected\"";}else{$sel="";}
echo "<option value=".$row['Drawing_ID']." $sel >";
echo "$row[Drawing_NO] - $row[Component_Name]</option>";
}
print("</select>");



?>