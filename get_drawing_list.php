<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$custid=$_GET['cid'];
$query="SELECT * FROM Component WHERE Customer_ID='$custid' ORDER BY Drawing_NO;";
		print("<h2>Drawing Configuration List</h2>");
		print("<h3>Document. Ref: DEW/MR/R/12 Issue NO.: 0  Date: 01-07-2013</h3>");

print("<table cellspacing=\"1\" cellborder=\"1\" width=\"100%\">");
print("<tr  class=\"t\" ><th>Drawing ID</th><th>Drawing NO</th>
		<th>Customer Drawing Ref</th><th>Component Name</th>
		<th>Drawing Rev</th><th>Material Code</th><th>Customer Drawing</th></tr>");
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));

	$c="q";
while ($row = mysql_fetch_assoc($resa))
{
if($row['Customer_Drawing']!=''){$dpath='/drawings/'.$row['Customer_Drawing'];

$d='<a class="pdf" href="'.$dpath.'" target="_NEW" title="Opens Part Drawing in a new TAB">Drawing</a>';
}else{$d='';}

	print("<tr class=\"$c\"><td>$row[Drawing_ID]</td>
				<td>$row[Drawing_NO]</td>
				<td>$row[Cust_Drawing_NO]</td>
				<td>$row[Component_Name]</td>
				<td>$row[Drawing_Rev]</td>
				<td>$row[Material_Code]</td>
				<td>$d</td>
				</tr>");
	if($c=="q"){$c="s";}else{$c="q";}
}
print("</table>");



?>