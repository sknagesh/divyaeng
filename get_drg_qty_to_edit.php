<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$miid=$_GET['cid'];
$csid=$_GET['csid'];
$query="SELECT MI_Drg_Qty_ID,Material_Qty,Material_Code,Component_Name,Drawing_NO,mdq.Drawing_ID  
		FROM MI_Drg_Qty AS mdq INNER JOIN Material_Inward as mi ON
		mi.Material_Inward_ID=mdq.Material_Inward_ID
		INNER JOIN Component AS comp ON comp.Drawing_ID=mdq.Drawing_ID WHERE mdq.Material_Inward_ID='$miid';";


$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
$r=mysql_num_rows($res);
if($r!='')
{
	$j=0;
print("<table><tr><th>Comp No and Name</th><th>Quantity</th><th>Material Code</th></tr>");	
while($row = mysql_fetch_assoc($res))
{
		$qs="SELECT * FROM Component WHERE Customer_ID='$csid';";
$ress = mysql_query($qs, $cxn) or die(mysql_error($cxn));
		
	print("<tr><td>");
	print("<select name=\"Drawing_ID[$j]\" id=\"Drawing_ID[$j]\" class=\"required\">");
print('<option value="">Select Drawing</option>');
while ($rows = mysql_fetch_assoc($ress))
{
	if($rows['Drawing_ID']==$row['Drawing_ID']){$sel="selected=\"selected\"";}else{$sel="";}
echo "<option value=".$rows['Drawing_ID']." $sel >";
echo "$rows[Drawing_NO] - $rows[Component_Name]</option>";
}
print("</select></td><td>
<input type=\"text\" name=\"mqty[$j]\" id=\"mqty[$j]\" value=\"$row[Material_Qty]\" class=\"required\"></td>
<td><input type=\"text\" name=\"mcode[$j]\" id=\"mcode[$j]\" value=\"$row[Material_Code]\" class=\"required\"></td></tr>");
$j++;
}

}
print("</table>");



?>