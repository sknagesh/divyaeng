<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$ddesc=$_POST['ddesc'];


$query="INSERT INTO Dimn_Desc (Dimn_Desc) ";
$query.="VALUES('$ddesc');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added New Description $ddesc");	
	
}else
	{
		print("Error Adding Description");
	}


?>