<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_GET['drawingid'];
//print_r($_POST);
$query="SELECT Preview_Image FROM Component WHERE Drawing_ID='$drawingid';";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);
$preview='/drawings/'.$row['Preview_Image'];
print("<img src=\"$preview\" width=\"100%\">");
?>