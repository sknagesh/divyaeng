<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//$drawingid=$_POST['drawid'];
$mid=$_POST['Inward_ID'];
$qty=$_POST['Batch_Qty'];

$query="SELECT Material_Qty FROM Material_Inward WHERE Material_Inward_ID='$mid';";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);

$inqty=$row['Material_Qty'];

$queryb="SELECT Batch_Qty From Batch_NO WHERE Material_Inward_ID='$mid';";

$res = mysql_query($queryb, $cxn) or die(mysql_error($cxn));
$r=mysql_num_rows($res);
$bqty=0;
if($r!=0)
{
while($rowb = mysql_fetch_assoc($res))
{
	$bqty+=$rowb['Batch_Qty'];
	
}
}
$maxbqty=$inqty-$bqty;

if($maxbqty>=$qty){print("true");}else{print("false");}

?>