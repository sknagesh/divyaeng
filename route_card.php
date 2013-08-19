<?php
include('dewdb.inc');
require_once('../tcpdf/tcpdf.php');
require ("php-excel.class.php");

//print_r($_POST);


$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$batchid=$_POST['Batch_ID'];
$pdfxl=$_POST['pdfxl'];
$drawingid=$_POST['Drawing_ID'];

if(isSet($_POST['rdate'])){$rdate=$_POST['rdate'];}else{$rdate=date('D/M/Y');}




$j="SELECT Component_Name, Customer_Name, Drawing_NO FROM Component as comp 
			INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID WHERE Drawing_ID='$drawingid';";
	$rr = mysql_query($j, $cxn) or die(mysql_error($cxn));
while($rrs=mysql_fetch_assoc($rr))
{
	$cname=$rrs['Component_Name'];
	$partno=$rrs['Drawing_NO'];
	$custname=$rrs['Customer_Name'];
}


$kj="SELECT Heat_Code,Mfg_Batch_NO,Material_Code,Batch_Remarks FROM Batch_NO as bn 
		INNER JOIN BNo_MI_Challans as bmc ON bmc.Batch_ID=bn.Batch_ID 
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID  
		WHERE bn.Batch_ID='$batchid';";
$krr = mysql_query($kj, $cxn) or die(mysql_error($cxn));
while($krrs=mysql_fetch_assoc($krr))
{
	$heatcode=$krrs['Heat_Code'];
	$materialcode=$krrs['Material_Code'];
	$batchdesc=$krrs['Mfg_Batch_NO'];
	$batchremark=$krrs['Batch_Remarks'];
}

$qchalan="SELECT Ex_Challan_NO,DATE_FORMAT(Ex_Challan_Date,'%d/%m/%Y') as ecd,
			Purchase_Ref,DATE_FORMAT(Purchase_Ref_Date,'%d/%m/%Y') as prd,Qty_In_Batch FROM BNo_MI_Challans as bmc
		INNER JOIN MI_Drg_Qty as mdq ON mdq.MI_Drg_Qty_ID=bmc.MI_Drg_Qty_ID
		INNER JOIN Material_Inward as mi ON mi.Material_Inward_ID=mdq.Material_Inward_ID
		WHERE bmc.Batch_ID='$batchid';";

$rqchallan = mysql_query($qchalan, $cxn) or die(mysql_error($cxn));
$challanno='';
$bqty=0;
while($rchallan=mysql_fetch_assoc($rqchallan))
{
		if($rchallan['Ex_Challan_NO']!=''){$cno=$rchallan['Ex_Challan_NO'];}else{$cno=$rchallan['Purchase_Ref'];}
		if($rchallan['ecd']!='00/00/0000'){$cdt=$rchallan['ecd'];}else{$cdt=$rchallan['prd'];}
	$challanno.=$cno.' dt. '.$cdt.' Qty: '.$rchallan['Qty_In_Batch'].' ' ;
	$bqty+=$rchallan['Qty_In_Batch'];
	}


$y=0;



