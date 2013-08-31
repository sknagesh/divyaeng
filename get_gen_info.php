
<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$query="SELECT * FROM Gen_Info;";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));

print("<table cellspacing=\"5\" cellpadding=\"5\" border=\"1\">");
	print("<tr>");
$i=1;
		while($row=mysql_fetch_assoc($resa))
		{

		print("<td><a href=\"/geninfo/$row[Info_Path]\" target=\"_NEW\">$row[Info_Description]</a></td>");
		if($i==5)
		{
	print("</tr>");
	$i=0;
	print("<tr>");		
		}
$i++;
		}
	


print("</table>");

?>