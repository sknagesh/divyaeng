<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);
/*
$custid=$_POST['Customer_ID'];

$materials=$_POST['materials'];
$noofmaterials=$_POST['noofmaterials'];

if(isSet($_POST['cno'])){$cno=$_POST['cno'];}else{$cno="";}
if(isSet($_POST['cdatedb'])){$cdate=$_POST['cdatedb'];}else{$cdate="";}
if(isSet($_POST['gpno'])){$gpno=$_POST['gpno'];}else{$gpno="";}
if(isSet($_POST['gpdatedb'])){$gpdate=$_POST['gpdatedb'];}else{$gpdate="";}
if(isSet($_POST['dano'])){$dano=$_POST['dano'];}else {$dano="";}
if(isSet($_POST['dadatedb'])){$dadate=$_POST['dadatedb'];}else{$dadate="";}

if(isSet($_POST['pref'])){$pref=$_POST['pref'];}else{$pref="";}
if(isSet($_POST['prdatedb'])){$prdate=$_POST['prdatedb'];}else{$prdate="";}
$query="INSERT INTO Material_Inward 
		(Customer_ID,
		Open
		)

		VALUES('$custid',
				'1');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
	$challanid=mysql_insert_id();
$querychallan="INSERT INTO MI_Challans (Material_Inward_ID,
										EX_Challan_NO,EX_Challan_Date,
										DA_NO,DA_Date,
										GP_NO,GP_Date,
										Purchase_Ref,Purchase_Ref_Date)
							VALUES ('$challanid','$cno','$cdate',
									'$dano','$dadate',
									'$gpno','$gpdate','$pref','$prdate');";

$reschallan=mysql_query($querychallan) or die(mysql_error());

	$mlist=explode(",", $materials);

	$j=0;
	$k=0;
	while($j<$noofmaterials)
	{
		$Drawing_ID[$j]=$mlist[$k];
		$k++;
		$Material_Qty[$j]=$mlist[$k];
		$k++;
		$Material_Code[$j]=$mlist[$k];
		$k++;
		$j++;
	}

	$j=0;
	while($j<$noofmaterials)
	{

$querymtl="INSERT INTO MI_Drg_Qty (Material_Inward_ID,Drawing_ID,Material_Qty,Material_Code)
								VALUES('$challanid','$Drawing_ID[$j]','$Material_Qty[$j]','$Material_Code[$j]');";
$resmaterial=mysql_query($querymtl) or die(mysql_error());
										$j++;
	}
	
	
	
$nm=mysql_affected_rows();	

	print("<br>Record ID=$challanid and Added $noofmaterials material Quantites");	


	
}else
	{
		print("<br>Error Adding New Data");
	}



*/

?>