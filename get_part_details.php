<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/enquiry/';
$drawingid=$_GET['drawingid'];
//print_r($_POST);
$q="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(Clamping_Time))) as clt,SEC_TO_TIME(SUM(TIME_TO_SEC(Machining_Time))) as mt FROM Operation WHERE Drawing_ID='$drawingid';";
$r = mysql_query($q, $cxn) or die(mysql_error($cxn));
while($rr=mysql_fetch_assoc($r))
{
$cltime=$rr['clt'];
$mctime=$rr['mt'];
}
$query="SELECT * FROM Component WHERE Drawing_ID='$drawingid';";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
print('<table name="ptable" id="ptable">');
$row = mysql_fetch_assoc($resa);

	$dpath='/drawings/'.$row['Customer_Drawing'];
	$ppath='/drawings/'.$row['Process_Sheet'];
	print('<p style="font-size:14px;color:green">Part Name:</p>'.$row['Component_Name']);
	print('<p style="font-size:14px;color:green">Drawing NO and Rev No:</p>'.$row['Drawing_NO'].' Rev: '.$row['Drawing_Rev']);
	print("<tr><td height=\"35\"><a class=\"pdf\" href=\"$dpath\" target=\"_NEW\" title=\"Opens Part Drawing in a new TAB\">Part Drawing</a></td>");
	print("<td height=\"35\"><a class=\"pdf\" href=\"$ppath\" target=\"_NEW\" title=\"Opens Process Sheet in a new TAB\">Process Sheet</a></td>");
	print("</tr>");
	
	print('</table>');

	print('<table border=\"1\" cellspacing=\"1\">');

//	print("<tr><td><label>Total Clamping Time (h:m:s)</label></td><td height=\"55\">$cltime</td>
//			<td><label>Total Machining Time (h:m:s)</label></td><td height=\"35\">$mctime</td></tr>");
	print("<tr><td><label>Raw Material Size</label></td><td height=\"35\">$row[Raw_Material_Size]</td>");
	print("<td><label>Pre Machined Blank Size</label></td><td height=\"10\">$row[Pre_Machined_Blank_Size]</td>
		<td>Weight Difference</td><td>$row[Scrap_Weight] Kg</td><tr>");
	print("<tr><td><label>Finish Size</label></td><td height=\"10\">$row[Finish_Size]</td>");
	print("<td><label>Component Material</label></td><td height=\"10\">$row[Component_Material]</td>
			<td>Material Code</td><td>$row[Material_Code]</td><tr>");
	print('</table>');



?>