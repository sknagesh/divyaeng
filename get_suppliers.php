<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['bid'])){$bid=$_GET['bid'];}else{$bid="";}
//print_r($_POST);

if($bid!='')
{
$query="SELECT Supplier_Name,sup.Supplier_ID FROM Supplier as sup 
		INNER JOIN Supplier_Brand as sb ON sb.Supplier_ID=sup.Supplier_ID
		INNER JOIN Tool_Brand as tb ON tb.Brand_ID=sb.Brand_ID WHERE tb.Brand_ID=$bid;";
	
	
}else{
$query="SELECT * FROM Supplier;";
}

//print($query);
print("<select name=\"Supplier_ID\" id=\"Supplier_ID\" class=\"required\">");
echo '<option value="">Select Tool Supplier</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Supplier_ID'].">";
echo "$row[Supplier_Name]</option>";
}
print("</select></td></tr>");



?>