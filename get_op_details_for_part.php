<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_GET['drawingid'];

$query="SELECT Program_NO,Operation_Desc,Stage_Drawing_Path,NC_Prog_Path,Operation_Notes,
		(SELECT GROUP_CONCAT(Fixture_NO) FROM Ope_Fixt_Map as fmp 
		WHERE fmp.Operation_ID=op.Operation_ID) AS fxtno,
		(SELECT GROUP_CONCAT('/drawings/',Operation_Image_Path) FROM Operation_Image as oi 
		WHERE oi.Operation_ID=op.Operation_ID) as opim 
		FROM Operation as op WHERE Drawing_ID='$drawingid' AND In_Op_List=0 ORDER BY Operation_Desc ASC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
print("<table border=\"1\" cellspacing=\"1\" style=\"width:100%\">");
print("<tr><th>Operation Description</th><th>Program NO</th><th>Fixture Number</th><th>Operation Images</th><th>Notes</th></tr>");
while($row=mysql_fetch_assoc($res))
{

	if($row['Stage_Drawing_Path']!='')
	{
		$p='<a class="pdf" href="/drawings/'.$row['Stage_Drawing_Path'].'" target="_NEW" title="View Image in New Tab">'.$row['Operation_Desc'].'</a>';
	}else{
		$p=$row['Operation_Desc'];
	}

	if($row['NC_Prog_Path']!='')
	{
		$n='<a class="pdf" href="'.$row['NC_Prog_Path'].$row['Program_NO'].'" target="_NEW" title="Opens NC Program in New Window">'.$row['Program_NO'].'</a>';
	}else{
		$n=$row['Program_NO'];
	}



	print("<tr><td>$p</td><td>$n</td><td>$row[fxtno]</td><td>");
	
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
	
	print("</td><td>$row[Operation_Notes]</td></tr>");
	
}
print("</table>");

}
else {
	print("No Operations Added For this Drawing Yet<p>");
}


///cust clarifications


$q2="SELECT * FROM Cust_Clarification WHERE Drawing_ID='$drawingid';";
$r2 = mysql_query($q2, $cxn) or die(mysql_error($cxn));	
$r=mysql_num_rows($r2);
if($r)
{

	print("Customer Clarifications For this Part:");
	$z=1;
	print("<table border=\"1\" cellspacing=\"1\" style=\"width:100%\">");
	print("<tr><th>Clarification</th><th>Reply</th><th>Remarks</th></tr>");
	while($row2=mysql_fetch_assoc($r2))
	{
				$ppath='/enquiry/'.$row2['PDF_Path'];
				$replaypath='/enquiry/'.$row2['Replay_Path'];
				print("<tr><td><a class=\"pdf\" href=\"$ppath\" target=\"_NEW\" > $z Dated $row2[Date_Of_Request] </a></td>");
			if($row2['Replay_Path']!='')
			{
				print("<td><a class=\"pdf\" href=\"$replaypath\" target=\"_NEW\" >  $z Dated $row2[Date_Of_Clarification]</a></td><td>$row2[Remarks]</td></tr>");
			}
				$z++;
	}
print("</table>");

}


$q3="SELECT *,Operation_Desc,Operator_Name,(SELECT GROUP_CONCAT('/logimages/',Image_Path) as ip FROM PRev_Image WHERE Process_Revision_ID=pr.Process_Revision_ID) FROM Process_Revision as pr
	INNER JOIN Operation as ope ON ope.Operation_ID=pr.Operation_ID
	INNER JOIN Component as comp ON comp.Drawing_ID=ope.Drawing_ID
	INNER JOIN Operator as oper ON oper.Operator_ID=pr.Operator_ID
	WHERE comp.Drawing_ID='$drawingid' ORDER BY Change_Date Desc;";
$r3 = mysql_query($q3, $cxn) or die(mysql_error($cxn));	
$r=mysql_num_rows($r3);
if($r)
{

	print("Improvement History DEW\PRD\R\\11:");
	print("<table border=\"1\" cellspacing=\"1\" style=\"width:100%\"><tr>
			<th>Operation</th><th>Changed By</th><th>Change Date</th><th>Reason For Change</th><th>Changes</th><th>Remarks</th><th>Images</th></tr>");
	while($row3=mysql_fetch_assoc($r3))
	{	

				if((isSet($row3['ip']))&&($row3['ip']!=''))
		{
			$images=explode(',', $row3['ip']);
			
			$ip="<table><tr><td>";
			$y=1;
			for($z=0;$z<count($images);$z++)
			{
				$ip.="<a class=\"pdf\" href=\"$images[$z]\" target=\"_NEW\" title=\"View Image in New Tab\">$y&nbsp;&nbsp;&nbsp;  </a>";
				$y++;
			}
			$ip.="</td></tr></table>";			
		}else{
			
			$ip='';
		}

		print("<tr><td>$row3[Operation_Desc]</td><td>$row3[Operator_Name]</td><td>$row3[Change_Date]</td><td>$row3[Revision_Reason]</td><td>$row3[Process_Changes]</td><td>$row3[Remarks]</td><td>$ip</td></tr>");

	}


}









?>