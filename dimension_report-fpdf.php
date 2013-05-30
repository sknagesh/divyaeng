<?php
include('dewdb.inc');
require('fpdf.php');
require('cellfit.php');
require (dirname (__FILE__) . "/class-excel-xml.inc.php");

//print_r($_POST);


$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$jobnos=$_POST['jobno'];
$bnos=$_POST['bno'];
$opid = $_POST['Operation_ID'];
$batchid=$_POST['Batch_ID'];
$pdfxl=$_POST['pdfxl'];
$rtype=$_POST['Report_Type'];
$comment=$_POST['comment'];

if($rtype==1){
$docref="Final Inspection Report";	
}else{$docref="Inprocess Inspection Report";}

if(isSet($_POST['rdate'])){$rdate=$_POST['rdate'];}else{$rdate="";}

//print_r($bnos);
$jobno=array_values($jobnos);  //to reassign array values in an order ie, 1,3,7 to 0,1,2
$bno=array_values($bnos);  //to reassign array values in an order ie, 1,3,7 to 0,1,2
//print_r($bno);
$bnomax=count($bno);


$j="SELECT Component_Name, Customer_Name, Drawing_NO FROM
		 Operation as op INNER JOIN Component as  comp ON comp.Drawing_ID=op.Drawing_ID
		 INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID WHERE op.Operation_ID='$opid';";
	$rr = mysql_query($j, $cxn) or die(mysql_error($cxn));
while($rrs=mysql_fetch_assoc($rr))
{
	$cname=$rrs['Component_Name'];
	$partno=$rrs['Drawing_NO'];
	$custname=$rrs['Customer_Name'];
}


$kj="SELECT Heat_Code,Mfg_Batch_NO,Material_Code FROM Batch_NO as bn 
		INNER JOIN BNo_MI_Challans as bmc ON bmc.Batch_ID=bn.Batch_ID 
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID  
		WHERE bn.Batch_ID='$batchid';";
$krr = mysql_query($kj, $cxn) or die(mysql_error($cxn));
while($krrs=mysql_fetch_assoc($krr))
{
	$heatcode=$krrs['Heat_Code'];
	$materialcode=$krrs['Material_Code'];
	$batchdesc=$krrs['Mfg_Batch_NO'];
}
//print("heat code=$heatcode materialcode= $materialcode batch desc=$batchdesc");


	$jobq="SELECT Job_NO FROM Dimn_Observation WHERE Operation_ID='$opid' AND Batch_ID='$batchid';";
	$r = mysql_query($jobq, $cxn) or die(mysql_error($cxn));

	$qry="SELECT ip.Operation_ID, Basic_Dimn,Dimn_Desc,Tol_Lower,Tol_Upper,ip.Instrument_ID,Instrument_Description,
			Instrument_SLNO,Baloon_NO,Dimn_Desc FROM Dimension as ip 
			INNER JOIN Instrument AS inst ON inst.Instrument_ID=ip.Instrument_ID 
			INNER JOIN Operation AS op ON ip.Operation_ID=op.Operation_ID 
			INNER JOIN Component AS comp ON op.Drawing_ID=comp.Drawing_ID 
			INNER JOIN Dimn_Desc as dd ON dd.Desc_ID=ip.Desc_ID  
			WHERE ip.Operation_ID='$opid' ORDER BY Baloon_NO ASC;";
//print("<br>$qry");
	$j=0;
	$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
	while ($row = mysql_fetch_assoc($resa))  //get all dimensions for thi operation and store them in an array
        		{
	$lrows[$j]=array($row['Baloon_NO'],$row['Dimn_Desc'],$row['Basic_Dimn'],$row['Tol_Lower'].'/'.$row['Tol_Upper'],$row['Instrument_SLNO'].'-'.$row['Instrument_Description']);		
	$j++;
		        }
//print("<br>lrows");
//print_r($lrows);

	$z=0;
	while($z<sizeof($jobno))  //loop through each job inspected
	{
$qry="SELECT dimn.Dimension_ID, dimn.Operation_ID,Insp_Date, 
					Basic_Dimn, ob.Dimn_Observation_ID, Batch_ID,
					Job_NO, Observed_Dimn,ob.Comment_ID,Comment FROM Observations as ob
					INNER JOIN Dimn_Observation as do ON do.Dimn_Observation_ID=ob.Dimn_Observation_ID
					LEFT OUTER JOIN Dimn_Comment AS dc ON dc.Comment_ID=ob.Comment_ID 
					LEFT OUTER JOIN Dimension as dimn ON dimn.Dimension_ID = ob.Dimension_ID 
					AND do.Job_NO='$jobno[$z]'  AND Batch_ID='$batchid'
					WHERE dimn.Operation_ID = '$opid' ORDER BY Baloon_NO ASC";				
			
		
//		print($qry);
		$res = mysql_query($qry, $cxn) or die(mysql_error($cxn));
		$x=0;
		while($row=mysql_fetch_assoc($res))  //for each job get dimensions measured and store it in an array
		{
			if($row['Comment']==''){$ob=$row['Observed_Dimn'];}else{$ob=$row['Comment'];}
			$rrow[$z][$x]=$ob.' , '.$row['Remarks'];
			$jdate=$row['Insp_Date'];
			$x+=1;
		}
//	$jobno[$z]=$i['Job_NO'];	
	$z+=1;
	}

