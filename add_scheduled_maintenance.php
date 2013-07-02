<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
print_r($_POST);


$machineid=$_POST['Machine_ID'];
$interval=$_POST['interval'];
$spmdesc=$_POST['spmdesc'];
$mdesch=explode('<|>',$_POST['mdesch']);


$query="INSERT INTO Scheduled_PM (Machine_ID,
								SPM_Interval,
								SPM_Title) 
	 						VALUES('$machineid','$interval','$spmdesc');";


$res=mysql_query($query) or die(mysql_error());

$pmid=mysql_insert_id();

print($query);


for($i=0;$i<count($mdesch);$i++)
{

if($mdesch[$i]!='')
{
$q="INSERT INTO SPM_Desc (SPM_ID,SPM_Desc) VALUES('$pmid','$mdesch[$i]');";
print("<br>$q");
$res=mysql_query($q) or die(mysql_error());
}

}

?>