<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$dcomment=$_POST['dcomm'];
$ddescid=$_POST['Desc_ID'];

$desc=explode('/', $dcomment);

foreach ($desc as $key => $value) {
$query="INSERT INTO Dimn_Comment (Desc_ID,Comment) ";
$query.="VALUES('$ddescid','$value');";
$res=mysql_query($query) or die(mysql_error());
	
}

?>