//print("<br>rrows<br>");
//print_r($rrow);

class PDF_SKN extends PDF_CellFit
{
// Page header
function Header()
{
	$cname=$GLOBALS['cname'];
	$custname=$GLOBALS['custname'];
	$partno=$GLOBALS['partno'];
	$jdate=$GLOBALS['jdate'];
	$heatcode=$GLOBALS['heatcode'];
	$materialcode=$GLOBALS['materialcode'];
    $rtype=$GLOBALS['rtype'];
    $comment=$GLOBALS['comment'];
    $rdate=$GLOBALS['rdate'];
    $docref=$GLOBALS['docref'];
    $batchdesc=$GLOBALS['batchdesc'];
    
    if($rdate!=""){$jdate=$rdate;}else{$jdate=change_date_format_for_dispaly($jdate);}
    
    $this->SetFont('Arial','',16);
    $this->CellFitScale(100,18,'Divya Engineering Works (P) Ltd, Mysore',1,0,'C');
	$this->CellFitScale(100,18,$rtype,1,0,'C');
	$this->SetFont('Arial','', 10);
	$this->Cell(75,6,'RECORD REF: '.$docref,'T R',2,'L');
	$this->Cell(75,6,'DATE: 01-06-2003','R',2,'L');
	$this->Cell(75,6,'REV NO: 00','B R',0,'L');
	$this->ln();
    $this->Cell(200,6,'ITEM: '.$cname,'L T R',0,'L');
    $this->Cell(75,6,'HEAT NO: '.$heatcode,'T R',1,'L');
    $this->Cell(200,6,'Customer: '.$custname,'L R',0,'L');
    $this->Cell(75,6,'Material Stock NO: '.$materialcode,'R',1,'L');
    $this->Cell(100,6,'Drg No/Rev No: '.$partno,'L B R',0,'L');
	$this->Cell(100,6,$comment,'B R',0,'L');
    $this->Cell(75,6,'DATE: '.$jdate.' | Batch No: '.$batchdesc,'L B R',1,'L');

	}

function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-35);
	// Arial italic 8
    $this->SetFont('Arial','',16);
	$this->Cell(220,10,"Inspected By: G.S ",'0',0,'L');
	$this->Cell(140,10,"Approved By: U.S",'0',1,'L');
	
    // Page number
    $this->SetY(-10);
    $this->SetFont('Arial','',6);
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}



$pdf = new PDF_SKN();
$pdf->AliasNbPages();
$pdf->setAutoPageBreak(1,35);
$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','',10);
if(!isSet($rrow))
{ //if no jobs are measured for this operation
	print("<br>No Dimensions entered for this operation for this batch number");
}
else {
		$pdf->Cell(20,8,'Baloon No',1,0,'L');
		$pdf->Cell(20,8,'Desc',1,0,'L');
		$pdf->Cell(20,8,'Dimension',1,0,'L');
		$pdf->Cell(20,8,'Tolerance',1,0,'L');
		$pdf->Cell(20,8,'Instrument',1,0,'L');
		$xldata[0]=array('Baloon No','Desc','Dimension','Tolerance','Instrument');
		$z=0;
		$jbs=count($jobno);
		$jbsmax=5;
		
		while($z<$jbsmax)  //append job nos to first row heading
		{
			if($z<$jbs)
			{
		$pdf->Cell(35,8,$jobno[$z],1,0,'L');
		array_push($xldata[0],$jobno[$z]);
			}else{
				$pdf->Cell(35,8,'',1,0,'L');
			}
		$z++;
		}
		$pdf->ln(); 

		$z=0;
		$bna=0; //baloon no counter
		$xy=1;
		while($z<count($lrows))  //while no of dimensions defined for this operation
		{
		$oktodisplay=0;
			foreach($lrows[$z] as $x) //print dimension details
			{
					
				if($x==$bno[$bna] || $oktodisplay==1)  //if baloon no is the one selected to be displayed
				{
					$pdf->CellFitScale(20,8,$x,1,0,'L');
					$xldata[$xy]=$lrows[$z];
					$oktodisplay=1;
				}else{break;}
			$x+=1;
			}
			if($oktodisplay==1)
			{
				$s=0;
				while($s<$jbsmax)  //print relavant dimensions measured
				{
					if($s<$jbs)
					{
						$pdf->CellFitScale(35,8,$rrow[$s][$z],1,0,'L');
						array_push($xldata[$xy],$rrow[$s][$z]);
					
					}else{
						$pdf->Cell(35,8,'',1,0,'L');
					}
			
				$s+=1;
				}
			$pdf->ln();   //end of each row
			$xy+=1;
			}

					if($bna<$bnomax-1 && $oktodisplay==1)
					{
//						print("<br>inside bna counter bna=$bna max=$bnomax");
					$bna+=1;
					}
		$z++;		
		}


  }        				

if(!$pdfxl)
{
		$name="$opid".".pdf";
//		$name="temp.pdf";
		$pdf->Output($name,'F');
}else{
$xls = new Excel_XML;
$xls->addArray ( $xldata );
$xls->generateXML ($opid);
}
 
?>