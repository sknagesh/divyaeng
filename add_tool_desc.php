<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

//print_r($_POST);

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
if(isSet($_POST['minstock'])){$minstock=$_POST['minstock'];}else{$minstock="";}

$query="INSERT INTO Tool (Supplier_ID,
								Tool_Type_ID,
								Tool_Part_NO,
								Tool_Desc,
								Tool_Dia,
								Tool_FL,
								Tool_Corner_Rad,
								Tool_Angle,
								No_Of_Edges,
								Tool_OAL,
								Tool_Useful_Length,
								Shank_Dia,
								Brand_ID,
								Tool_Coating,
								Tool_Remarks,
								Tool_Price,
								Min_Stock_Qty) ";
$query.="VALUES('$supid',
				'$ttypeid',
				'$tpno',
				'$tdesc',
				'$tdia',
				'$tfl',
				'$tcr',
				'$tang',
				'$nce',
				'$oal',
				'$usel',
				'$sdia',
				'$tmake',
				'$coating',
				'$tremark',
				'$tprice',
				'$minstock');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added new Tool $tdesc - $tpno");	
	
}else
	{
		print("Error Adding");
	}


?>