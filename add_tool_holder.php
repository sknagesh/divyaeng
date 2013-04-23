<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$holdername=$_POST['holdername'];


$query="INSERT INTO Holder (Holder_Description) ";
$query.="VALUES('$holdername');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added New Tool Holder $brandname");	
	
}else
	{
		print("Error Adding Holder");
	}


?>