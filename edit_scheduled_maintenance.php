<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
//print_r($_POST);

$pmid=$_POST['SPM_ID'];
$interval=$_POST['interval'];
$spmdesc=$_POST['spmdesc'];
$spmtol=$_POST['spmtol'];
if(isSet($_POST['mdesch'])){$mdesch=explode('<|>',$_POST['mdesch']);}else{$mdesch='';}
if(isSet($_POST['spmid'])){$spmid=$_POST['spmid'];}else{$spmid='';}
if(isSet($_POST['mdescedit'])){$mdescedit=$_POST['mdescedit'];}else{$mdescedit='';}
if(isSet($_POST['delspmd'])){$delspmd=$_POST['delspmd'];}else{$delspmd='';}




$query="UPDATE Scheduled_PM SET SPM_Interval='$interval',
								SPM_Title='$spmdesc',
								SPM_Tol='$spmtol' WHERE SPM_ID='$pmid';";
	 						

//print($query);
$res=mysql_query($query) or die(mysql_error());

if($mdesch!='')   //add new maintenance activities
{

for($i=0;$i<count($mdesch);$i++)
{

		if($mdesch[$i]!='')
		{
		$q="INSERT INTO SPM_Desc (SPM_ID,SPM_Desc) VALUES('$pmid','$mdesch[$i]');";
//		print("<br>$q");
		$res=mysql_query($q) or die(mysql_error());
		}
}

}



if($mdescedit!='')   //update maintenance activities
{

for($i=0;$i<count($spmid);$i++)
{

$q="UPDATE SPM_Desc SET SPM_Desc='".$mdescedit[$i]."' WHERE SPM_Desc_ID ='".$spmid[$i]."';";
//print("<br>$q");
$res=mysql_query($q) or die(mysql_error());

}

}



if($delspmd!='')   //delete selected descriptions
{
$delspmd=array_values($delspmd);
for($i=0;$i<count($delspmd);$i++)
{

$q="DELETE FROM SPM_Desc WHERE SPM_Desc_ID=$delspmd[$i];";
//print("<br>$q");
$res=mysql_query($q) or die(mysql_error());

}

}

?>