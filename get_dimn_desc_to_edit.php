<?php
include('dewdb.inc');
if(isSet($_GET['cid'])){$cid=$_GET['cid'];}else{$cid='';}
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$dcid=$_GET['dcid'];
//print_r($_POST);
$query="SELECT * FROM Dimn_Desc WHERE Desc_ID='$dcid';";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);

$detaildesc=$row['Detailed_Desc'];
$basicdesc=$row['Dimn_Desc'];

$query2="SELECT * FROM Dimn_Comment WHERE Desc_ID='$dcid';";
$resa2 = mysql_query($query2, $cxn) or die(mysql_error($cxn));
$dcomm="";
$i=0;
while($row2 = mysql_fetch_assoc($resa2))
{
	$dcomm.='<input type="hidden" id=cid['.$i.'] name=cid['.$i.'] value="'.$row2['Comment_ID'].'">
	<input type="text" id="com['.$i.']" name=com['.$i.'] value="'.$row2['Comment'].'">';
	$i++;
}

$data=$basicdesc.'<|>'.$detaildesc.'<|>'.$dcomm;

print($data);


?>