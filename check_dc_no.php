<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$dcno=$_GET['dcno'];
$query="SELECT DC_NO FROM Material_Outward WHERE DC_NO='$dcno';";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);

$r=mysql_affected_rows();
if($r!=0)
{
print("<p style=\"font-size:20px;color:red\">DC No Already Used, Please enter New DC No</p>");	
}

?>