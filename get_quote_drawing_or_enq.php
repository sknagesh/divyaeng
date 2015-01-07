<?php
include('dewdb.inc');

$uploadDir = '/home/www/enquiry/';


$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$t=$_GET['type'];
if(isSet($_GET['id'])){$id=$_GET['id'];}else{$id='';}



if($t==1)
{
	$q2="SELECT *,DATE_FORMAT(Quote_Date,'%d/%m/%Y') as d FROM Quote WHERE Enquiry_ID='$id';";
	//print("query=$q2");
	$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$res2=mysql_fetch_assoc($r2);
if(mysql_num_rows($r2))
{
    $qdate=$res2['d'];
    $qnotes=$res2['Quote_Notes'];
    $eau=$res2['EAU'];
	$bsize=$res2['Batch_Size'];
	$nos=$res2['No_Of_Settings'];
	$stime=$res2['Total_Batch_Setting_Time'];
	$actime=$res2['Actual_Cutting_Time'];
	$effy=$res2['Efficiency'];
	$hwork=$res2['Hand_Work'];
	$holes=$res2['Holes'];
	$packing=$res2['Packing'];
	$transport=$res2['Transportation'];
	$cscrap=$res2['Cost_Of_Scrap'];
	$path=$res2['Quote_Image_Path'];
}
}else if($t==2)
{
	$q2="SELECT *,DATE_FORMAT(Quote_Date,'%d/%m/%Y') as d FROM Quote WHERE Drawing_ID='$id';";
	$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$res2=mysql_fetch_assoc($r2);
if(mysql_num_rows($r2))
{
    $qdate=$res2['d'];
    $qnotes=$res2['Quote_Notes'];
    $eau=$res2['EAU'];
	$bsize=$res2['Batch_Size'];
	$nos=$res2['No_Of_Settings'];
	$stime=$res2['Total_Batch_Setting_Time'];
	$actime=$res2['Actual_Cutting_Time'];
	$effy=$res2['Efficiency'];
	$hwork=$res2['Hand_Work'];
	$holes=$res2['Holes'];
	$packing=$res2['Packing'];
	$transport=$res2['Transportation'];
	$cscrap=$res2['Cost_Of_Scrap'];
	$path=$res2['Quote_Image_Path'];
}
}else{
		$qdate='';

}




if($qdate!='')
{
////    0        1              2           3           4           5          6            7             8           9            10            11               12               13           14
$data="yes<|>".$qdate."<|>".$qnotes."<|>".$eau."<|>".$bsize."<|>".$nos."<|>".$stime."<|>".$actime."<|>".$effy."<|>".$hwork."<|>".$holes."<|>".$packing."<|>".$transport."<|>".$cscrap."<|>".$path;
}else{
	$data="no<|>";
}

	print($data);

	


?>