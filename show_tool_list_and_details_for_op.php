<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$opid=$_GET['opid'];

$qop="SELECT * FROM Operation WHERE Operation_ID='$opid';";
$r=mysql_query($qop) or die(mysql_error());
$rope=mysql_fetch_assoc($r);

$ctime=$rope['Clamping_Time'];
$mtime=$rope['Machining_Time'];
$fno=$rope['Fixture_NO'];
$opnote=$rope['Operation_Notes'];
$odpath='/drawings/'.$rope['Operation_Drawing'];
$ppath=$rope['P_Path'].$rope['Program_NO'];
print("<table cellspacing=\"5\">");
print("<tr><td><label>Fixture No:</label></td><td>$fno</td></tr>");
//print("<td><label>Clamping Time For This OP:</label></td><td height=\"35\">$ctime</td>");
//print("<td><label>Machining Time for This OP:</label></td><td height=\"35\">$mtime</td></tr>");
print("<tr><td><label>Note:</label></td><td>$opnote</td></tr>");
if($rope['Operation_Drawing']!='')
{
print("<tr><td><a href=\"$odpath\" target=\"_NEW\">Stage Drawing</a></td>");
}
if($rope['P_Path']!='')
{
print("<td><a href=\"$ppath\" target=\"_NEW\">NC Program</a></td></tr>");	
}else{
	print("<td><label>NC Program:</label> $rope[Program_NO]</td></tr>");
}


print("</table>");
$query="SELECT Ope_Tool_ID,t.Tool_Part_NO,t.Tool_Desc,Insert_ID_1,Insert_ID_2,tb1.Brand_Description as bd1,tb2.Brand_Description as bd2,
		tt.Tool_Part_NO as tpn2,tt.Tool_Desc as tde2,ins2.Insert_Part_NO as ip2,ins2.Insert_Description as id2,
		inse.Insert_Description,
		t.Tool_Dia,Tool_ID_1,Tool_ID_2,Ope_Tool_OH,Ope_Tool_Desc,Holder_Description FROM Ope_Tool AS ot 
		INNER JOIN Tool as t ON t.Tool_ID=ot.Tool_ID_1 
		LEFT OUTER JOIN Inserts AS inse ON inse.Insert_ID=ot.Insert_ID_1
		LEFT OUTER JOIN Inserts AS ins2 ON ins2.Insert_ID=ot.Insert_ID_2
		LEFT OUTER JOIN Tool as tt ON tt.Tool_ID=ot.Tool_ID_2 
		INNER JOIN Tool_Brand as tb1 ON tb1.Brand_ID=t.Brand_ID
		INNER JOIN Tool_Brand as tb2 ON tb2.Brand_ID=tt.Brand_ID
		INNER JOIN Holder AS h ON h.Holder_ID=ot.Holder_ID_1
		WHERE ot.Operation_ID='$opid';";



//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\" id=\"ttble\">");
print("<tr><th>Tool Info</th><th>Preferred Tool/Alternate Tool</th><th>Description</th><th>Insert</th><th>Holder</th><th>Tool Overhang</th></tr>");
while($row=mysql_fetch_assoc($res))
{
	$td=$row['bd1'].' Make '.$row['Tool_Desc']." (".$row['Tool_Part_NO'].") ".$row['Insert_Part_NO'].")";
	if($row['tpn2']!=""){$td.="<font color=\"red\"> OR </font><font color=\"green\">".$row['bd2'].' Make '.$row['tde2']." (".$row['tpn2'].") ".$row['ip2']." </font>";}
	print("<tr><td><input type=\"radio\" name=\"tinfo\" class=\"tinfo\" id=\"tinfo\" value=\"$row[Tool_ID_1]\"></input>
	</td><td>$td</td><td>$row[Ope_Tool_Desc]</td>
	<td>$row[Insert_Description] <font color=\"green\"> $row[id2]</font></td>
	<td>$row[Holder_Description]</td><td>$row[Ope_Tool_OH]</td></tr>");
	
}
print("</table>");
}
else {
	print("<br><br>No Tools Added For this Drawing Yet");
}
?>