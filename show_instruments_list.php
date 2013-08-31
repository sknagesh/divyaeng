<?php
include('dewdb.inc');
require_once('../tcpdf/tcpdf.php');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$type=$_GET['type'];
if(isSet($_GET['rpt'])){$rpt=$_GET['rpt'];}else{$rpt='';}
$query="SELECT *,DATE_FORMAT(Calibration_Date,'%d-%m-%Y') as cd FROM Instrument WHERE Show_In_List=1 ORDER BY Instrument_ID ASC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{

	if($type=='list')
	{


		print("<h2>List Of Measuring Instruments And Gages</h2>");
		print("<h3>Document. Ref: DEW/QA/D/01 Issue NO.: 06  Date: 01-06-2013</h3>");

		print("<table border=\"1\" cellspacing=\"1\">");
		print("<tr><th>Serial No</th><th>Description</th><th>Make</th><th>Range</th>
		<th>Least Count</th><th>Calibration Frequency</th><th>Acceptable Error</th><th>Remarks</th></tr>");
		while($row=mysql_fetch_assoc($res))
			{

				print("<tr><td>$row[Instrument_SLNO]</td><td>$row[Instrument_Description]</td>
			<td>$row[Make]</td><td>$row[Instrument_Range]</td><td>$row[Least_Count] mm</td>
			<td>$row[Calibration_Frequency] Months</td><td>$row[Acceptable_Error]</td><td>$row[Remarks]</td></tr>");
	
			}
		print("</table>");

	}else
	if($type="history")
	{

		print("<h2>Calibration History Card</h2>");
		print("<h3>Document. Ref: DEW/QA/R/01 Issue NO.: 01  Date: 01-05-2012</h3>");



		print("<table border=\"1\" cellspacing=\"1\">");
		print("<tr><th>Serial No</th><th>Description</th><th>Make</th><th>Range</th>
		<th>Least Count</th><th>Calibration Date</th><th>Next Calibration Due Date</th><th>Remarks</th></tr>");
			while($row=mysql_fetch_assoc($res))
		{

					if($row['Calibration_Date']!='')
					{
					$cd = strtotime($row['Calibration_Date']);
	  				$ncd = date('Y-m-d', mktime(0,0,0,date('m',$cd)+$row['Calibration_Frequency'],date('d',$cd-1),date('Y',$cd)));
					$ncdisplay = date('d-m-Y', mktime(0,0,0,date('m',$cd)+$row['Calibration_Frequency'],date('d',$cd-1),date('Y',$cd)));
  					$today_start = strtotime('today');
					$monthperiod = strtotime('+1 month');
					$duedate = strtotime($ncd);

//print("<br>today= $today_start, 1 month= $monthperiod, duedate=$duedate");
						if ($duedate > $monthperiod)
  							{

							$s='style="background-color: lightgreen"';
 							$c='';
  							}
  							if ($duedate < $monthperiod)
  							{
							$s='style="background-color: orange"';
							$c="Calibration Due This Month";
  							}
  							if ($duedate < $today_start)
  							{
							$s='style="background-color: red"';
							$c="Calibration Over Due";
  							}

					}else
  					{
  					$ncd='';$ncdisplay=''; $c='';$s='style="background-color: white"';
  					} 

	print("<tr><td>$row[Instrument_SLNO]</td><td>$row[Instrument_Description]</td>
			<td>$row[Make]</td><td>$row[Instrument_Range]</td><td>$row[Least_Count]</td>
			<td>$row[cd]</td><td $s>$ncdisplay $c</td><td>$row[Remarks]</td></tr>");
	

		}

		print("</table>");
		}
}
else {
	print("No Instruments Added");
}



if($rpt=='export')
	{

$query="SELECT *,DATE_FORMAT(Calibration_Date,'%d-%m-%Y') as cd FROM Instrument WHERE Show_In_List=1 ORDER BY Instrument_ID ASC;";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);

class PDF_SKN extends TCPDF {
// Page header
function Header()
{
	$type=$GLOBALS['type'];

    
    $this->SetFont('helvetica','',12);
    $this->MultiCell(60, 18, 'Divya Engineering Works (P) Ltd', 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
    if($type=='list')
    {
    $this->MultiCell(100, 18, 'List Of Measuring Instruments and Gages', 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
    $this->SetFont('helvetica','', 8);
	$this->Cell(85,6,'RECORD REF: DEW/QA/D/01','T R',2,'L');
	$this->Cell(85,6,'DATE: 01-06-2013','R',2,'L');
	$this->Cell(85,6,'ISSUE NO: 06','B R',0,'L');
    }else{
    $this->MultiCell(100, 18, 'Calibration History Card', 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
    $this->SetFont('helvetica','', 8);
	$this->Cell(85,6,'RECORD REF: DEW/QA/R/01','T R',2,'L');
	$this->Cell(85,6,'DATE: 01-05-2012','R',2,'L');
	$this->Cell(85,6,'ISSUE NO: 01','B R',0,'L');
	}
	$this->ln();

	}

function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-32);
	// Arial italic 8
    $this->SetFont('helvetica','',16);
	$this->Cell(140,10,"Approved By: ",'0',1,'L');
	    // Page number
    $this->SetY(-10);
    $this->SetFont('helvetica','',6);
	$this->Cell(0,10,'Page'.$this->getAliasNumPage().'/'.$this->getAliasNbPages(),0,0,'C');
}
}


$pdf = new PDF_SKN(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->AliasNbPages();
$pdf->setTopMargin(10);
$pdf->setAutoPageBreak(0,35);
$pdf->AddPage('P','A4');
$pdf->SetFont('helvetica','',10);

		$pdf->SetY(18);
		$pdf->Cell(20,8,'SL NO',1,0,'L');
		$pdf->Cell(50,8,'Description',1,0,'L');
		$pdf->Cell(20,8,'Make',1,0,'L');
		$pdf->Cell(20,8,'Range',1,0,'L');
		$pdf->Cell(20,8,'Least Count',1,0,'L');
		if($type=='list')
		{
		$pdf->MultiCell(20, 8,'Calibration Frequency', 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
		$pdf->MultiCell(20, 8,'Acceptable Error', 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
		}else{
		$pdf->MultiCell(20, 8,'Calibration Date', 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
		$pdf->MultiCell(20, 8,'Next Calibration Date', 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
		}
		$pdf->Cell(50,8,'Remarks',1,0,'L');
		$pdf->ln();

		while($row=mysql_fetch_assoc($res))
			{



					if($row['Calibration_Date']!='')
					{
					$cd = strtotime($row['Calibration_Date']);
	  				$ncd = date('Y-m-d', mktime(0,0,0,date('m',$cd)+$row['Calibration_Frequency'],date('d',$cd-1),date('Y',$cd)));
					$ncdisplay = date('d-m-Y', mktime(0,0,0,date('m',$cd)+$row['Calibration_Frequency'],date('d',$cd-1),date('Y',$cd)));
  					$today_start = strtotime('today');
					$monthperiod = strtotime('+1 month');
					$duedate = strtotime($ncd);

//print("<br>today= $today_start, 1 month= $monthperiod, duedate=$duedate");
						if ($duedate > $monthperiod)
  							{

							$s='style="background-color: lightgreen"';
 							$c='';
  							}
  							if ($duedate < $monthperiod)
  							{
							$s='style="background-color: orange"';
							$c="Calibration Due This Month";
  							}
  							if ($duedate < $today_start)
  							{
							$s='style="background-color: red"';
							$c="Calibration Over Due";
  							}

					}else
  					{
  					$ncd='';$ncdisplay=''; $c='';$s='style="background-color: white"';
  					} 



				$pdf->Cell(20,8,$row[Instrument_SLNO],1,0,'L');
				$pdf->MultiCell(50, 8,$row[Instrument_Description], 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
				$pdf->MultiCell(20, 8,$row[Make], 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
				$pdf->MultiCell(20, 8,$row[Instrument_Range], 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
				$pdf->MultiCell(20, 8,round($row[Least_Count],4), 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
					if($type=='list')
					{
				$pdf->Cell(20,8,$row[Calibration_Frequency].' Months',1,0,'L');
				$pdf->Cell(20,8,$row[Acceptable_Error],1,0,'L');
					}else{
				$pdf->Cell(20,8,$row[Calibration_Date],1,0,'L');
				$pdf->Cell(20,8,$ncd,1,0,'L');
				}

				$pdf->Cell(50,8,$row[Remarks],1,0,'L');
	
		if ($pdf->getY() > $pdf->getPageHeight() - 50) {
        $pdf->rollbackTransaction(true);
        $pdf->AddPage();
		$pdf->setY(18);
		}else{        
        $pdf->ln();   //end of each row
		}



			}

	$pdf->Output('listofinstruments.pdf','F');

}


?>
