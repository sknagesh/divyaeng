<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$opid=$_GET['opid'];

$qop="SELECT Program_NO,NC_Prog_Path,Operation_Notes,Stage_Drawing_Path,
		(SELECT GROUP_CONCAT(Fixture_NO) FROM Ope_Fixt_Map WHERE Operation_ID='$opid') as fixt 
		FROM Operation WHERE Operation_ID='$opid';";
$r=mysql_query($qop) or die(mysql_error());
$rope=mysql_fetch_assoc($r);

$fno=$rope['fixt'];
$opnote=$rope['Operation_Notes'];
$odpath='/drawings/'.$rope['Stage_Drawing_Path'];
$ppath=$rope['NC_Prog_Path'].$rope['Program_NO'];
print("<table cellspacing=\"5\">");
print("<tr><td><label>Fixture No:</label></td><td>$fno</td></tr>");
//print("<td><label>Clamping Time For This OP:</label></td><td height=\"35\">$ctime</td>");
//print("<td><label>Machining Time for This OP:</label></td><td height=\"35\">$mtime</td></tr>");
print("<tr><td><label>Note:</label></td><td>$opnote</td></tr>");
if($rope['Operation_Drawing']!='')
{
print("<tr><td><a href=\"$odpath\" target=\"_NEW\">Stage Drawing</a></td>");
}
if($rope['NC_Prog_Path']!='')
{
print("<td><a href=\"$ppath\" target=\"_NEW\">NC Program</a></td></tr>");	
}else{
	print("<td><label>NC Program:</label> $rope[Program_NO]</td></tr>");
}


print("</table>");
/*$query="SELECT Ope_Tool_ID,t.Tool_Part_NO,t.Tool_Desc,Insert_ID_1,Insert_ID_2,tb1.Brand_Description as bd1,tb2.Brand_Description as bd2,
		tt.Tool_Part_NO as tpn2,tt.Tool_Desc as tde2,ins2.Insert_Part_NO as ip2,ins2.Insert_Description as id2,
		inse.Insert_Description,
		t.Tool_Dia,Tool_ID_1,Tool_ID_2,Ope_Tool_OH,Ope_Tool_Desc,Holder_Description FROM Ope_Tool AS ot 
		INNER JOIN Tool as t ON t.Tool_ID=ot.Tool_ID_1 
		LEFT OUTER JOIN Inserts AS inse ON inse.Insert_ID=ot.Insert_ID_1
		LEFT OUTER JOIN Inserts AS ins2 ON ins2.Insert_ID=ot.Insert_ID_2
		INNER JOIN Tool as tt ON tt.Tool_ID=ot.Tool_ID_2 
		INNER JOIN Tool_Brand as tb1 ON tb1.Brand_ID=t.Brand_ID
		INNER JOIN Tool_Brand as tb2 ON tb2.Brand_ID=tt.Brand_ID
		INNER JOIN Holder AS h ON h.Holder_ID=ot.Holder_ID_1
		WHERE ot.Operation_ID='$opid';";
*/



$query='SELECT Ope_Tool_ID,Ope_Tool_OH,Ope_Tool_Desc,Holder_Description,Tool_ID_1, 
		(SELECT CONCAT(Tool_Desc," ( ",Tool_Part_NO," )") FROM Tool WHERE Tool_ID=ot.Tool_ID_1) as td1, 
		(SELECT CONCAT(Tool_Desc," ( ",Tool_Part_NO," )") FROM Tool WHERE Tool_ID=ot.Tool_ID_2)as td2, 
		(SELECT CONCAT(Insert_Part_NO," ",Insert_Description) FROM Inserts WHERE Insert_ID=ot.Insert_ID_1) as i1,
		(SELECT CONCAT(Insert_Part_NO," ",Insert_Description) FROM Inserts WHERE Insert_ID=ot.Insert_ID_2) as i2,
		(SELECT Brand_Description FROM Tool_Brand as tb INNER JOIN Tool as t1 ON t1.Brand_ID=tb.Brand_ID WHERE t1.Tool_ID=ot.Tool_ID_1) as tb1,
		(SELECT Brand_Description FROM Tool_Brand as tb2 INNER JOIN Tool as t2 ON t2.Brand_ID=tb2.Brand_ID WHERE t2.Tool_ID=ot.Tool_ID_2) as tb2 
		FROM Ope_Tool AS ot INNER JOIN Holder AS h ON h.Holder_ID=ot.Holder_ID_1 WHERE ot.Operation_ID="'.$opid.'";';


//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\" id=\"ttble\">");
print("<tr><th>Tool ID</th><th>Preferred Tool and Insert</th><th>Alternate Tool and Insert</th><th>Description</th><th>Holder</th><th>Tool Overhang</th></tr>");
while($row=mysql_fetch_assoc($res))
{
	if($row['td2']!=''){$t2='<font color=\"green\">'.$row['tb2'].' Make '.$row['td2'].' '.$row['i2'].'</font>';}else{$t2='';}
	print("<tr><td><input type=\"radio\" name=\"tinfo\" class=\"tinfo\" id=\"tinfo\" value=\"$row[Tool_ID_1]\"></input></td>
	<td width=\"450px\">$row[tb1] Make $row[td1] $row[i1]</td>
	<td>$t2</td>
	<td>$row[Ope_Tool_Desc]</td>
	<td>$row[Holder_Description]</td><td>$row[Ope_Tool_OH]</td></tr>");
	
}
print("</table>");
}
else {
	print("<br><br>No Tools Added For this Drawing Yet");
}
?>