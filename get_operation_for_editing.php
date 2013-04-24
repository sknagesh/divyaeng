<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
$opid=$_GET['opid'];

$query="SELECT Drawing_ID,Program_NO,Operation_Desc,NC_Prog_Path,Operation_Notes,Clamping_Time,Machining_Time,
		(Select GROUP_CONCAT(Fixture_NO) FROM Ope_Fixt_Map WHERE Operation_ID='$opid')as fno,
		(Select GROUP_CONCAT(CONCAT(OP_Image_ID, ',' ,Operation_Image_Path)) FROM Operation_Image WHERE Operation_ID='$opid')as oip,
		Stage_Drawing_Path FROM Operation WHERE Operation_ID='$opid';";


$res=mysql_query($query) or die(mysql_error());

$n=mysql_affected_rows();
$row=mysql_fetch_assoc($res);
if($n!=0)
{

$drawid=$row['Drawing_ID'];
$opedesc=$row['Operation_Desc'];
$ctime=hms2mins($row['Clamping_Time']);
$mtime=hms2mins($row['Machining_Time']);
$fixtno=$row['fno'];
$progno=$row['Program_NO'];
$ppath=$row['NC_Prog_Path'];
$onote=$row['Operation_Notes'];
$sdpath=$row['Stage_Drawing_Path'];
$ippath=$row['oip'];

$data=$opedesc.'<|>'.$ctime.'<|>'.$mtime.'<|>'.$progno.'<|>'.$ppath.'<|>'.$onote.'<|>'.$fixtno.'<|>'.$ippath.'<|>'.$sdpath;	
}else{
	$data='';
}


print($data);
?>