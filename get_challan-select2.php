<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//$custid=$_GET['custid'];
if(isSet($_POST['searchTerm'])){$dn=$_POST['searchTerm'];}else{$dn="";}
//print_r($_POST);
if($dn!='')
{
$query="SELECT mi.Material_Inward_ID,EX_Challan_NO,DATE_FORMAT(EX_Challan_Date,'%d/%m/%Y') as ecd,
				DA_NO,DATE_FORMAT(DA_Date,'%d/%m/%Y') as dad,GP_NO,DATE_FORMAT(GP_Date,'%d/%m/%Y') as gpd,
				Purchase_Ref,DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prd FROM 
				Material_Inward as mi WHERE EX_Challan_NO like('%$dn%');";

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
if($row['EX_Challan_NO']!=''){$desc=$row['EX_Challan_NO'].' - '.$row['ecd'];}else{$desc=$row['Purchase_Ref'].' - '.$row['prd'];}
 $buildjson = array('id' => "$row[Material_Inward_ID]",'text' => "$desc");

 array_push($jsonArray, $buildjson);
}

echo json_encode($jsonArray);
}


?>