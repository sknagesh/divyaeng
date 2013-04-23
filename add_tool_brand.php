<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$brandname=$_POST['brandname'];


$query="INSERT INTO Tool_Brand (Brand_Description) ";
$query.="VALUES('$brandname');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added New Brand $brandname");	
	
}else
	{
		print("Error Adding Brand");
	}


?>