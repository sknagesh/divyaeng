<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$bid=$_POST['Batch_ID'];
if(isSet($_POST['hcode'])){$hcode=$_POST['hcode'];}else{$hcode='';}
if(isSet($_POST['bncid'])){$bncid=$_POST['bncid'];}else{$bncid='';}
if(isSet($_POST['bremark'])){$bremark=$_POST['bremark'];}else{$bremark='';}
if(isSet($_POST['cdatedb'])){$cdatedb=$_POST['cdatedb'];}else{$cdatedb='';}

$query="UPDATE Batch_NO SET Batch_Remarks='$bremark',Commited_Date='$cdatedb' WHERE Batch_ID='$bid';";

//print("<br>Query is '$query'");
$result = mysql_query($query, $cxn) or die(mysql_error($cxn));

///update get batch details to edit with array of elements

for($j=0;$j<count($bncid);$j++)
{

$qbn="UPDATE BNo_MI_Challans SET Heat_Code='$hcode[$j]' WHERE BNo_MI_Challan_ID='$bncid[$j]';";
//print("$qbn");
$res = mysql_query($qbn, $cxn) or die(mysql_error($cxn));
}



?>
