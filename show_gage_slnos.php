<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$gid=$_GET['gid'];


$query="SELECT g.Gage_ID,Gage_Desc,Gage_No,DATE_FORMAT(Date_Received,'%d-%m-%Y') as d,Gage_Type FROM Gage_SlNo as gsl 
		INNER JOIN Gage as g On g.Gage_ID=gsl.Gage_ID WHERE g.Gage_ID=$gid;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");

print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Gage_ID</th><th>Gage Description</th><th>Gage Serial No</th><th>Date Received</th><th>Gage Type</th></tr>");
while($row=mysql_fetch_assoc($res))
{
        print("<tr><td>$row[Gage_ID]</td><td>$row[Gage_Desc]</td><td>$row[Gage_No]</td><td>$row[d]</td><td>$row[Gage_Type]</td></tr>");

}
print("</table>");

print("</table>");


}
else {
	print("There are no Gages in Stock");
}
?>