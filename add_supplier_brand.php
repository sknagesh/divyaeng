<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$brandid=$_POST['Brand_ID'];
$suppid=$_POST['Supplier_ID'];

$query="INSERT INTO Supplier_Brand (Brand_ID,Supplier_ID) ";
$query.="VALUES('$brandid','$suppid');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added New supplier to Brand");	
	
}else
	{
		print("Error Adding Brand");
	}


?>