<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$opid=$_GET['opid'];


$query="SELECT Stage_Drawing_Path FROM Operation WHERE Operation_ID='$opid';";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);

$odpath='/drawings/'.$row['Stage_Drawing_Path'];
if($row['Stage_Drawing_Path']!='')
{
print("<a href=\"$odpath\" target=\"_NEW\">Stage Drawing</a>");
}else{
	print("No Stage Drawing Found");
}


?>