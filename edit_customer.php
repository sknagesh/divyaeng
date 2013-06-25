<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$custid=$_POST['Customer_ID'];
$custname=$_POST['name'];
$custsname=$_POST['sname'];
if(isSet($_POST['cper'])){$cper=$_POST['cper'];}else {$cper="";}
$addl1=$_POST['addl1'];
if(isSet($_POST['addl2'])){$addl2=$_POST['addl2'];}else {$addl2="";}
if(isSet($_POST['phone'])){$phone=$_POST['phone'];}else{$phone="";}
$tinno=$_POST['tinno'];
$panno=$_POST['panno'];
if(isSet($_POST['excise'])){$excise=$_POST['excise'];}else{$excise="";}

$query="UPDATE Customer SET
		Customer_Name='$custname',
		Customer_Name_Short='$custsname',
		Contact_Person='$cper',
		Address_L1='$addl1',
		Address_L2='$addl2',
		Phone_NO='$phone',
		TIN_NO='$tinno',
		Excise_NO='$excise',
		PAN_NO='$panno' WHERE Customer_Id='$custid' ";


//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Updated Details For $custname");	
	
}else
	{
		print("Error Updating customer");
	}


?>