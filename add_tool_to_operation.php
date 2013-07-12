<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
//print_r($_POST);
//print_r($_FILES);
$opid=$_POST['Operation_ID'];
if(isSet($_POST['Insert_ID1'])){$insertid1=$_POST['Insert_ID1'];}else{$insertid1='';}
if(isSet($_POST['Insert_ID2'])){$insertid2=$_POST['Insert_ID2'];}else{$insertid2='';}
if(isSet($_POST['Tool_ID_2'])){$toolid2=$_POST['Tool_ID_2'];}else{$toolid2='';}
$toolid1=$_POST['Tool_ID_1'];
$holderid=$_POST['Holder_ID_1'];
if(isSet($_POST['Holder_ID_2'])){$holderid2=$_POST['Holder_ID_2'];}else{$holderid2='';}
$mdesc=$_POST['tdesc'];
if(isSet($_POST['toh'])){$toh=$_POST['toh'];}else{$toh='';}
if(isSet($_POST['tlife'])){$tlife=$_POST['tlife'];}else{$tlife='';}
if(isSet($_POST['tsl'])){$tsl=$_POST['tsl'];}else{$tsl='';}


if((isSet($_FILES['timg']['name']))&&($_FILES['timg']['name']!=''))
{
	$odrgfileName = $opid."-".$_FILES['timg']['name'];
	$odrgtmpName = $_FILES['timg']['tmp_name'];
	$odrgfileSize = $_FILES['timg']['size'];
	$odrgfileType = $_FILES['timg']['type'];
	$odrgfilePath = $uploadDir . $odrgfileName;
	$oresult = move_uploaded_file($odrgtmpName, $odrgfilePath);
	if (!$oresult) {
						echo "<br>Error uploading Tool Image $odrgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$odrgfileName = addslashes($odrgfileName);
						$odrgfilePath = addslashes($odrgfilePath);
						}

}else{$odrgfileName='';}





$query="INSERT INTO Ope_Tool (
				Operation_ID,
				Tool_ID_1,
				Insert_ID_1,
				Tool_ID_2,
				Insert_ID_2,
				Holder_ID_1,
				Holder_ID_2,
				Ope_Tool_Desc,
				Ope_Tool_OH,
				Ope_Tool_Life,
				Storage_Location,
				Ope_Tool_Image_Path)
				 
		VALUES('$opid',
				'$toolid1',
				'$insertid1',
				'$toolid2',
				'$insertid2',
				'$holderid',
				'$holderid2',
				'$mdesc',
				'$toh',
				'$tlife',
				'$tsl',
				'$odrgfileName');";

//			print($query);

			$res=mysql_query($query) or die(mysql_error());

			$result=mysql_affected_rows();
			if($result!=0)
				{
				print("One tool added to This Operation");

					
	
				}


?>