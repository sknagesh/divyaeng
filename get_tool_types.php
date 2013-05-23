<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['id'])){$id=$_GET['id'];}else{$id='';}
if(isSet($_GET['it'])){$it=$_GET['it'];}else{$it='';}
//print_r($_POST);
$query="SELECT * FROM Tool_Type;";
print("<p><label>Select Tool Type</label>");
print("<select name=\"Tool_Type_ID$id\" id=\"Tool_Type_ID$id\" class=\"required\">");
echo '<option value="">Select Tool Type</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$i=1;
$j=array(5,6);
while ($row = mysql_fetch_assoc($resa))
{
	if(($it=='1' && in_array($i, $j))){
echo "<option value=".$row['Tool_Type_ID'].">";
echo "$row[Tool_Type]</option>";
	}else 
	if(($it=='2' && !in_array($i, $j))){
echo "<option value=".$row['Tool_Type_ID'].">";
echo "$row[Tool_Type]</option>";
	}else
	if($it==''){
echo "<option value=".$row['Tool_Type_ID'].">";
echo "$row[Tool_Type]</option>";
	}
$i++;
}
print("</select></p>");



?>