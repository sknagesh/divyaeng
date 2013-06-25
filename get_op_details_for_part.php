<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_GET['drawingid'];

$query="SELECT Program_NO,Operation_Desc,
		(SELECT GROUP_CONCAT(Fixture_NO) FROM Ope_Fixt_Map as fmp 
		WHERE fmp.Operation_ID=op.Operation_ID) AS fxtno,
		(SELECT GROUP_CONCAT('/drawings/',Operation_Image_Path) FROM Operation_Image as oi 
		WHERE oi.Operation_ID=op.Operation_ID) as opim 
		FROM Operation as op WHERE Drawing_ID='$drawingid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\" style=\"width:100%\">");
print("<tr><th>Operation Description</th><th>Program NO</th><th>Fixture Number</th><th>Operation Images</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	print("<tr><td>$row[Operation_Desc]</td><td>$row[Program_NO]</td><td>$row[fxtno]</td><td>");
	
if($row['opim']!='')
{
			$images=explode(',', $row['opim']);
			
			print("<table><tr>");
$y=1;
			for($z=0;$z<count($images);$z++)
			{
				print("<td><a class=\"opimg\" href=\"$images[$z]\">Image $y</a></td>");
$y++;
			}
			print("</tr></table>");			
	
}	
	
	
	
	
	
	print("</td></tr>");
	
}
print("</table>");


}
else {
	print("No Operations Added For this Drawing Yet");
}
?>