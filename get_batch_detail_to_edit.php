<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$bid=$_GET['bid'];

$query="SELECT *,EX_Challan_NO,DATE_FORMAT(EX_Challan_Date,'%d/%m/%Y') as cdate,Purchase_Ref,
		DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prf,BNo_MI_Challan_ID FROM Batch_NO as bn
		INNER JOIN BNo_MI_Challans as bmc ON bmc.Batch_ID=bn.Batch_ID
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Material_Inward as mi ON mi.Material_Inward_ID=mdq.Material_Inward_ID
		INNER JOIN Component as comp ON comp.Drawing_ID=mdq.Drawing_ID WHERE bn.Batch_ID='$bid';";
//print($query);

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$r=mysql_num_rows($resa);
if($r!='')
{
$i=0;
	while($row=mysql_fetch_assoc($resa)){
if($row['cdate']!='00/00/0000'){$dt=$row['cdate'];}else{$dt=$row['prf'];}
$bremark=$row['Batch_Remarks'];
if($row['EX_Challan_NO']!=''){$cno=$row['EX_Challan_NO'];}else{$cno=$row['Purchase_Ref'];}
print('Challan/Inv NO '.$cno.' dated '.$dt);
print("<input type=\"hidde\" id=\"bncid[$i]\" name=\"bncid[$i]\" value=\"$row[BNo_MI_Challan_ID]\">");
print("Heat Code <input type=\"text\" id=\"hcode[$i]\" name=\"hcode[$i]\" value=\"$row[Heat_Code]\"><br>");
}

print("Batch Remarks<input type=\"text\" name=\"bremark\" id=\"bremark\" value=\"$bremark\"/>");

}else{
	
	print("Can not Edit This Batch Details");
}
?>				