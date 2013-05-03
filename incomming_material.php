<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);

$custid=$_POST['Customer_ID'];
$drawid=$_POST['Drawing_ID'];
$mcode=$_POST['mcode'];
$mqty=$_POST['mqty'];
if(isSet($_POST['cno'])){$cno=$_POST['cno'];}else{$cno="";}
if(isSet($_POST['cdatedb'])){$cdate=$_POST['cdatedb'];}else{$cdate="";}
if(isSet($_POST['gpno'])){$gpno=$_POST['gpno'];}else{$gpno="";}
if(isSet($_POST['gpdatedb'])){$gpdate=$_POST['gpdatedb'];}else{$gpdatedb="";}
if(isSet($_POST['dano'])){$dano=$_POST['dano'];}else {$dano="";}
if(isSet($_POST['dadatedb'])){$dadate=$_POST['dadatedb'];}else{$dadate="";}

if(isSet($_POST['pref'])){$pref=$_POST['pref'];}else{$pref="";}

$query="INSERT INTO Material_Inward 
		(Customer_ID,Drawing_ID,
		Purchase_Ref,
		Ex_Challan_NO,Ex_Challan_Date,
		GP_NO,GP_Date,
		DA_NO,DA_Date,
		Material_Qty,
		Open,
		Material_Code)

		VALUES('$custid','$drawid',
				'$pref',
				'$cno','$cdate',
				'$gpno','$gpdate',
				'$dano','$dadate',
				'$mqty',
				'1','$mcode');";

print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
	$challanid=mysql_insert_id();
print("<br>Record ID=$challanid");	
	
}else
	{
		print("<br>Error Adding New Data");
	}





?>