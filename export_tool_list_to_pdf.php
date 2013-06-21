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
print($query);



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


$q2="SELECT * FROM Operation WHERE Drawing_ID=$did AND In_Tool_List=1;";

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


$q3="SELECT Ope_Tool_ID,t.Tool_Part_NO,t.Tool_Desc,Insert_ID_1,Insert_ID_2,tb1.Brand_Description as bd1,tb2.Brand_Description as bd2,
		tt.Tool_Part_NO as tpn2,tt.Tool_Desc as tde2,ins2.Insert_Part_NO as ip2,ins2.Insert_Description as id2,
		inse.Insert_Description,
		t.Tool_Dia,Tool_ID_1,Tool_ID_2,Ope_Tool_OH,Ope_Tool_Desc,Holder_Description FROM Ope_Tool AS ot 
		INNER JOIN Tool as t ON t.Tool_ID=ot.Tool_ID_1 
		LEFT OUTER JOIN Inserts AS inse ON inse.Insert_ID=ot.Insert_ID_1
		LEFT OUTER JOIN Inserts AS ins2 ON ins2.Insert_ID=ot.Insert_ID_2
		LEFT OUTER JOIN Tool as tt ON tt.Tool_ID=ot.Tool_ID_2 
		INNER JOIN Tool_Brand as tb1 ON tb1.Brand_ID=t.Brand_ID
		INNER JOIN Tool_Brand as tb2 ON tb2.Brand_ID=tt.Brand_ID
		INNER JOIN Holder AS h ON h.Holder_ID=ot.Holder_ID_1 
		WHERE ot.Operation_ID='$row[Operation_ID]';";

//	print($q3);

//	$q3="SELECT Tool_Part_NO,Tool_Desc,Tool_Dia,Ope_Tool_OH,Ope_Tool_Desc,Holder_Desc FROM Ope_Tools AS ot  
//				INNER JOIN Tool as t ON t.Tool_ID=ot.Tool_ID_1
//				INNER JOIN Holder AS h ON h.Holder_ID=ot.Holder_ID
//				WHERE ot.Operation_ID='$row[Operation_ID]';";
		$rr3=mysql_query($q3) or die(mysql_error());
		
$n=1;
	while($rowi=mysql_fetch_assoc($rr3))
	{
		$pdf->Cell(10,8,$n,1,0,'C');
		$pdf->Cell(135,8,$rowi[Tool_Desc]." (".$rowi[Tool_Part_NO].")",1,0,'L');
		$pdf->Cell(25,8,$rowi[Holder_Description],1,0,'L');
		$pdf->Cell(25,8,$rowi[Ope_Tool_OH],1,0,'C');
		$pdf->Cell(80,8,$rowi[Ope_Tool_Desc],1,1,'L');
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