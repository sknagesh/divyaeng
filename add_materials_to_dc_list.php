<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$dqty=$_POST['dqty'];
$remarks=$_POST['remark'];
$midrgid=$_POST['midrgid'];
$add='';
$tqty=0;
for($i=0;$i<count($midrgid);$i++)
{
if($dqty[$i]!='')
{
$add.=$midrgid[$i].'<|>'.$dqty[$i].'<|>'.$remarks.'<||>';	
$tqty+=$dqty[$i];	
}


}
$add.='@'.$tqty;
print($add);

?>