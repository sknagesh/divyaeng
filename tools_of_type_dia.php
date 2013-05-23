<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$ttype=$_GET['ttype'];
$tdia=$_GET['tdia'];
if(isSet($_GET['id'])){$id=$_GET['id'];}else{$id='';}
//print_r($_POST);
$query="SELECT * FROM Tool WHERE Tool_Type_ID='$ttype' AND Tool_Dia='$tdia';";
$st="<select name=\"Tool_ID$id\" id=\"Tool_ID$id\" class=\"required\">";
$st.="<option value=\"\">Select Changed Tool</option>";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
$st.="<option value=\"$row[Tool_ID]\">";
$st.="$row[Tool_Part_NO] - $row[Tool_Desc]</option>";
}
$st.="</select></div></p>";

echo $st;


?>