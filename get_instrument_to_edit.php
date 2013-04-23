<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$iid=$_GET['iid'];

$query="SELECT Instrument_ID, Instrument_SLNO, Instrument_Description,Calibration_Date,Next_Calibration_Date,
		DATE_FORMAT(Calibration_Date,'%d-%m-%Y') as cdt,DATE_FORMAT(Next_Calibration_Date,'%d-%m-%Y') as ndt FROM Instrument WHERE Instrument_ID=$iid;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
$row=mysql_fetch_assoc($res);
if($r!=0)
{
$islno=$row['Instrument_SLNO'];
$idesc=$row['Instrument_Description'];
$cdate=$row['Calibration_Date'];
$cdatedb=$row['cdt'];
$ncdate=$row['Next_Calibration_Date'];
$ncdatedb=$row['ndt'];

$data=$islno.'<|>'.$idesc.'<|>'.$cdate.'<|>'.$cdatedb.'<|>'.$ncdate.'<|>'.$ncdatedb;
}else{
	$data='';
}

print($data);

?>