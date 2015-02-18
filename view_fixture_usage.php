<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$fid=$_GET['fid'];

$query="SELECT Component_Name, Drawing_NO,Operation_Desc,Fixture_NO FROM Operation as ope
				INNER JOIN Component as comp on comp.Drawing_ID=ope.Drawing_ID
				INNER JOIN Ope_Fixt_Map as ofm ON ofm.Operation_ID=ope.Operation_ID
				WHERE ofm.Fixture_NO like('%$fid%') AND In_Op_List!=1 ORDER BY ope.Operation_ID Desc;";

//print($query);


$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);

if($r!=0)
{
	print("This Fixture is used in $r Operations<br><br>");
print("<table border=\"1\" cellspacing=\"1\" bgcolor=\"#7FFFD4\">");
print("<tr><th>Drawing No and Name</th><th>Operation Desc</th><th>Fixture No</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Drawing_NO] - $row[Component_Name]</td>
				<td>$row[Operation_Desc]</td><td>$row[Fixture_NO]</td></tr>");
	
}
print("</table>");
}
else {
	print("This Tool is Currently Not in Use");
}
?>