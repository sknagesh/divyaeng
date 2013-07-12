<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$iid=$_GET['iid'];

$query="SELECT Instrument_ID, Instrument_SLNO, Instrument_Description,Calibration_Date,Make,Least_Count,Acceptable_Error,Instrument_Range,
		Remarks,DATE_FORMAT(Calibration_Date,'%d-%m-%Y') as cdt,Calibration_Frequency FROM Instrument WHERE Instrument_ID=$iid;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
$row=mysql_fetch_assoc($res);
if($r!=0)
{
$islno=$row['Instrument_SLNO'];
$idesc=$row['Instrument_Description'];
$cdate=$row['cdt'];
$cdatedb=$row['Calibration_Date'];
$calfreq=$row['Calibration_Frequency'];
$make=$row['Make'];
$lc=$row['Least_Count'];
$range=$row['Instrument_Range'];
$aerror=$row['Acceptable_Error'];

$data=$islno.'<|>'.$idesc.'<|>'.$cdate.'<|>'.$cdatedb.'<|>'.$calfreq.'<|>'.$make.'<|>'.$lc.'<|>'.$range.'<|>'.$aerror.'<|>'.$remark;
}else{
	$data='';
}

print($data);

?>