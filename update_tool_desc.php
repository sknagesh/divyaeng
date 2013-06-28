<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
//print_r($_POST);

$toolid=$_POST['Tool_ID_1'];
$supid=$_POST['Supplier_ID'];
$ttypeid=$_POST['Tool_Type_ID'];
$nce=$_POST['nce'];
$oal=$_POST['oal'];
$tdesc=$_POST['tdesc'];
$tpno=$_POST['tpno'];
$tdia=$_POST['tdia'];
$tfl=$_POST['tfl'];
$tmake=$_POST['Brand_ID'];
if(isSet($_POST['coating'])){$coating=$_POST['coating'];}else{$coating="";}
if(isSet($_POST['tcr'])){$tcr=$_POST['tcr'];}else{$tcr="";}
if(isSet($_POST['tang'])){$tang=$_POST['tang'];}else{$tang="";}
if(isSet($_POST['usel'])){$usel=$_POST['usel'];}else{$usel="";}
if(isSet($_POST['sdia'])){$sdia=$_POST['sdia'];}else{$sdia="";}
if(isSet($_POST['tremark'])){$tremark=$_POST['tremark'];}else{$tremark="";}
if(isSet($_POST['tprice'])){$tprice=$_POST['tprice'];}else{$tprice="";}

$query="UPDATE Tool SET
	Supplier_ID='$supid',
	Tool_Type_ID='$ttypeid',
	Tool_Part_NO='$tpno',
	Tool_Desc='$tdesc',
	Tool_Dia='$tdia',
	Tool_FL='$tfl',
	Tool_Corner_Rad='$tcr',
	Tool_Angle='$tang',
	No_Of_Edges='$nce',
	Tool_OAL='$oal',
	Tool_Useful_Length='$usel',
	Shank_Dia='$sdia',
	Brand_ID='$tmake',
	Tool_Coating='$coating',
	Tool_Remarks='$tremark',
	Tool_Price='$tprice'
	 WHERE Tool_ID=$toolid;";



//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Updated Tool $tdesc - $tpno");	
	
}else
	{
		print("Error Updating");
	}


?>