<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$miid=$_GET['cid'];

$query="SELECT Material_Inward_ID,EX_Challan_NO,DATE_FORMAT(EX_Challan_Date,'%d-%m-%Y') as ecd,
				DA_NO,DATE_FORMAT(DA_Date,'%d-%m-%Y') as dad,GP_NO,DATE_FORMAT(GP_Date,'%d-%m-%Y') as gpd,
				Purchase_Ref,DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prd,MI_Ch_ID,EX_Challan_Date,
				DA_Date,GP_Date,Purchase_Ref_Date FROM 
				MI_Challans WHERE Mi_Ch_ID='$miid';";


$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($res);

//print($query);



if($row['ecd']!='00/00/0000'){$ecd=$row['ecd'];}else{$ecd='';}
if($row['dad']!='00/00/0000'){$dad=$row['dad'];}else{$dad='';}
if($row['gpd']!='00/00/0000'){$gpd=$row['gpd'];}else{$gpd='';}
if($row['prd']!='00/00/0000'){$prd=$row['prd'];}else{$prd='';}

if($row['Purchase_Ref']=='')
{
$st='1<>'.$row['Material_Inward_ID'].'<>'.$row['EX_Challan_NO'].'<>'.$ecd.'<>'.$row['DA_NO'].'<>'.$dad.
		'<>'.$row['GP_NO'].'<>'.$gpd.'<>'.$row['EX_Challan_Date'].'<>'.$row[DA_Date].'<>'.$row['GP_Date'];
}else
{
	$st='2<>'.$row['Material_Inward_ID'].'<>'.$row['Purchase_Ref'].'<>'.$prd.'<>'.$row['Purchase_Ref_Date'];
}
print($st);



?>