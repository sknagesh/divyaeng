<?php
include('dewdb.inc');
if(isSet($_GET['aid'])){$aid=$_GET['aid'];}else{$aid='';}
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$query="SELECT * FROM Gage;";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
print("<label>Select Gage</label>");
print("<td><select name=\"Gage_ID\" id=\"Gage_ID\" class=\"required\">");
echo '<option value="">Select Gage</option>';
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Gage_ID'].">";
echo "$row[Gage_Desc]</option>";
}
print("</select></td>");



?>