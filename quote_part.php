<?php
include('dewdb.inc');
require_once('../tcpdf/tcpdf.php');

$uploadDir = '/home/www/enquiry/';
//print_r($_POST);
print("Saving quote to database");
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

if(isSet($_POST['Drawing_ID'])){$drawingid=$_POST['Drawing_ID'];}else{$drawingid='';}
if(isSet($_POST['Enquiry_ID'])){$enquiryid=$_POST['Enquiry_ID'];}else{$enquiryid='';}
$cdatedb=$_POST['cdatedb'];
$cdate=$_POST['cdate'];
$pdesc=$_POST['pdesc'];
if(isSet($_POST['eau'])){$eau=$_POST['eau'];}else{$eau='';}
if(isSet($_POST['bsize'])){$batchsize=$_POST['bsize'];}else{$batchsize='1';}
if(isSet($_POST['nos'])){$noofsettings=$_POST['nos'];}else{$noofsettings='';}
if(isSet($_POST['stime'])){$totalbatchsettingtime=$_POST['stime'];}else{$totalbatchsettingtime='';}
if(isSet($_POST['actime'])){$actualcuttingtime=$_POST['actime'];}else{$actualcuttingtime='';}
if(isSet($_POST['effy'])){$efficiency=$_POST['effy'];}else{$efficiency='';}
if(isSet($_POST['hwork'])){$handwork=$_POST['hwork'];}else{$handwork='';}
if(isSet($_POST['holes'])){$holes=$_POST['holes'];}else{$holes='';}
if(isSet($_POST['packing'])){$packing=$_POST['packing'];}else{$packing='';}
if(isSet($_POST['transport'])){$transportation=$_POST['transport'];}else{$transportation='';}
if(isSet($_POST['cscrap'])){$costofscrap=$_POST['cscrap'];}else{$costofscrap='';}

if($enquiryid!='')
{
	$q2="SELECT * FROM Enquiry_Record WHERE Enquiry_ID='$enquiryid';";
	$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$res2=mysql_fetch_assoc($r2);
	$dno=$res2['Drawing_NO'];
	$compname=$res2['Component_Name'];

}else
if($drawingid!='')
{
	$q2="SELECT *,Customer_Name FROM Component as comp 
	INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID WHERE Drawing_ID='$drawingid';";
	$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));
	$res2=mysql_fetch_assoc($r2);
	$dno=$res2['Drawing_NO'];
	$compname=$res2['Component_Name'];
}

if((isSet($_FILES['drg']['name']))&&$_FILES['drg']['name']!='')
{
	$drgfileName = $cdatedb.$drawingno.$enquiryid."-".$_FILES['drg']['name'];
	$drgtmpName = $_FILES['drg']['tmp_name'];
	$drgfileSize = $_FILES['drg']['size'];
	$drgfileType = $_FILES['drg']['type'];
	$drgfilePath = $uploadDir . $drgfileName;
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	chmod($drgfilePath, 777);
	if (!$result) {
						echo "<br>Error uploading Drawing $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$drgfileName = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}

}else{$drgfileName='';}




$q1="INSERT INTO Quote (Drawing_ID,
						Enquiry_ID,
						Quote_Date,
						Quote_Notes,
						EAU,
						Batch_Size,
						No_Of_Settings,
						Total_Batch_Setting_Time,
						Actual_Cutting_Time,
						Efficiency,
						Hand_Work,
						Holes,
						Packing,
						Transportation,
						Cost_Of_Scrap,
						Quote_Image_Path)
				VALUES('$drawingid',
				       '$enquiryid',
					   '$cdatedb',
					   '$pdesc',
					   '$eau',
					   '$batchsize',
					   '$noofsettings',
					   '$totalbatchsettingtime',
					   '$actualcuttingtime',
					   '$efficiency',
					   '$handwork',
					   '$holes',
					   '$packing',
					   '$transportation',
					   '$costofscrap',
					   '$drgfileName');";

$r1 = mysql_query($q1, $cxn) or die(mysql_error($cxn));
/*
		$ppath='/enquiry/'.$pdfname;
	print("<a class=\"pdf\" href=\"$ppath\" target=\"_NEW\" title=\"Opens PDF in a new TAB\">Clarification Request  ///out put file name is $pdfsname</a>");
*/

//	print($q1);
?>