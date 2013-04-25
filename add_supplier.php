<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$supplier=$_POST['supplier'];
$contact=$_POST['contact'];
if(isSet($_POST['phone'])){$phone=$_POST['phone'];}else{$phone="";}


$query="INSERT INTO Supplier (Supplier_Name,
								Contact_Name,
								Supplier_Phone) ";
$query.="VALUES('$supplier',
				'$contact',
				'$phone');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added new Supplier $supplier");	
	
}else
	{
		print("Error Adding");
	}


?>