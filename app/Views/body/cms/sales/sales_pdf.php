<?php
// create new PDF document
$this->load->library('Pdf');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);
		
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Item Vendor Sales Report');

// set default header data
$pdf->SetHeaderData(null, 0, $company." - ".$dept, "Item Vendor Sales Report");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 20, 25);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->SetHeaderMargin(9); 
//$pdf->SetFooterMargin(15); 

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

//SET DATA  TO PRINT
$date1      = $this->concession_model->cDF("m/d/Y",$datefrom);
$date2      = $this->concession_model->cDF("m/d/Y",$dateto);

if($division)
{           
	$pdf->SetFont('helvetica','',10);
	
    $ctr = 0;
    $gt_qty = 0;
    $gt_netsales = 0;
    $gt_vatinput = 0;
    $gt_salescommission      = 0;
    $gt_consignmentpayable   = 0;
    $comm_rate  = "";

	$bodytable ='';	
    foreach($division as $rows):
        
        //$itemsales  = $this->concession_model->get_item_sales_report($vendorcode,$datefrom,$dateto,$rows['item_division'],$compcode);
        $itemsales  = $this->concession_model->get_item_sales_report($rows['item_division'],$sufid,$vendorcode);
        $itemdivname= $this->concession_model->getDivision($rows['item_division']);

        //totals
        $total_qty  = 0;
        $total_netsales = 0;
        $total_vatinput = 0;
        $total_salescommission      = 0;
        $total_consignmentpayable   = 0;		
		
        foreach($itemsales as $row):

            $ctr++;
            $tbl_isr    = "tbl_item_sales_report";
         
            $itemcode   = $row['item_code'];   
            $itemdesc   = $row['item_desc'];
            $qty        = $row['qty'];
            $netsales   = $row['net_sale_with_vat'];

            $comm_rate  = $this->concession_model->getOneField("comm_rate","tbl_items"," item_code = '$itemcode' and vendor_code ='$vendorcode' ")->comm_rate;    
            
            $commission             = $comm_rate / 100;                          
            $salescommission        = $netsales * $commission;
            $consignmentpayable     = $netsales - $salescommission;
            
            $total_qty += $qty;
            $total_netsales += $netsales;
            $total_salescommission += $salescommission;
            $total_consignmentpayable += $consignmentpayable;

            $desc1 = substr($itemdesc, 0,25);
           			
			$bodytable .= '<tr>
					<td> '.$itemcode.' </td>
					<td> '.$desc1.' </td>
					<td align="right"> '.$qty.' </td>
					<td align="right"> '.number_format($netsales,2).'</td>
					<td align="right"> '.$comm_rate."%".' </td>
					<td align="right"> '.number_format($salescommission,2).' </td>
					<td align="right"> '.number_format($consignmentpayable,2).' </td>
				</tr>';
		
        endforeach;
				
				$bodytable .= '
					<tr> <td colspan="7"> </td> </tr>
				<tr>
					<td colspan="2"><b>Total for Item Division '.$rows['item_division'].' - '.$itemdivname.' </b>  </td>					
					<td align="right"> '.$total_qty.' </td>
					<td align="right"> '.number_format($total_netsales,2).'</td>
					<td align="right"> '.$comm_rate."%".' </td>
					<td align="right"> '.number_format($total_salescommission,2).' </td>
					<td align="right"> '.number_format($total_consignmentpayable,2).' </td>
				</tr>
				<tr> <td colspan="7"> </td> </tr>';

               
        $gt_qty += $total_qty; 
        $gt_netsales += $total_netsales;
        $gt_salescommission      += $total_salescommission;
        $gt_consignmentpayable   += $total_consignmentpayable;    
        $concession_sales = $gt_netsales - $gt_vatinput;

    endforeach;

		$table2 = '';
		$table2 = '			
		<table width="100%"  border="1" style="font-size:8px" >
			<tr align="center">
				<td width="45%" colspan="2"></td>
				<td width="12%"><b>Qty</b></td>
				<td width="12%"><b>NetSale w/ VAT</b></td>
				<td width="13%"><b>Commission Rate</b></td>
				<td width="12%"><b>Sales Commission</b></td>
				<td width="14%"><b>Consignment Payable</b></td>
			</tr>';

  			$table2 .= 
				'<tr>
					<td colspan="2">Total for Vendor No. '.$vendorcode." ".$vendorname.' </td>					
					<td align="right"> '.$gt_qty.' </td>
					<td align="right"> '.number_format($gt_netsales,2).'</td>
					<td align="right"> '.$comm_rate."%".' </td>
					<td align="right"> '.number_format($gt_salescommission,2).' </td>
					<td align="right"> '.number_format($gt_consignmentpayable,2).' </td>
				</tr>';
		
			
		$html = '			
			Item Vendor Filter: '.strtoupper($vendorcode).' '.$vendorname.' <br>
			Date Filter: '.$date1.' .. '. $date2.'<br><br>
			
			<table width="100%" border="1" style="font-size:9px;padding:2px">
				<tr align="center">
					<td width="10%"><b>Item Code</b></td>
					<td width="35%"><b>Description</b></td>
					<td width="12%"><b>Qty</b></td>
					<td width="12%"><b>NetSale w/ VAT</b></td>
					<td width="13%"><b>Commission Rate</b></td>
					<td width="12%"><b>Sales Commission</b></td>
					<td width="14%"><b>Consignment Payable</b></td>
				</tr>'.$bodytable.'</table><br><br>'.$table2;
		$pdf->writeHTML($html, true, false, true, false, '');
      
}

    
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('vendor_sales.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+