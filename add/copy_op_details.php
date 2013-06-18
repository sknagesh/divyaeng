<?php
include('dewdb.inc');
$cxns = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('ShopLog',$cxns) or die("error opening db: ".mysql_error());


$sdid=$_GET['sdid'];
$ddid=$_GET['ddid'];
//print_r($_POST);
$query="SELECT * FROM Operation WHERE Drawing_ID='$sdid';";
//print($query);
$resa = mysql_query($query, $cxns) or die(mysql_error($cxns));
$sf="Source Operations<br>";
$i=0;
while($row = mysql_fetch_assoc($resa)){
	$sf.='<input type="checkbox" id=sf['.$i.'] name="sf['.$i.']">';
	$sf.='<input type="hidden" id=soid['.$i.'] name="soid['.$i.']" value="'.$row['Operation_ID'].'">';
	$sf.=$row['Operation_Desc'].'<br>';
$i++;
}



$cxnd = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxnd) or die("error opening db: ".mysql_error());

$qd="SELECT * FROM Operation WHERE Drawing_ID='$ddid';";
$resd = mysql_query($qd, $cxnd) or die(mysql_error($cxnd));

$sf.="Destination Operations<br>";
while($rowd = mysql_fetch_assoc($resd)){
	$sf.='<br>'.$rowd['Operation_Desc'];
}


print($sf);

?>