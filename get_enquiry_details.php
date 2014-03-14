<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_GET);
$eid=$_GET['eid'];
$uploadDir = '/home/www/enquiry/';

$query="SELECT * FROM Feasibility_Review WHERE Enquiry_ID='$eid';";

$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$noofrecords=mysql_affected_rows();
$fr='';
if($noofrecords!=0)
{
	$row=mysql_fetch_assoc($resa);
$q3="SELECT * FROM Cust_Clarification WHERE Enquiry_ID='$eid';";
$r3 = mysql_query($q3, $cxn) or die(mysql_error($cxn));
$n=mysql_affected_rows();
if($n>0){
	$c='';
	$z=1;
while($re3=mysql_fetch_assoc($r3))
{

	$ppath='/enquiry/'.$re3['PDF_Path'];
	$c.="<a class=\"pdf\" href=\"$ppath\" target=\"_NEW\" >Clarification $z</a>";
	$z++;
	}
}
$fr.="<table><tr><th>Similar Products:</th><td> $row[Similar_Product_Details]</td>";
$fr.="<th>Drawing Clear: </th><td>";
if($row['Drawing_Clarity']==1){$fr.="Yes</td></tr>";}else{$fr.="No</td></tr>";}
$fr.="<tr><th>Clarifications :</th><td>".$c.'</td>';
$fr.="<th>Technically Feasible: </th><td>";
if($row['Technically_Feasible']==1){$fr.="Yes</td></tr>";}else{$fr.="No</td></tr>";}
$fr.="<tr><th>Tolerance Achievable: </th><td>";
if($row['Tolerance_Achievable']==1){$fr.="Yes</td>";}else{$fr.="No</td>";}
$fr.="<th>Special Final Inspection Report: </th><td>";
if($row['Specific_FIR']==1){$fr.="Yes</td>";}else{$fr.="No</td></tr>";}
$fr.="<tr><th>Documents to be Sent for Each Dispatch: </th><td> $row[Documents_To_Be_Sent]</td>";
$fr.="<th>Volume: </th><td>$row[Volume]</td></tr>";
$fr.="<tr><th>Applicable Taxes: </th><td>$row[Applicable_Taxes]</td>";
$fr.="<th>Specific Packing: </th><td>$row[Specific_Packing]</td></tr>";
$fr.="<tr><th>Mode Of Transport: </th><td>$row[Mode_Of_Transport]</td>";
$fr.="<th>Freight Paid By: </th><Td>$row[Freight_By]</td></tr>";
$fr.="<tr><th>Payment Terms: </th><td>$row[Payment_Terms]</td>";
$fr.="<th>Capacity Available: </th><td>$row[Capacity_Available]</td></tr>";
$fr.="<tr><th>Special Tooling/Fixture etc Required: </th><td>$row[Tool_Fixture_Requirement]</td>";
$fr.="<th>Any Subcontracting Required: </th<<td>$row[Any_subcontracting]</td></tr>";
$fr.="<tr><th>Component Feasible: </th><td>";
if($row['Enquiry_Feasible']==1){$fr.="Yes</td>";}else{$fr.="No</td>";}
$fr.="<th>Remarks: </th><td>$row[Remarks]</td></tr>";







}

print($fr);
?>