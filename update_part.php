<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
//print_r($_POST);
//print_r($_FILES);
$drgid=$_POST['Drawing_ID'];
$custid=$_POST['Customer_ID'];
$drawingno=$_POST['drawingno'];
if(isSet($_POST['revno'])){$revno=$_POST['revno'];}else{$revno="";}
$componentname=$_POST['componentname'];
if(isSet($_POST['mspec'])){$mspec=$_POST['mspec'];}else{$mspec="";}
if(isSet($_POST['cblank'])){$cblank=$_POST['cblank'];}else{$cblank="";}
if(isSet($_POST['pmblank'])){$pmblank=$_POST['pmblank'];}else{$pmblank="";}
if(isSet($_POST['fsize'])){$fsize=$_POST['fsize'];}else{$fsize="";}
if(isSet($_POST['sweight'])){$sweight=$_POST['sweight'];}else{$sweight="";}


if((isSet($_FILES['drg']['name']))&&$_FILES['drg']['name']!='')
{
	$drgfileName = $drawingno."-".$_FILES['drg']['name'];
	$drgtmpName = $_FILES['drg']['tmp_name'];
	$drgfileSize = $_FILES['drg']['size'];
	$drgfileType = $_FILES['drg']['type'];
	$drgfilePath = $uploadDir . $drgfileName;
	 if(file_exists($drgfilePath))
                {
						print("deleting old file...<br>");
                        unlink($drgfilePath);
                }
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

 if(file_exists($profilePath))
                {
                        print("Deleting old Process sheet...<br>");
                        unlink($profilePath);
                }


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

 if(file_exists($prefilePath))
                {
                		print("Deleting old preview image...<br>");
                        unlink($prefilePath);
                }


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






$query="UPDATE Component SET 	Drawing_NO='$drawingno',
								Drawing_Rev='$revno',
								Component_Name='$componentname',
								Component_Material='$mspec',
								Raw_Material_Size='$cblank',
								Pre_Machined_Blank_Size='$pmblank',
								Finish_Size='$fsize',
								Customer_Drawing='$drgfileName',
								Process_Sheet='$profileName',
								Preview_Image='$prefileName',
								Scrap_Weight='$sweight' WHERE Drawing_ID='$drgid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("<br>Updated Component $componentname - $drawingno");	
	
}else
	{
		print("<br>Error Updating");
	}





?>