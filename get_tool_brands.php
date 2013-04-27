<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$supid=$_GET['sid'];
if($supid!='')
{
$query="SELECT tb.Brand_ID, Brand_Description FROM Tool_Brand as tb 
		INNER JOIN Supplier_Brand as sb ON sb.Brand_ID=tb.Brand_ID WHERE sb.Supplier_ID=$supid;";
	
}else{
$query="SELECT * FROM Tool_Brand;";	
}

//print($query);
print("<label>Tool Make</label>");
print("<select name=\"Brand_ID\" id=\"Brand_ID\" class=\"required\">");
echo '<option value="">Select Tool Brand</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Brand_ID'].">";
echo "$row[Brand_Description]</option>";
}
print("</select></td></tr>");



?>