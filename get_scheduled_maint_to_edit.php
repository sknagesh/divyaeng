<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$spmid=$_GET['spmid'];

$q="SELECT * FROM Scheduled_PM WHERE SPM_ID='$spmid';";

$resm=mysql_query($q) or die(mysql_error());

$rowm=mysql_fetch_assoc($resm);
$data=$rowm['SPM_Interval'].'<|>'.$rowm['SPM_Title'].'<|>'.$rowm['SPM_Tol'];


$qs="SELECT * FROM SPM_Desc WHERE SPM_ID='$spmid';";
$res=mysql_query($qs) or die(mysql_error());
$i=0;
while($row=mysql_fetch_assoc($res))
	{

$data.='<|><br><input type="checkbox" name="delspmd['.$i.']" value="'.$row['SPM_Desc_ID'].'">
				<input type="hidden" name="spmid['.$i.']" value="'.$row['SPM_Desc_ID'].'">
				<input type="text" name="mdescedit['.$i.']" value="'.$row['SPM_Desc'].'"><br>';
$i++;
	}
	
print($data);
?>