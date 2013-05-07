<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$custid=$_POST['Customer_ID'];

$miid=$_POST['MI_ID'];


if(isSet($_POST['cno'])){$cno=$_POST['cno'];}else{$cno="";}
if(isSet($_POST['cdatedb'])){$cdate=$_POST['cdatedb'];}else{$cdate="";}
if(isSet($_POST['gpno'])){$gpno=$_POST['gpno'];}else{$gpno="";}
if(isSet($_POST['gpdatedb'])){$gpdate=$_POST['gpdatedb'];}else{$gpdate="";}
if(isSet($_POST['dano'])){$dano=$_POST['dano'];}else {$dano="";}
if(isSet($_POST['dadatedb'])){$dadate=$_POST['dadatedb'];}else{$dadate="";}

if(isSet($_POST['pref'])){$pref=$_POST['pref'];}else{$pref="";}
if(isSet($_POST['prdatedb'])){$prdate=$_POST['prdatedb'];}else{$prdate="";}
if(isSet($_POST['del'])){$del=$_POST['del'];}else{$del='';}
if(isSet($_POST['Drawing_ID'])){$did=$_POST['Drawing_ID'];}else{$did='';}
if(isSet($_POST['mqty'])){$mqty=$_POST['mqty'];}else{$mqty='';}
if(isSet($_POST['mcode'])){$mcode=$_POST['mcode'];}else{$mcode='';}
if(isSet($_POST['miqid'])){$miqid=$_POST['miqid'];}else{$miqid='';}

$query="UPDATE MI_Challans SET 
			EX_Challan_NO='$cno',EX_Challan_Date='$cdate',
			GP_NO='$gpno',GP_Date='$gpdate',
			DA_NO='$dano',DA_Date='$dadate',
			Purchase_Ref='$pref',Purchase_Ref_Date='$prdate' 
			WHERE MI_Ch_ID='$miid';";

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
		$resd=mysql_query($qd) or die(mysql_error());
		
	}
	
	
}




?>