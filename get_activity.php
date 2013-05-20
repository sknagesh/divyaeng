<?php
include('dewdb.inc');
if(isSet($_GET['aid'])){$aid=$_GET['aid'];}else{$aid='';}
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$query="SELECT * FROM Activity;";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
print("<label>Select Activity</label>");
print("<td><select name=\"Activity_ID\" id=\"Activity_ID\" class=\"required\">");
echo '<option value="">Select Activity</option>';
while ($row = mysql_fetch_assoc($resa))
{
	if($row['Activity_ID']==$aid){$sel= " selected=selected";} else{$sel='';}
	echo "<option value=".$row['Activity_ID']." $sel>";
echo "$row[Activity_Name]</option>";
}
print("</select></td>");



?>