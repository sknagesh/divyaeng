<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$Drawing_ID=$_POST['Drawing_ID'];
$Batch_Qty=$_POST['Batch_Qty'];
$miid=$_POST['Inward_ID'];
	if(isset($_POST['Batch_Desc']))
	{
	$Batch_Desc=$_POST['Batch_Desc'];	
	} else{$Batch_Desc=$miid.'-'.$Drawing_ID."-".date("Y")."-".date("m")."-".date("d")."-".$Batch_Qty;}

if(isSet($_POST['hcode'])){$hcode=$_POST['hcode'];}else{$hcode='';}

$query="INSERT INTO Batch_NO ";
$query.="(Material_Inward_ID, Mfg_Batch_NO,Batch_Qty,Batch_Under_Progress,Heat_Code) ";
$query.=" VALUES('$miid','$Batch_Desc','$Batch_Qty',1,'$Heat_Code');";
//print("<br>Query is '$query'");
$result = mysql_query($query, $cxn) or die(mysql_error($cxn));


$bid=mysql_insert_id();

print("New Batch Created with ID $bid and Batch No $Batch_Desc");


?>