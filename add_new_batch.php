<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$Drawing_ID=$_POST['Drawing_ID'];
$miid=$_POST['MI_Drg_Qty_ID'];
$mqty=$_POST['mqty'];
$hcode=$_POST['hcode'];
if(isSet($_POST['Batch_Remarks'])){$batchremarks=$_POST['Batch_Remarks'];}else{$batchremarks='';}
if(isSet($_POST['cdatedb'])){$cdatedb=$_POST['cdatedb'];}else{$cdatedb='';}
$batchqty=0;
for($j=0;$j<count($mqty);$j++)
{
	$batchqty+=$mqty[$j];
	
}

	if(isset($_POST['Batch_Desc']))
	{
	$Batch_Desc=$_POST['Batch_Desc'];	
	} else{$Batch_Desc=$Drawing_ID."-".date("Y")."-".date("m")."-".date("d")."-".$batchqty;}

$query="INSERT INTO Batch_NO (Mfg_Batch_NO,Batch_Remarks,Commited_Date) VALUES('$Batch_Desc','$batchremarks','$cdatedb');";

//print("<br>Query is '$query'");
$result = mysql_query($query, $cxn) or die(mysql_error($cxn));

$bid=mysql_insert_id();



for($i=0;$i<count($miid);$i++)
{
	if($mqty[$i]!='')
	{

$qbn="INSERT INTO BNo_MI_Challans (Batch_ID,MI_Drg_Qty_ID,Qty_In_Batch,Heat_Code) 
		VALUES('$bid','$miid[$i]','$mqty[$i]','$hcode[$i]');";
print("$qbn");
$res = mysql_query($qbn, $cxn) or die(mysql_error($cxn));
	}

}

print("New Batch Created with ID $bid and Batch No $Batch_Desc");

?>