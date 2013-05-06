<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$custid=$_GET['custid'];
//print_r($_POST);
$query="SELECT mic.Material_Inward_ID,EX_Challan_NO,DATE_FORMAT(EX_Challan_Date,'%d/%m/%Y') as ecd,
				DA_NO,DATE_FORMAT(DA_Date,'%d/%m/%Y') as dad,GP_NO,DATE_FORMAT(GP_Date,'%d/%m/%Y') as gpd,
				Purchase_Ref,DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prd,MI_Ch_ID FROM 
				MI_Challans as mic INNER JOIN Material_Inward as mi ON mi.Material_Inward_ID=mic.Material_Inward_ID;";

print("<label for=\"draw\">Select Challans</label>");
print("<select name=\"MI_ID\" id=\"MI_ID\" class=\"required\">");
echo '<option value="">Select Challan to Edit</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['MI_Ch_ID']." $sel >";
if($row['EX_Challan_NO']!=''){$desc=$row['EX_Challan_NO'].' - '.$row['ecd'];}else{$desc=$row['Purchase_Ref'].' - '.$row['prd'];}
echo "$desc</option>";
}
print("</select>");



?>