<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$modq=$_POST['Mo_Drg_Qty_ID'];
$grno=$_POST['grno'];
$grdatedb=$_POST['grdatedb'];




$query="INSERT INTO GR_Nos (MO_Drg_Qty_ID,
								GR_NO,
								GR_Date) 
	 						VALUES('$modq',
									'$grno',
									'$grdatedb');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{

	
print("Added new Gr No $grno Dated $grdatedb");	
	
}else
	{
		print("Error Adding GR No");
	}


?>