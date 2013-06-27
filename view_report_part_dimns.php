<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
if(isSet($_GET['oid'])){$opid = $_GET['oid'];}else{$opid='';}
if(isSet($_GET['drawingid'])){$drawingid = $_GET['drawingid'];}else{$drawingid='';}
$batchid=$_GET['batchid'];
$reptype=$_GET['reptype'];


if($opid!='')   ///if opid is set then we want inprocess report
{
$jobq="SELECT GROUP_CONCAT(Job_NO) as jn FROM Dimn_Observation WHERE Operation_ID='$opid' AND Batch_ID='$batchid';";
$r = mysql_query($jobq, $cxn) or die(mysql_error($cxn));

$i=mysql_fetch_assoc($r);
$jobno=explode(',', $i['jn']);  //store unique job nos to display in headding	

	$jobq="SELECT Job_NO FROM Observations as ob
	inner join Dimn_Observation as do on do.Dimn_Observation_ID=ob.Dimn_Observation_ID
	 inner join Dimension as dimn on dimn.Dimension_ID=ob.Dimension_ID
	 WHERE dimn.Operation_ID='$opid' AND do.Batch_ID='$batchid';";
	$r = mysql_query($jobq, $cxn) or die(mysql_error($cxn));

	$qry="SELECT dimn.Operation_ID, Basic_Dimn,dimn.Desc_ID,Tol_Lower,Tol_Upper,dimn.Instrument_ID,Instrument_Description, 
			Baloon_NO,Dimn_Desc FROM Dimension as dimn 
			INNER JOIN Instrument AS inst ON inst.Instrument_ID=dimn.Instrument_ID 
			INNER JOIN Operation AS op ON dimn.Operation_ID=op.Operation_ID
			INNER JOIN Dimn_Desc as dd ON dd.Desc_ID=dimn.Desc_ID  
			INNER JOIN Component AS comp ON op.Drawing_ID=comp.Drawing_ID 
			WHERE dimn.Operation_ID='$opid' ORDER BY Baloon_NO ASC;";
//print("<br>$qry");
	$j=0;
	$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
	while ($row = mysql_fetch_assoc($resa))  //get all dimensions for thi operation and store them in an array
        		{
	$lrows[$j]=array($row['Baloon_NO'],$row['Dimn_Desc'],$row['Basic_Dimn'],$row['Tol_Lower'].'/'.$row['Tol_Upper'],$row['Instrument_Description']);		
	$j++;
		        }
//print("<br>lrows");
//print_r($lrows);

$jobq="SELECT DISTINCT(Job_NO) FROM Dimn_Observation WHERE Operation_ID='$opid' AND Batch_ID='$batchid';";
$r = mysql_query($jobq, $cxn) or die(mysql_error($cxn));

	$z=0;
	while($i=mysql_fetch_assoc($r))  //loop through each job inspected
	{
	$qry="SELECT dimn.Dimension_ID, dimn.Operation_ID, 
					Basic_Dimn, ob.Dimn_Observation_ID, Batch_ID,Baloon_NO,
					Job_NO, Observed_Dimn,ob.Comment_ID,Comment FROM Observations as ob
					INNER JOIN Dimn_Observation as do ON do.Dimn_Observation_ID=ob.Dimn_Observation_ID
					LEFT OUTER JOIN Dimn_Comment AS dc ON dc.Comment_ID=ob.Comment_ID 
					RIGHT OUTER JOIN Dimension as dimn ON dimn.Dimension_ID = ob.Dimension_ID 
					AND do.Job_NO='$i[Job_NO]'  AND Batch_ID='$batchid'
					WHERE dimn.Operation_ID = '$opid' ORDER BY Baloon_NO ASC";
	//	print("$qry<br>");
		$res = mysql_query($qry, $cxn) or die(mysql_error($cxn));
		$x=0;
		while($row=mysql_fetch_assoc($res))  //for each job get dimensions measured and store it in an array
		{
			if($row['Comment']==''){$ob=$row['Observed_Dimn'];}else{$ob=$row['Comment'];}
			$rrow[$z][$x]=$ob;
			$x+=1;
		}
//	$jobno[$z]=$i['Job_NO'];	
	$z+=1;
	}

//print("<br>rrows<br>");
//print_r($rrow);


if(!isSet($rrow))
{ //if no jobs are measured for this operation
	print("<br>No Dimensions entered for this operation for this batch number");
}
else {

		print("<table border=\"1\" cellspacing=\"1\" id=\"inprocesstble\">");
		print("<tr><th>In Report</th><th>Baloon No</th><th>Dimension Desc</th><th>Basic dimn</th>");
		print("<th>Tolerance</th><th>Instrument</th>");
		$z=0;
		while($z<count($jobno))  //append job nos to first row heading
		{
		print("<th>$jobno[$z] <input type=\"checkbox\" id=\"jobno\" name=\"jobno[$z]\" value=\"$jobno[$z]\" ></th>");
		$z++;
		}
		print("</tr>");  

		$z=0;
		while($z<count($lrows))  //while no of dimensions defined for this operation
		{
			print("<tr>");
			$t=0;
			foreach($lrows[$z] as $x) //print dimension details
			{
				if($t==0){
					print("<td><input type=\"checkbox\" name=\"bno[$z]\" id=\"bno\" value=\"$x\"></td>");
					$t=1;
				}
			print("<td>$x</td>");
			$x+=1;
			}
			$s=0;
			while($s<count($jobno))  //print relavant dimensions measured
			{
			print("<td>");
			print($rrow[$s][$z]);
			print("</td>");
			$s+=1;
			}
			print("</tr>");   //end of each row
		$z++;		
		}

  		print("</table>");
  
  }        				

}else///end of inprocess insp report

