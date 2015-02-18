<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT *,Gage_Desc FROM Gage_SlNo as gsl
        INNER JOIN Gage as g ON g.Gage_ID=gsl.Gage_ID ORDER BY Gage_Desc ASC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
	$d=date('d-m-Y');
	print("<p><label>List of Customer property as on $d</label></p><br>");

print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Gage ID</th><th>Gage Description</th><th>Gage Serial No</th><th>Gage Type</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Gage_ID]</td><td>$row[Gage_Desc]</td><td>$row[Gage_No]</td><td>$row[Gage_Type]</td></tr>");
	
}
print("</table>");
}
else {
	print("No Gages Added Yet");
}
?>