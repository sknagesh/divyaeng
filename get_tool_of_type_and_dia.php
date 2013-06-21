<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$ttype=$_GET['ttype'];
$tdia=$_GET['tdia'];
$query="SELECT Tool_ID,Brand_Description,Tool_Part_NO,Tool_Desc FROM Tool as t  
		INNER JOIN Tool_Brand AS tb ON tb.Brand_ID=t.Brand_ID
		 WHERE Tool_Type_ID='$ttype' AND Tool_Dia='$tdia' ORDER  BY t.Brand_ID;";
$st="<select name=\"Tool_ID_1\" id=\"Tool_ID_1\" class=\"required\">";
$st.="<option value=\"\">Select Tool</option>";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
$st.="<option value=\"$row[Tool_ID]\">";
$st.="$row[Brand_Description] Make $row[Tool_Desc] : $row[Tool_Part_NO]</option>";
}
$st.="</select>";
echo $st;


?>