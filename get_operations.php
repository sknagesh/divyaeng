<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_GET['drawingid'];
if(isSet($_GET['oid'])){$did=$_GET['oid'];}else{$did="";}
if(isSet($_GET['hcomp'])){$hcomp=$_GET['hcomp'];}else{$hcomp="";}
if(isSet($_GET['id'])){$id=$_GET['id'];}else{$id="";}
//print_r($_POST);
$query="SELECT * FROM Operation WHERE Drawing_ID='$drawingid' ORDER BY Operation_Desc;";

print("<label for=\"draw\">Select Operation</label>");
print("<select name=\"Operation_ID$id\" id=\"Operation_ID$id\" class=\"required\">");
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