{  ///final inspection report



	$qry="SELECT dimn.Operation_ID, Basic_Dimn,dimn.Desc_ID,Tol_Lower,Tol_Upper,dimn.Instrument_ID,Instrument_Description, 
			Baloon_NO,Dimn_Desc FROM Dimension as dimn 
			INNER JOIN Operation AS ope ON ope.Operation_ID=dimn.Operation_ID
			INNER JOIN Component as comp ON comp.Drawing_ID=ope.Drawing_ID
			INNER JOIN Instrument AS inst ON inst.Instrument_ID=dimn.Instrument_ID 
			INNER JOIN Operation AS op ON dimn.Operation_ID=op.Operation_ID
			INNER JOIN Dimn_Desc as dd ON dd.Desc_ID=dimn.Desc_ID  
			WHERE comp.Drawing_ID='$drawingid' ORDER BY Baloon_NO ASC;";
			
//print("<br>$qry");

	$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
	$j=0;
	while ($row = mysql_fetch_assoc($resa))  //get all dimensions for thi operation and store them in an array
        		{
	$lrows[$j]=array($row['Baloon_NO'],$row['Dimn_Desc'],$row['Basic_Dimn'],$row['Tol_Lower'].'/'.$row['Tol_Upper'],$row['Instrument_Description']);		
	$j++;
		        }

//print("<br>lrows");
//print_r($lrows);



	$jobq="SELECT DISTINCT(Job_NO) FROM Dimn_Observation WHERE Batch_ID='$batchid';";
	$rj = mysql_query($jobq, $cxn) or die(mysql_error($cxn));

$z=0;
	while($i=mysql_fetch_assoc($rj))  //loop through each job inspected
	{
		$x=0;
				$qry="SELECT dimn.Dimension_ID, Basic_Dimn,
				(SELECT Observed_Dimn FROM Observations as ob 
				INNER JOIN Dimn_Observation as do ON do.Dimn_Observation_ID=ob.Dimn_Observation_ID 
				WHERE do.Batch_ID='$batchid' AND Job_NO='$i[Job_NO]' AND ob.Dimension_ID=dimn.Dimension_ID) as obd,
				(SELECT Comment FROM Dimn_Comment as comm 
				INNER JOIN Observations as obc ON obc.Comment_ID=comm.Comment_ID 
				INNER JOIN Dimn_Observation as do2 ON do2.Dimn_Observation_ID=obc.Dimn_Observation_ID
				 WHERE do2.Batch_ID='$batchid' AND Job_NO='$i[Job_NO]' AND obc.Dimension_ID=dimn.Dimension_ID) as obco 
				FROM Dimension as dimn
				INNER JOIN Operation as ope ON ope.Operation_ID=dimn.Operation_ID
				INNER JOIN Component as comp ON comp.Drawing_ID=ope.Drawing_ID
				WHERE comp.Drawing_ID='$drawingid'  ORDER BY Baloon_NO ASC;";
			
//		print("$qry<br>");
		$res = mysql_query($qry, $cxn) or die(mysql_error($cxn));
		
		while($row=mysql_fetch_assoc($res))  //for each job get dimensions measured and store it in an array
		{
			if($row['obco']==''){$ob=$row['obd'];}else{$ob=$row['obco'];}
			$rrow[$z][$x]=$ob;
			$x+=1;
		}

//	$jobno[$z]=$i['Job_NO'];	
	$z+=1;
	}



//print("<br>rrows<br>");
//print_r($rrow);

$jobq="SELECT DISTINCT(Job_NO) FROM Dimn_Observation WHERE Batch_ID='$batchid';";
$r = mysql_query($jobq, $cxn) or die(mysql_error($cxn));
$m=0;
while($i=mysql_fetch_assoc($r))
{
$jobno[$m]=$i['Job_NO'];  //store unique job nos to display in headding
$m++;	
}




if(!isSet($rrow))
{ //if no jobs are measured for this operation
	print("<br>No Dimensions entered for this operation for this batch number");
}
else {

		print("<table border=\"1\" cellspacing=\"1\" id=\"inprocesstble\">");
		print("<tr><th>In Report</th><th>Baloon No</th><th>Dimension Desc</th><th>Basic dimn</th>");
		print("<th>Tolerance</th><th>Instrument</th>");
		$z=0;
		while($z<count($jobno))  //append job nos to first row heading
		{
		print("<th>$jobno[$z] <input type=\"checkbox\" id=\"jobno\" name=\"jobno[$z]\" value=\"$jobno[$z]\" ></th>");
		$z++;
		}
		print("</tr>");  

		$z=0;
		while($z<count($lrows))  //while no of dimensions defined for this operation
		{
			print("<tr>");
			$t=0;
			foreach($lrows[$z] as $x) //print dimension details
			{
				if($t==0){
					print("<td><input type=\"checkbox\" name=\"bno[$z]\" id=\"bno\" value=\"$x\"></td>");
					$t=1;
				}
			print("<td>$x</td>");
			$x+=1;
			}
			$s=0;
			while($s<count($jobno))  //print relavant dimensions measured
			{
			print("<td>");
			print($rrow[$s][$z]);
			print("</td>");
			$s+=1;
			}
			print("</tr>");   //end of each row
		$z++;		
		}

  		print("</table>");
  
  }        				


	
	
}


	
?>