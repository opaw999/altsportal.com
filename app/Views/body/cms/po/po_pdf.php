<?php
// create new PDF document
$this->load->library('Pdf');
$this->ppdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$this->ppdf->SetCreator(PDF_CREATOR);
$this->ppdf->SetTitle('Purchase Order');

// set default header data
$this->ppdf->SetHeaderData(null, null, $company." - ".$dept, "Purchase Order");
$this->ppdf->setFooterData("TEST"); 

// set header and footer fonts
$this->ppdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$this->ppdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set margins
$this->ppdf->SetMargins(15, 30, 15);
$this->ppdf->SetHeaderMargin(15); //
$this->ppdf->SetFooterMargin(15); //PDF_MARGIN_FOOTER

// set auto page breaks
$this->ppdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$this->ppdf->AddPage();

   
    //SET DATA  TO PRINT
    $this->ppdf->SetFont('Times','',10);
    $this->ppdf->Cell(134,7, "PO No.          : $refno ");    
    $this->ppdf->Cell(45,7, "Transaction Date  : $daterequested"); 
    $this->ppdf->Ln(4);
    $this->ppdf->Cell(100,7, "Supplier        : $vendor ");    
   

    $this->ppdf->Ln(2);

    $this->ppdf->Cell(100,7, "_____________________________________________________________________________________________________"); 
    $this->ppdf->Ln(5);  
    $this->ppdf->SetFont('Times','B',10);
    $this->ppdf->Cell(20,8, "Item Code"); 
    $this->ppdf->Cell(142,8, "Description"); 
    $this->ppdf->Cell(20,8, "PO Qty",0,0,'C');   
    
    $this->ppdf->Ln(1);  
    $this->ppdf->Cell(100,9, "_____________________________________________________________________________________________________"); 
    $this->ppdf->Ln(8);
    
   
    $totpoqty = $totrecqty = $totvar = 0;
    foreach($poitems as $row):

        $po_qty = $this->concession_model->getOneField("qty","tbl_po_requisition_details","ref_no = '$refno' and item_code = '$row[item_code]' and vendor_code='$vcode' ")->qty;
       
        $totpoqty += $po_qty;       
    
        $this->ppdf->SetFont('Times','',10);
        $this->ppdf->Cell(20,8, $row['item_code']); 
        $this->ppdf->Cell(134,8, $row['item_desc']); 
        $this->ppdf->Cell(20,8, $po_qty,0,0,'R' );  
        $this->ppdf->Ln(5); 
        

    endforeach;

    $this->ppdf->Cell(100,8, "_____________________________________________________________________________________________________"); 
    $this->ppdf->SetFont('Times','',10);
    $this->ppdf->Ln(5); 
    $this->ppdf->Cell(161,8, "",0,0,'C'); 
    $this->ppdf->Cell(20,8, $totpoqty,0,0,'C');    
    $this->ppdf->Ln(1);
    $this->ppdf->Cell(100,8, "_____________________________________________________________________________________________________"); 

    $this->ppdf->Ln(6);
    $this->ppdf->SetFont('Times','Item',9);
    $this->ppdf->Cell(125,7, "Note: Please attach Purchase Order with Invoice upon delivery or indicate the  PO number in the Invoice. ");  
    $this->ppdf->Ln(8);

    // $this->ppdf->SetFont('Times','',10);
    // $this->ppdf->Cell(60,7, "Prepared by: ");    
    // $this->ppdf->Cell(60,7, "Approved by: ");    
    // $this->ppdf->Cell(60,7, "Checked by: ");    
    // $this->ppdf->Ln(10);
    // $this->ppdf->Cell(60,7, strtoupper($receivedby),0,0,'C');   
    // $this->ppdf->Cell(60,7, strtoupper($approvedby),0,0,'C');   
    // $this->ppdf->Ln(0);
    // $this->ppdf->Cell(60,8, "________________________________"); 
    // $this->ppdf->Cell(60,8, "________________________________"); 
    // $this->ppdf->Cell(60,8, "________________________________"); 
    // $this->ppdf->Ln(5);
    // $this->ppdf->Cell(60,7, "(Signature Over Printed Name)",0,0,'C');
    // $this->ppdf->Cell(60,7, "(Signature Over Printed Name)",0,0,'C');    
    // $this->ppdf->Cell(60,7, "(Signature Over Printed Name)",0,0,'C');    
    // $this->ppdf->Ln();
    
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$this->ppdf->Output('po_pdf.php', 'I');
//============================================================+
// END OF FILE
//============================================================+