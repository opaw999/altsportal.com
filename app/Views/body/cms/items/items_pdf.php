<?php
// create new PDF document
$this->load->library('Pdf');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);
		
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Items Report');

// set default header data
$pdf->SetHeaderData(null, 0, " ", "Items Report");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(25, 25, 25);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->SetHeaderMargin(9); 
//$pdf->SetFooterMargin(15); 

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();
    
    $vendorcode = $item->vendor_code;	
    $vendorname = $this->concession_model->getVendorName($item->vendor_code);
          
	$pdf->SetFont('helvetica','',10);
	$bodytable  ='';	
	$no         = "";
    foreach($items as $row)
	{					
	    $no++;
	    $bodytable .=
    	"<tr>
    	    <td> $no </td>
    		<td> $row[item_code] </td>
    		<td> $row[ext_desc] </td>
    	</tr>";
	}

	$html = '			
		Item Vendor Filter: '.strtoupper($vendorcode).' '.$vendorname.' <br>
		Date Generate: '.date('M d, Y').'<br><br>
		
		<table width="100%" border="1" style="font-size:9px;padding:2px">
			<tr align="center">
			    <td width="10%"><b>No</b></td>
				<td width="20%"><b>Item Code</b></td>
				<td width="70%"><b>Description</b></td>
			</tr>
			'.$bodytable.'</table>';
	$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('items.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+