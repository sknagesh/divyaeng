<?php
include('dewdb.inc');
$cxns = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('ShopLog',$cxns) or die("error opening db: ".mysql_error());


$sopid=$_GET['sopid'];
$dopid=$_GET['dopid'];
//print_r($_POST);
$query="SELECT * FROM Operation WHERE Operation_ID='$sopid';";
print($query);
$resa = mysql_query($query, $cxns) or die(mysql_error($cxns));
$row = mysql_fetch_assoc($resa);

$cxnd = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxnd) or die("error opening db: ".mysql_error());

$qd="UPDATE Operation SET Operation_Description='$row[Operation_Desc]' WHERE Operation_ID='$dopid';";

print($qd);

?>