<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT * FROM Dimn_Desc;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\">");
print("<tr><th>SL NO</th><th>Dimension Description</th><th>Comments</th></tr>");
while($row=mysql_fetch_assoc($res))
{
		$q="SELECT * FROM Dimn_Comment WHERE Desc_ID='$row[Desc_ID]';";
		$resc=mysql_query($q) or die(mysql_error());
		$n=mysql_affected_rows();
		if($n!=0)
		{$c='';
			while($r=mysql_fetch_assoc($resc))
			{
				$c.=$r['Comment'].'/';
			}
			$co=substr($c,0,strlen($c)-1);
		}
	print("<tr><td>$row[Desc_ID]</td><td>$row[Dimn_Desc]</td><td>$co</td></tr>");
	
	
}
print("</table>");
}
else {
	print("No Descriptions are added yet");
}
?>