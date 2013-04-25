<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$toolid=$_GET['toolid'];
if(isSet($_GET['iid'])){$iid=$_GET['iid'];}else{$iid='';}


$query="SELECT Insert_ID,Tool_Part_NO,Tool_Desc,Insert_Part_NO,Insert_Description 
		FROM Inserts as ins INNER JOIN Tool as t ON t.Tool_ID=ins.Tool_ID WHERE ins.Tool_ID='$toolid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{

print("<label for=\"draw\">Select Insert</label>");
print("<select name=\"Insert_ID$iid\" id=\"Insert_ID$iid\" class=\"required\">");
echo '<option value="">Select Insert</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Insert_ID'].">";
echo "$row[Insert_Part_NO] - $row[Insert_Description]</option>";
}
print("</select>");

}
else {
	print("This Tool Is Not Inserted OR No Inserts Added For this Tool");
}
?>