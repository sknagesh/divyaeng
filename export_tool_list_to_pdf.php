<?php
error_reporting(E_ERROR|E_PARSE);
include('dewdb.inc');
require_once('../tcpdf/tcpdf.php');


//print_r($_POST);
$did=$_GET['Drawing_ID'];
//print($did);
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT * FROM Component WHERE Drawing_ID=$did;";
//print($query);



$r=mysql_query($query) or die(mysql_error());
$rope=mysql_fetch_assoc($r);

$Drawing_NO=$rope['Drawing_NO'];
$Component_Name=$rope['Component_Name'];
$revno=$rope['Drawing_Rev'];

class PDF_SKN extends TCPDF {

// Page header
	function Header()
	{
		$partno=$GLOBALS['Drawing_NO'];
		$partname=$GLOBALS['Component_Name'];
		$revno=$GLOBALS['revno'];   
    
    	$this->SetFont('helvetica','',16);
//		$this->MultiCell(100,18, 'Divya Engineering Works (P) Ltd', 1, 'C', 0, 0, '', '', true,0,false,true,8,'M',true);
    	$this->Cell(100,18,'Divya Engineering Works (P) Ltd',1,0,'C');
		$this->Cell(100,18,'CNC Machining Tool List',1,0,'C');
		$this->SetFont('helvetica','', 10);
		$this->Cell(75,6,'Doc. Ref: '.'DEW/PRD/D/03','T R',2,'L');
		$this->Cell(75,6,'REV NO: 00','T B R',2,'L');
		$this->Cell(75,6,'DATE: 01-06-2003','R',0,'L');
		$this->ln();
    	$this->Cell(200,6,'Part Name: '.$partname,'L T R',0,'L');
    	$this->Cell(75,6,'Drg No '.$partno.' Rev No: '.$revno,'T R',1,'L');
		$this->Cell(200,6,'Customer Name: ','L B R',0,'L');
    	$this->Cell(40,6,'Date: '.date('d-m-Y'),'T B R',0,'L');
		$this->Cell(35,6,'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(),'T B R',1,'C');	


	}

	function Footer()
	{
    	// Position at 1.5 cm from bottom
    	$this->SetY(-35);
		// Arial italic 8
    	$this->SetFont('helvetica','',16);
		$this->Cell(220,10,"Prepared By: S.K.N ",'0',0,'L');
		$this->Cell(140,10,"Approved By: M.N.V",'0',1,'L');
	
	    // Page number
    	$this->SetY(-10);
    	$this->SetFont('helvetica','',6);

		}
}




$pdf = new PDF_SKN(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->AliasNbPages();
$pdf->setAutoPageBreak(1,35);
$pdf->AddPage('L','A4');
$pdf->SetFont('helvetica','',10);


$q2="SELECT * FROM Operation WHERE Drawing_ID=$did AND In_Tool_List=1 ORDER BY Operation_Desc ASC;";

$rr=mysql_query($q2) or die(mysql_error());
$noofop=mysql_affected_rows();
$pn=0;
while($row=mysql_fetch_assoc($rr))
{
$pdf->SetY(30);
$pdf->SetFont('helvetica','B',12);
$pdf->Cell(275,8,'Operation Description '. $row['Operation_Desc']."  Program No: ".$row['Program_NO'],1,1,'C');
$pdf->SetFont('helvetica','B',9);
$pdf->Cell(10,8,'SL No',1,0,'C');
$pdf->Cell(135,8,'Tool Part No and Description',1,0,'L');
$pdf->Cell(25,8,'Tool Holder',1,0,'L');
$pdf->Cell(25,8,'Tool Overhang',1,0,'C');
$pdf->Cell(80,8,'Work Description',1,1,'L');
$pdf->SetFont('helvetica','',10);


$q3='SELECT Ope_Tool_ID,Ope_Tool_OH,Ope_Tool_Desc,Tool_ID_1,Ope_Tool_Image_Path,Storage_Location,
		(SELECT CONCAT(Tool_Desc," ( ",Tool_Part_NO," )") FROM Tool WHERE Tool_ID=ot.Tool_ID_1) as td1, 
		(SELECT CONCAT(Tool_Desc," ( ",Tool_Part_NO," )") FROM Tool WHERE Tool_ID=ot.Tool_ID_2)as td2, 
		(SELECT CONCAT(Insert_Part_NO," ",Insert_Description) FROM Inserts WHERE Insert_ID=ot.Insert_ID_1) as i1,
		(SELECT CONCAT(Insert_Part_NO," ",Insert_Description) FROM Inserts WHERE Insert_ID=ot.Insert_ID_2) as i2,
		(SELECT Brand_Description FROM Tool_Brand as tb INNER JOIN Tool as t1 ON t1.Brand_ID=tb.Brand_ID WHERE t1.Tool_ID=ot.Tool_ID_1) as tb1,
		(SELECT Brand_Description FROM Tool_Brand as tb2 INNER JOIN Tool as t2 ON t2.Brand_ID=tb2.Brand_ID WHERE t2.Tool_ID=ot.Tool_ID_2) as tb2, 
		(SELECT Holder_Description FROM Holder as h1 WHERE Holder_ID=ot.Holder_ID_1) as hd1,
		(SELECT Holder_Description FROM Holder as h2 WHERE Holder_ID=ot.Holder_ID_2) as hd2
		FROM Ope_Tool AS ot WHERE ot.Operation_ID="'.$row[Operation_ID].'";';

		$rr3=mysql_query($q3) or die(mysql_error());
		
$n=1;
	while($rowi=mysql_fetch_assoc($rr3))
	{
		$pdf->Cell(10,8,$n,1,0,'C');
		$pdf->Cell(135,8,$rowi[td1],1,0,'L');
		$pdf->MultiCell(25, 8, $rowi[hd1], 1, 'L', 0, 0, '', '', true,0,false,true,8,'M',true);
		$pdf->Cell(25,8,$rowi[Ope_Tool_OH],1,0,'C');
		$pdf->MultiCell(80, 8, $rowi[Ope_Tool_Desc], 1, 'L', 0, 1, '', '', true,0,false,true,8,'M',true);
//		$pdf->Cell(80,8,$rowi[Ope_Tool_Desc],1,1,'L');

		if (($pdf->getY()) > ($pdf->getPageHeight()-46.5))   //IF TOOL LIST EXCEEDS ONE PAGE FOR THIS OPERATION
		{
        $pdf->rollbackTransaction(true);
        $pdf->AddPage();
		$pdf->SetY(30);
		$pdf->SetFont('helvetica','B',12);
		$pdf->Cell(275,8,'Operation Description '. $row['Operation_Desc']."  Program No: ".$row['Program_NO'],1,1,'C');
		$pdf->SetFont('helvetica','B',9);
		$pdf->Cell(10,8,'SL No',1,0,'C');
		$pdf->Cell(135,8,'Tool Part No and Description',1,0,'L');
		$pdf->Cell(25,8,'Tool Holder',1,0,'L');
		$pdf->Cell(25,8,'Tool Overhang',1,0,'C');
		$pdf->Cell(80,8,'Work Description',1,1,'L');
		$pdf->SetFont('helvetica','',10);


		}
	$n+=1;
		
	}

		$pn+=1;
		if($pn!=$noofop)
			{
				$pdf->AddPage('L');	
			}

	
	
	
}


		$name="pdf/$Drawing_NO"."_Tool_List.pdf";
		ob_clean();
		$pdf->Output($name,'F');
	
	print("<a href=\"$name\" target=\"_NEW\" title=\"Opens Tool List PDF File TAB\">Tool List in PDF</a>");
		
?>