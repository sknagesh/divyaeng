<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$dqty=$_POST['dqty'];
$remarks=$_POST['remark'];
$miid=$_POST['miid'];
$add='';
for($i=0;$i<count($miid);$i++)
{
		if($dqty[$i]!='')
		{
		$add.=$miid[$i].'<|>'.$dqty[$i].'<|>'.$remarks[$i].'<||>';		
			
		}
	
	
}
print($add);

?>