<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/enquiry/';
//print_r($_POST);
//print_r($_FILES);
$cname=$_POST['cname'];
$edatedb=$_POST['edatedb'];
$rdatedb=$_POST['rdatedb'];
if(isSet($_POST['eref'])){$eref=$_POST['eref'];}else{$eref="";}
$drawingno=$_POST['drawingno'];
$componentname=$_POST['componentname'];
if(isSet($_POST['mspec'])){$mspec=$_POST['mspec'];}else{$mspec="";}
if(isSet($_POST['msource'])){$msource=$_POST['msource'];}else{$msource="";}



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

$query="INSERT INTO Enquiry_Record (Customer_Name,
									Enquiry_Date,
									Enquiry_Ref,
									Drawing_NO,
									Required_Date,
									Remarks,
									Component_Name,
									Component_Material,
									Material_Source,
								Visual_Ref) ";
$query.="VALUES('$cname',
				'$edatedb',
				'$eref',
				'$drawingno',
				'$rdatedb',
				'$remarks',
				'$componentname',
				'$mspec',
				'$msource',
				'$drgfileName');";

print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added new Enquiry for $componentname - $drawingno");	
	
}else
	{
		print("Error Adding");
	}


?>