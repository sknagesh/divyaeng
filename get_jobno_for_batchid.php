<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$bid=$_GET['bid'];
$opid=$_GET['opid'];
$query2="SELECT Job_NO FROM Dimn_Observation WHERE Batch_ID='$bid' AND Operation_ID='$opid';";

//print($query2);
print("<label>Job No</label>");
print("<select name=\"Job_NO\" id=\"Job_NO\" class=\"required\">");
echo '<option value="">Select Job NO</option>';
$resa2 = mysql_query($query2, $cxn) or die(mysql_error($cxn));
while ($row2 = mysql_fetch_assoc($resa2))
{
echo "<option value=".$row2['Job_NO'].">".$row2['Job_NO']."</option>";
}
print("</select>");



?>
