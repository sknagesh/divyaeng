<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$drawid=$_GET['drawid'];

$query="SELECT mi.Material_Inward_ID,EX_Challan_NO,EX_Challan_Date,Purchase_Ref,Purchase_Ref_Date, Drawing_ID 
		 FROM Material_Inward as mi 
		 INNER Join MI_Challans as mc ON mc.Material_Inward_ID=mi.Material_Inward_ID 
		 INNER JOIN MI_Drg_Qty as mdq ON mdq.Material_Inward_ID=mi.Material_Inward_ID WHERE mdq.Drawing_ID='$drawid' AND mi.Open='1';";
//print("<label for=\"draw\">Select Customer</label>");
print("<td><select name=\"Inward_ID\" id=\"Inward_ID\" class=\"required\">");
echo '<option value="">Select Material_Inward</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Material_Inward_ID'].">";
if($row['EX_Challan_NO']!=''){$cno=$row['EX_Challan_NO'].' - '.$row['EX_Challan_Date'];}else{$cno=$row['Purchase_Ref'].' - '.$row['Purchase_Ref_Date'];}


echo "$cno</option>";
}
print("</select></td>");


?>