<?php
include('dewdb.inc');
if(isSet($_GET['cid'])){$cid=$_GET['cid'];}else{$cid='';}
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$query="SELECT * FROM Dimn_Desc;";
print("<label>Select Comment</label>");
print("<td><select name=\"Desc_ID\" id=\"Desc_ID\" class=\"required\">");
echo '<option value="">Select Comment</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{

echo "<option value=".$row['Desc_ID'] ." >";
echo "$row[Detailed_Desc]</option>";
}
print("</select></td>");



?>