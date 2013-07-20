<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_GET);
if(isSet($_GET['eid'])){$eid=$_GET['eid'];}else{$eid="";}
if(isSet($_GET['sdate'])){$sdate=$_GET['sdate'];}else{$sdate="";}
if(isSet($_GET['edate'])){$edate=$_GET['edate'];}else{$edate="";}


if($mid!='')
{
	
	$mid="AND er.Enquiry_ID='$eid'";
}

if($sdate!='')
{
	
	$sdate="AND Enquiry_Date>='$sdate'";
}

if($edate!='')
{
	
	$edate="AND Enquiry_Date<='$edate'";
}

$query="SELECT * FROM Enquiry_Record WHERE Customer_Name!='' $mid $sdate $edate;";


	
//print("$query<br>");

print("<br><h1>Enquires Received </h1><br>");
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$noofrecords=mysql_affected_rows();
if($noofrecords!=0)
{
	$c="q";
print("<table cellspacing=\"1\" cellborder=\"1\" >");
print("<tr class=\"t\" ><th>Details</th><th>Enquiry ID</th><th>Customer Name</th><th>Enquiry Date</th><th>Required Date</th>
						<th>Enquiry Ref</th><th>Component Name</th><th>Drawing No</th>
						<th>Material</th><th>Material Source</th><th>Visual Ref</th><th>Remarks</th></tr></table>");
$i=0;
while ($row = mysql_fetch_assoc($resa))
{
print("<table cellspacing=\"1\" cellborder=\"1\" >");		
print("<tr class=\"$c\"><td><input type=\"radio\" name=\"detail\" class=\"detail\" id=\"detail[$i]\" value=\"$row[Enquiry_ID]\"></td>
	<td>$row[Enquiry_ID]</td><td>$row[Customer_Name]</td><td>$row[Enquiry_Date]</td><td>$row[Required_Date]</td>
						<td>$row[Enquiry_Ref]</td><td>$row[Component_Name]</td><td>$row[Drawing_NO]</td><td>$row[Component_Material]</td>
						<td>$row[Material_Source]</td><td>$row[Visual_Ref]</td><td>$row[Remarks]</td></tr>");
print("<tr><td colspan=\"12\"><div id=\"$i\"></div></td><tr>");
print("</table>");
if($c=="q"){$c="s";}else{$c="q";}
$i++;
}

}else{print("No Enquired Found This Period");}



?>