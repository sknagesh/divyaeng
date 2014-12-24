<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
//print_r($_POST);
//print_r($_FILES);
$custid=$_POST['Customer_ID'];
$drawingno=$_POST['drawingno'];
if(isSet($_POST['revno'])){$revno=$_POST['revno'];}else{$revno="";}
$componentname=$_POST['componentname'];
if(isSet($_POST['mspec'])){$mspec=$_POST['mspec'];}else{$mspec="";}
if(isSet($_POST['cblank'])){$cblank=$_POST['cblank'];}else{$cblank="";}
if(isSet($_POST['pmblank'])){$pmblank=$_POST['pmblank'];}else{$pmblank="";}
if(isSet($_POST['fsize'])){$fsize=$_POST['fsize'];}else{$fsize="";}
if(isSet($_POST['sweight'])){$sweight=$_POST['sweight'];}else{$sweight="";}
if(isSet($_POST['qhours'])){$qhours=$_POST['qhours'];}else{$qhours="";}
if(isSet($_POST['custdrawingno'])){$custdrawingno=$_POST['custdrawingno'];}else{$custdrawingno="";}
if(isSet($_POST['mcode'])){$mcode=$_POST['mcode'];}else{$mcode="";}


if((isSet($_FILES['drg']['name']))&&$_FILES['drg']['name']!='')
{
	$drgfileName = $drawingno."-".$_FILES['drg']['name'];
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

if((isSet($_FILES['process']['name']))&&($_FILES['process']['name']!=''))
{
	$profileName = $drawingno."-".$_FILES['process']['name'];
	$protmpName = $_FILES['process']['tmp_name'];
	$profileSize = $_FILES['process']['size'];
	$profileType = $_FILES['process']['type'];
	$profilePath = $uploadDir . $profileName;
	$result = move_uploaded_file($protmpName, $profilePath);
		chmod($profilePath, 777);
	if (!$result) {
						echo "<br>Error uploading Process sheet $profileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$profileName = addslashes($profileName);
						$profilePath = addslashes($profilePath);
						}

}else{$profileName='';}


if((isSet($_FILES['preview']['name']))&&($_FILES['preview']['name']!=''))
{
	$prefileName = $drawingno."-".$_FILES['preview']['name'];
	$pretmpName = $_FILES['preview']['tmp_name'];
	$prefileSize = $_FILES['preview']['size'];
	$prefileType = $_FILES['preview']['type'];
	$prefilePath = $uploadDir . $prefileName;
	$result = move_uploaded_file($pretmpName, $prefilePath);
		chmod($prefilePath, 0777);
	if (!$result) {
						echo "<br>Error uploading preview image $prefileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$prefileName = addslashes($prefileName);
						$prefilePath = addslashes($prefilePath);
						}

}else{$prefileName='';}






$query="INSERT INTO Component (Customer_ID,
								Drawing_NO,
								Cust_Drawing_NO,
								Drawing_Rev,
								Component_Name,
								Component_Material,
								Material_Code,
								Raw_Material_Size,
								Pre_Machined_Blank_Size,
								Finish_Size,
								Customer_Drawing,
								Process_Sheet,
								Preview_Image,
								Scrap_Weight,
								Quote_Hours) ";
$query.="VALUES('$custid',
				'$drawingno',
				'$custdrawingno',
				'$revno',
				'$componentname',
				'$mspec',
				'$mcode',
				'$cblank',
				'$pmblank',
				'$fsize',
				'$drgfileName',
				'$profileName',
				'$prefileName',
				'$sweight',
				'$qhours');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added new Component $componentname - $drawingno");	
	
}else
	{
		print("Error Adding");
	}


?>