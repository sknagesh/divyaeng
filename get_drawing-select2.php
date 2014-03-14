<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//$custid=$_GET['custid'];
if(isSet($_POST['searchTerm'])){$dn=$_POST['searchTerm'];}else{$dn="";}
//print_r($_POST);
if($dn!='')
{

$query="SELECT * FROM Component WHERE Drawing_NO LIKE '%$dn%'";	
}else{
	$query="SELECT * FROM Component;";	
}


$jsonArray = array();
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$nr=mysql_num_rows($resa);
if($nr!='')
{
while ($row = mysql_fetch_assoc($resa))
{

 $buildjson = array('id' => "$row[Drawing_ID]",'text' => "$row[Drawing_NO] - $row[Component_Name]");

 array_push($jsonArray, $buildjson);
}

echo json_encode($jsonArray);
}


?>