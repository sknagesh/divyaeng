<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$drawingid=$_GET['drawingid'];


$query="SELECT DISTINCT(bn.Batch_ID),Mfg_Batch_NO FROM Batch_NO as bn
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=bn.Batch_ID
		INNER JOIN MI_Drg_Qty AS mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID 
		WHERE mdq.Drawing_ID='$drawingid';";
//print($query);
print("<label for=\"draw\">Select Batch NO</label>");
print("<select name=\"Batch_ID\" id=\"Batch_ID\" >");
echo '<option value="">Select Batch NO</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));

while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Batch_ID'].">".$row['Mfg_Batch_NO']."</option>";
}
print("</select>");



?>