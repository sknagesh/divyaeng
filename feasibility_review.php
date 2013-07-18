<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/enquiry/';
print_r($_POST);
//print_r($_FILES);

$enquiryid=$_POST['Enquiry_ID'];
if(isSet($_POST['scomps'])){$scomps=$_POST['scomps'];}else{$scomps='';}
if(isSet($_POST['requirementclear'])){$requirementclear=$_POST['requirementclear'];}else{$requirementclear='';}
if(isSet($_POST['clarification'])){$clarification=$_POST['clarification'];}else{$clarification='';}
if(isSet($_POST['tfeasible'])){$tfeasible=$_POST['tfeasible'];}else{$tfeasible='';}
if(isSet($_POST['tol'])){$tol=$_POST['tol'];}else{$tol='';}
if(isSet($_POST['fir'])){$fir=$_POST['fir'];}else{$fir='';}
if(isSet($_POST['docu'])){$docu=$_POST['docu'];}else{$docu='';}
if(isSet($_POST['volume'])){$volume=$_POST['volume'];}else{$volume='';}
if(isSet($_POST['taxes'])){$taxes=$_POST['taxes'];}else{$taxes='';}
if(isSet($_POST['packing'])){$packing=$_POST['packing'];}else{$packing='';}
if(isSet($_POST['tmode'])){$tmode=$_POST['tmode'];}else{$tmode='';}
if(isSet($_POST['freight'])){$freight=$_POST['freight'];}else{$freight='';}
if(isSet($_POST['pterms'])){$pterms=$_POST['pterms'];}else{$pterms='';}
if(isSet($_POST['mcapa'])){$mcapa=$_POST['mcapa'];}else{$mcapa='';}
if(isSet($_POST['tfireq'])){$tfireq=$_POST['tfireq'];}else{$tfireq='';}
if(isSet($_POST['scont'])){$scont=$_POST['scont'];}else{$scont='';}
if(isSet($_POST['ef'])){$ef=$_POST['ef'];}else{$ef='';}
if(isSet($_POST['remarks'])){$remarks=$_POST['remarks'];}else{$remarks='';}


$query="INSERT INTO Feasibility_Review (Enquiry_ID,
									Similar_Product_Details,
									Drawing_Clarity,
									Clarifications_Required,
									Technically_Feasible,
									Specific_FIR,
									Documents_To_Be_Sent,
									Volume,
									Applicable_Taxes,
									Specific_Packing,
									Mode_Of_Transport,
									Freight_By,
									Payment_Terms,
									Capacity_Available,
									Tool_Fixture_Requirement,
									Any_Subcontracting,
									Enquiry_Feasible,
									Remarks,
									Tolerance_Achievable) ";
$query.="VALUES('$enquiryid',
				'$scomps',
				'$requirementclear',
				'$clarification',
				'$tfeasible',
				'$fir',
				'$docu',
				'$volume',
				'$taxes',
				'$packing',
				'$tmode',
				'$freight',
				'$pterms',
				'$mcapa',
				'$tfireq',
				'$scont',
				'$ef',
				'$remarks',
				'$tol');";

print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added New Feasibility Review");	
	
}else
	{
		print("Error Adding");
	}


?>