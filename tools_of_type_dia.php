<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$ttype=$_GET['ttype'];
$tdia=$_GET['tdia'];
//print_r($_POST);
$query="SELECT * FROM Tool WHERE Tool_Type_ID='$ttype' AND Tool_Dia='$tdia';";
$st='<div id="tid1">';
$st.='<p><label>Select Tool being replaced</label>';
$st.="<select name=\"Tool_ID_1\" id=\"Tool_ID_1\" class=\"required\">";
$st.="<option value=\"\">Select Changed Tool</option>";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
$st.="<option value=\"$row[Tool_ID]\">";
$st.="$row[Tool_Part_NO] - $row[Tool_Desc]</option>";
}
$st.="</select></div><div id=\"insert1\"></div></p>";
$st.='<div id="tid2">';
$st.="<p><label>Tool Replaced By</label>";
$st.="<select name=\"Tool_ID_2\" id=\"Tool_ID_2\" >";
$st.="<option value=\"\">Select Replacement Tool</option>";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
$st.="<option value=\"$row[Tool_ID]\">";
$st.="$row[Tool_Part_NO] - $row[Tool_Desc]</option>";
}
$st.="</select></select></div><div id=\"insert2\"></div></p>";

echo $st;


?>