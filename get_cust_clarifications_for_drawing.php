<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

if(isSet($_GET['drawid'])){$drawid=$_GET['drawid'];}else{$drawid='';}
if(isSet($_GET['eid'])){$eid=$_GET['eid'];}else{$eid='';}
if($drawid!='')
{
$q="SELECT *,SUBSTRING(Problem_Statement,1,40) as ps FROM Cust_Clarification WHERE Drawing_ID='$drawid';";
}else{
	$q="SELECT *,SUBSTRING(Problem_Statement,1,40) as ps FROM Cust_Clarification WHERE Enquiry_ID='$eid';";
}
$res=mysql_query($q) or die(mysql_error());
$r=mysql_affected_rows();

if($r!=0)
{
print("<p><label>Select Clarification</label>");
print("<select id=\"Clarification_ID\" name=\"Clarification_ID\" class=\"required\">");
print("<option value=\"\">Select Clarification Sent</option>");
while($row=mysql_fetch_assoc($res))
{
print("<option value=\"$row[Clarification_ID]\">$row[Date_Of_Request] : $row[ps]</option>");
}
print("</select><div id=\"ccpdf\"> </div></p>");
}

?>