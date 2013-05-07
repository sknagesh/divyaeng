<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingd=$_GET['drawingid'];
if(isSet($_GET['oid'])){$oid=$_GET['oid'];}else{$oid="";}
//print_r($_POST);

$query2="SELECT Mfg_Batch_NO,Batch_ID FROM Batch_NO as bn
		INNER JOIN MI_Drg_Qty AS mdq ON mdq.Material_Inward_ID=bn.Material_Inward_ID 
		WHERE bn.Batch_Under_Progress=1 AND mdq.Drawing_ID='$drawingd';";

//print($query2);

print("<label for=\"bno\">Select Batch ID</label>");
print("<select name=\"Batch_ID\" id=\"Batch_ID\" class=\"required\">");
echo '<option value="">Select Batch NO</option>';
$resa2 = mysql_query($query2, $cxn) or die(mysql_error($cxn));
while ($row2 = mysql_fetch_assoc($resa2))
{
	
echo "<option value=".$row2['Batch_ID'].">".$row2['Mfg_Batch_NO']."</option>";
}
print("</select>");



?>
