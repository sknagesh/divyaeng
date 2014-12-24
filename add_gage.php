<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$gagedesc=$_POST['gagedesc'];


$query="INSERT INTO Gage (Gage_Desc) VALUES('$gagedesc');";
//print("<br>Query is '$query'");

$result = mysql_query($query, $cxn) or die(mysql_error($cxn));
print("One gage $gagedesc added");


?>