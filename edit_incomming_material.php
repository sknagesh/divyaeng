<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$custid=$_POST['Customer_ID'];

$miid=$_POST['Material_Inward_ID'];


if(isSet($_POST['cno'])){$cno=$_POST['cno'];}else{$cno="";}
if(isSet($_POST['cdatedb'])){$cdate=$_POST['cdatedb'];}else{$cdate="";}
if(isSet($_POST['gpno'])){$gpno=$_POST['gpno'];}else{$gpno="";}
if(isSet($_POST['gpdatedb'])){$gpdate=$_POST['gpdatedb'];}else{$gpdate="";}
if(isSet($_POST['dano'])){$dano=$_POST['dano'];}else {$dano="";}
if(isSet($_POST['dadatedb'])){$dadate=$_POST['dadatedb'];}else{$dadate="";}

if(isSet($_POST['pref'])){$pref=$_POST['pref'];}else{$pref="";}
if(isSet($_POST['prdatedb'])){$prdate=$_POST['prdatedb'];}else{$prdate="";}
if(isSet($_POST['del'])){$del=$_POST['del'];}else{$del='';}
if(isSet($_POST['Drawing_ID_OLD'])){$did=$_POST['Drawing_ID_OLD'];}else{$did='';}
if(isSet($_POST['mqtyold'])){$mqty=$_POST['mqtyold'];}else{$mqty='';}
if(isSet($_POST['mcodeold'])){$mcode=$_POST['mcodeold'];}else{$mcode='';}
if(isSet($_POST['miqid'])){$miqid=$_POST['miqid'];}else{$miqid='';}
if(isSet($_POST['materials'])){$materials=$_POST['materials'];}else{$materials='';}
if(isSet($_POST['noofmaterials'])){$noofmaterials=$_POST['noofmaterials'];}else{$noofmaterials='';}





$query="UPDATE Material_Inward SET 
			EX_Challan_NO='$cno',EX_Challan_Date='$cdate',
			GP_NO='$gpno',GP_Date='$gpdate',
			DA_NO='$dano',DA_Date='$dadate',
			Purchase_Ref='$pref',Purchase_Ref_Date='$prdate' 
			WHERE Material_Inward_ID='$miid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();


if($miqid!='')
{

	
	for($j=0;$j<count($miqid);$j++)
	{
		$qup="UPDATE MI_Drg_Qty SET Drawing_ID='$did[$j]', Material_Qty='$mqty[$j]',Material_Code='$mcode[$j]' 
				WHERE MI_Drg_Qty_ID='$miqid[$j]';";
		$resq=mysql_query($qup) or die(mysql_error());
	}
	
}

if($del!='')
{
	$del=array_values($del);
	for($j=0;$j<count($del);$j++)
	{
		$qd="DELETE FROM MI_Drg_Qty WHERE Mi_Drg_Qty_ID='$del[$j]';";
	//	print($qd);
		$resd=mysql_query($qd) or die(mysql_error());
		
	}
	
	
}


	$mlist=explode(",", $materials);  ///add new material

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
								VALUES('$miid','$Drawing_ID[$j]','$Material_Qty[$j]','$Material_Code[$j]');";
$resmaterial=mysql_query($querymtl) or die(mysql_error());
										$j++;
	}




?>