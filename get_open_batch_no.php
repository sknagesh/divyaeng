<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingd=$_GET['drawingid'];
if(isSet($_GET['bid'])){$oid=$_GET['bid'];}else{$oid="";}
//print_r($_POST);

$query2="SELECT DISTINCT(bn.Batch_ID),Mfg_Batch_NO FROM Batch_NO as bn
		INNER JOIN BNo_MI_Challans AS bmc ON bmc.Batch_ID=bn.Batch_ID
		INNER JOIN MI_Drg_Qty AS mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID 
		WHERE mdq.Drawing_ID='$drawingd' and Batch_Under_Progress='1';";

//print($query2);

print("<label for=\"bno\">Select Batch ID</label>");
print("<select name=\"Batch_ID\" id=\"Batch_ID\" class=\"required\">");
echo '<option value="">Select Batch NO</option>';
$resa2 = mysql_query($query2, $cxn) or die(mysql_error($cxn));
while ($row2 = mysql_fetch_assoc($resa2))
{
if($row2['Batch_ID']==$oid){$sel=" selected=selected";}else{$sel='';}	
echo "<option value=".$row2['Batch_ID'].$sel.">".$row2['Mfg_Batch_NO']."</option>";
}
print("</select><div id=\"br\"> </div>");



?>
