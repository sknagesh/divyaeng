<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$toolid=$_GET['tid'];


$query="SELECT Insert_ID,Tool_Part_NO,Tool_Desc,Insert_Part_NO,Insert_Description 
		FROM Inserts as ins INNER JOIN Tool as t ON t.Tool_ID=ins.Tool_ID WHERE ins.Tool_ID='$toolid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");

print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>Tool Part No</th><th>Tool Description</th><th>Insert Part No</th><th>Insert Description</th></tr>");
while($row=mysql_fetch_assoc($res))
{
        print("<tr><td>$row[Tool_Part_NO]</td><td>$row[Tool_Desc]</td><td>$row[Insert_Part_NO]</td><td>$row[Insert_Description]</td></tr>");

}
print("</table>");

print("</table>");


}
else {
	print("This Tool Is Not Inserted OR No Inserts Added For this Tool");
}
?>