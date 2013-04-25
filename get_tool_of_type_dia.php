<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$ttype=$_GET['ttype'];
$tdia=$_GET['tdia'];
//print_r($_POST);
$query="SELECT * FROM Tool WHERE Tool_Type_ID='$ttype' AND Tool_Dia='$tdia';";
$st="<p><label>Select Preferred Tool</label>";
$st.="<select name=\"Tool_ID_1\" id=\"Tool_ID_1\" class=\"required\">";
$st.="<option value=\"\">Select Tool</option>";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
$st.="<option value=\"$row[Tool_ID]\">";
$st.="$row[Tool_Part_NO] - $row[Tool_Desc]</option>";
}
$st.="</select><div id=\"insert1\"></div></p>";

$st.="<p><label>Select Alternate Tool</label>";
$st.="<select name=\"Tool_ID_2\" id=\"Tool_ID_2\" >";
$st.="<option value=\"\">Select Alternate Tool</option>";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
$st.="<option value=\"$row[Tool_ID]\">";
$st.="$row[Tool_Part_NO] - $row[Tool_Desc]</option>";
}
$st.="</select></select><div id=\"insert2\"></div></p>";

$query="SELECT * FROM Holder;";
$st.="<p><label>Select Holder</label>";
$st.="<select name=\"Holder_ID_1\" id=\"Holder_ID_1\" class=\"required\">";
$st.="<option value=\"\">Select Holder</option>";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
$st.="<option value=\"$row[Holder_ID]\">";
$st.="$row[Holder_Description]</option>";
}
$st.="</select></p>";
$st.="<p><label>Machining Description</label>";
$st.="<input type=\"text\" name=\"tdesc\" id=\"tdesc\" class=\"required\"></p>";
$st.="<p><label>Tool Over Hang</label>";
$st.="<input type=\"text\" name=\"toh\" id=\"toh\" class=\"number\"></p>";
$st.="<p><label>Tool Life In Mins/No Of Comp</label>";
$st.="<input type=\"text\" name=\"tlife\" id=\"tlife\" class=\"number\"></p>";
echo $st;


?>