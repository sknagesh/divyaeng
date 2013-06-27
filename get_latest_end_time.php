<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$mid=$_GET['mid'];
//print($mid);


$query="SELECT DATE_FORMAT(End_Date_Time,'%d-%m-%Y %h:%i%p')as edt,End_Date_Time FROM ActivityLog WHERE Machine_ID='$mid' AND Activity_ID NOT IN(10,15) ORDER BY End_Date_Time DESC LIMIT 1;";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$r=mysql_affected_rows();
if($r!=0)
{
$row = mysql_fetch_assoc($resa);	
$data=$row['edt'].'<>'.$row['End_Date_Time'];
		
}else{
	
	 $edt = date('d-m-Y h:i A');
	 $EDT=date('Y-m-d H:i');
	 $data=$edt.'<>'.$EDT;
	 
}

print($data);





?>