class PDF_SKN extends TCPDF {
// Page header
function Header()
{
	$cname=$GLOBALS['cname'];
	$custname=$GLOBALS['custname'];
	$partno=$GLOBALS['partno'];
	$heatcode=$GLOBALS['heatcode'];
	$materialcode=$GLOBALS['materialcode'];
    $rdate=$GLOBALS['rdate'];
    $batchdesc=$GLOBALS['batchdesc'];
	$challanno=$GLOBALS['challanno'];
   	$bqty=$GLOBALS['bqty'];
	$cdate=$GLOBALS['cdt'];
	$hcode=$GLOBALS['heatcode'];
	$bremarks=$GLOBALS['batchremark'];
	$y=$GLOBALS['y'];
    $this->SetFont('helvetica','',12);
    $this->MultiCell(70, 12, 'Divya Engineering Works (P) Ltd, Mysore', 1, 'L', 0, 0, '', '', true,0,false,true,8,'M',true);
	$this->Cell(70,12,"Route Card",1,0,'C');
	$this->SetFont('helvetica','', 10);
	$this->Cell(58,6,'RECORD REF: DEW/PRD/R/16',1,2,'L');
	$this->Cell(35,6,'DATE: 01-08-2012',1,0,'L');
	$this->Cell(23,6,'REV NO: 00',1,1,'L');
    $this->Cell(20,6,'Customer: ',1,0,'L');
	$this->Cell(50,6,$custname,1,0,'L');
    $this->Cell(128,6,'Material Stock NO: '.$materialcode,'1',1,'L');
	$this->Cell(70,6,'Part: '.$cname,1,0,'L');
	$this->MultiCell(70, 6, 'Drg No/Rev No: '.$partno, 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);				
//    $this->Cell(70,6,'Drg No/Rev No: '.$partno,1,0,'L');
    $this->Cell(58,6,'DATE Material Received: '.$cdate,1,1,'L');
    $this->Cell(70,6,'P.O NO & Date: ',1,0,'L');
    $this->Cell(128,6,'DEW Batch NO: '.$batchdesc. '   Batch Qty: '.$bqty,1,1,'L');
    $this->MultiCell(198,6,'D.C/Challan : '.$challanno, 1, 'L', 0, 1, '', '', true,0,false,true,8,'M',true);
	$this->MultiCell(99,6,'Heat Code & Qty: '.$hcode, 1, 'L', 0, 0, '', '', true,0,false,true,8,'M',true);
	$this->MultiCell(99,6,'Job Serial Nos: '.$bremarks, 1, 'L', 0, 1, '', '', true,0,false,true,8,'M',true);
	}

function Footer()
{
    // Position at 1.5 cm from bottom
//    $this->SetY(-35);
	// Arial italic 8
    $this->SetFont('helvetica','',16);
//	$this->Cell(220,10,"Inspected By: G.S ",'0',0,'L');
//	$this->Cell(140,10,"Approved By: U.S",'0',1,'L');
	
    // Page number
    $this->SetY(-10);
    $this->SetFont('helvetica','',6);
	$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
}


$pdf = new PDF_SKN(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->AliasNbPages();
$pdf->setAutoPageBreak(1,35);
$pdf->SetMargins(5,42,10,29);
$pdf->AddPage('P','A4');
$pdf->SetFont('helvetica','',10);


///top headings
$pdf->Cell(15,7,'No',1,0,'L');
$pdf->Cell(45,7,'Operation Desc',1,0,'L');
$pdf->MultiCell(55,7,'Place/Ref Doc/Name Of Supervisor', 1, 'L', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(35,7,'       Quantity         Accepeted   Rejected', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(14,7,'Start Date', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(16,7,'Completed Date', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->Cell(18,7,'Remarks',1,1,'L');
/*
//incomming
$pdf->Cell(15,7,'',1,0,'L');
$pdf->Cell(45,7,'Incomming',1,0,'L');
$pdf->MultiCell(55,7,'', 1, 'L', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(35,7,'', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(14,7,'', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(16,7,'', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->Cell(18,7,'',1,1,'L');
$pdf->Cell(15,7,'',1,0,'L');
$pdf->Cell(45,7,'',1,0,'L');
$pdf->MultiCell(55,7,'', 1, 'L', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(35,7,'', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(14,7,'', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->MultiCell(16,7,'', 1, 'C', 0, 0, '', '', true,0,false,true,7,'M',true);
$pdf->Cell(18,7,'',1,1,'L');
*/


$query="SELECT * FROM Operation WHERE Drawing_ID='$drawingid' ORDER BY Operation_Desc ASC;";

$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
while($row=mysql_fetch_assoc($res))
{
			
if(strpos($row['Operation_Desc'],'CMM'))
{

	$o=explode(':', $row['Operation_Desc']);
$odesc=$o[1];
$p='';
}else{
	$odesc=$row['Operation_Desc'];
	$p='Process Sheet, Activity Log';
}

$pdf->Cell(15,6,'',1,0,'L');
$pdf->MultiCell(45, 6, $odesc, 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);				
$pdf->MultiCell(55,6,$p, 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
$pdf->MultiCell(35,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
$pdf->MultiCell(14,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
$pdf->MultiCell(16,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
$pdf->Cell(18,6,'',1,1,'L');
	for($c=0;$c<=2;$c++)
		{
		$pdf->Cell(15,6,'',1,0,'L');
		$pdf->Cell(45,6,'',1,0,'L');
		$pdf->MultiCell(55,6,'', 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'',1,1,'L');
		}
if(!strpos($row['Operation_Desc'],'CMM'))
{
$in="Inspection";
$ir="Inprocess Inspection Report";
	for($c=0;$c<=2;$c++)
		{
		$pdf->Cell(15,6,'',1,0,'L');
		$pdf->Cell(45,6,$in,1,0,'L');
		$pdf->MultiCell(55,6,$ir, 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'',1,1,'L');
		$in='';
		$ir='';
		}
}

}


		$pdf->Cell(15,6,'',1,0,'L');
		$pdf->Cell(45,6,'Fianl Inspection',1,0,'L');
		$pdf->MultiCell(55,6,'', 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'',1,1,'L');

		$pdf->Cell(15,6,'',1,0,'L');
		$pdf->Cell(45,6,'',1,0,'L');
		$pdf->MultiCell(55,6,'', 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'',1,1,'L');


		$pdf->Cell(15,6,'',1,0,'L');
		$pdf->Cell(45,6,'Despatch Details',1,0,'L');
		$pdf->MultiCell(55,6,'', 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'',1,1,'L');

		$pdf->Cell(15,6,'',1,0,'L');
		$pdf->Cell(45,6,'Customer Lot No',1,0,'L');
		$pdf->MultiCell(55,6,'Quantity Despatched', 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'Balance Qty', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'Date', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'DC No', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'Remarks',1,1,'L');

		for($d=0;$d<=3;$d++){
		$pdf->Cell(15,6,'',1,0,'L');
		$pdf->Cell(45,6,'',1,0,'L');
		$pdf->MultiCell(55,6,'', 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'',1,1,'L');
		}

		$pdf->Cell(198,6,'Major Deviations/Rejection Data',1,1,'C');


		$pdf->Cell(15,6,'Opn No',1,0,'L');
		$pdf->Cell(45,6,'Quantity Rejected',1,0,'L');
		$pdf->MultiCell(55,6,'Machine', 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'Fixture', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'Tool', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'Operator', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'Other',1,1,'L');

	for($e=0;$e<=5;$e++)
	{
		$pdf->Cell(15,6,'',1,0,'L');
		$pdf->Cell(45,6,'',1,0,'L');
		$pdf->MultiCell(55,6,'', 1, 'L', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(35,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(14,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->MultiCell(16,6,'', 1, 'C', 0, 0, '', '', true,0,false,true,6,'M',true);
		$pdf->Cell(18,6,'',1,1,'L');
	}
		$pdf->Cell(198,6,'Customer Feed Back Data',1,1,'C');

		$pdf->Cell(198,40,'',1,1,'C');

$name=$partno.'.pdf';






if(!$pdfxl)
{
//		$name="temp.pdf";
//		header("Content-type: application/pdf");
//		header("Content-Disposition: attachment; filename=$name");
		$pdf->Output($name,'I');
}else{
$xls = new Excel_XML;
$xls->addArray ( $xldata );
$xls->generateXML ($opid);
}
 
?>