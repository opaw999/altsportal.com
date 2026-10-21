<?php
namespace App\Controllers;

class Concession extends BaseController
{	
	public function show_cas()
	{		
		$casid = $this->request->getGet('casid');	
		$conditions = [ 'cas_id' => $casid ];
		$cas_det 	= $this->concession_model->getfrmtblwhere('tbl_cas', $conditions);
			
		$location 	= $this->concession_model->getstores_concat($cas_det['vendor_code']);
		$terms 		= $this->concession_model->getstore_terms($cas_det['vendor_code']);

		$newterms 	= "";
		foreach($terms as $row){
			$newterms .= $row['aname']."-".$row['terms'].", ";
		}

		?>
		<center> <h4>(Per Concessionaire Agreement No.<?= $cas_det['cas_id'];?> ) </h4> 
		Date: <?= $cas_det['dated'];?>
		</center> <br>
		<table class="table-bordered" cellpadding="10">
			<tr>
				<td align="right"> <b> Vendor Code </b> </td>
				<td> <?= $cas_det['vendor_code'];?>  </td>
			</tr>
			<tr>
				<td align="right"> <b> Brand </b> </td>
				<td> <?= $cas_det['brand'];?>  </td>
			</tr>
			<tr>
				<td align="right"> <b>Concessionaire </b></td>
				<td> <?= $cas_det['brand'];?> </td>
			</tr>
			<tr>
				<td align="right"> <b>Period Covered</b> </td>
				<td> <?=  $cas_det['period_end']." .. ".$cas_det['period_end'];?> </td>
			</tr>
			<tr>
				<td align="right"> <b>Merchandise Category</b> </td>
				<td> <?= $cas_det['merchandise_category'];?> </td>
			</tr>
			<tr>
				<td align="right"> <b>Location</b> </td>
				<td> <?= @$location;?> </td>
			</tr>
			<tr>
				<td align="right"> <b>Area Covered</b> </td>
				<td> <?= $cas_det['area_covered'];?>  </td>
			</tr>
			<tr>
				<td align="right"> <b>Freight Charges </b></td>
				<td> 100% chargeable to Concessionaire including stocks/merchandise returned or pulled - out from the store </td>
			</tr>
			<tr>
				<td align="right"> <b>Monthly Utilities/Rental Expense Charge</b> </td>
				<td> <?= $cas_det['monthly_utilities'];?> </td>
			</tr>
			<tr>
				<td align="right"> <b>Monthly Store Supply Charge </b></td>
				<td> <?= $cas_det['monthly_supply_charge'];?> </td>
			</tr>
			<tr>
				<td align="right" > <b>Minimum Monthly Gross Sales Requirement </b></td>
				<td> N/A </td>
			</tr>
			<tr>
				<td align="right"> <b>Commission Percentage </b></td>
				<td> <?= $cas_det['monthly_supply_charge'];?> </td>
			</tr>
			<tr>
				<td align="right"> <b>Additional Sales Force  </b></td>
				<td> <?= $cas_det['salesforce_peak'];?> </td>
			</tr>
			<tr>
				<td align="right"> <b>Sales Force on Normal Operations </b></td>
				<td> <?= $cas_det['salesforce_normal'];?> </td>
			</tr>
			<tr>
				<td align="right"><b> Sales Cut-off Period</b> </td>
				<td> <?= substr_replace($newterms," ", -1);?> </td>
			</tr>
			<tr>
				<td align="right"><b> Check Issuance </b></td>
				<td> every 20<sup>th</sup> and 5<sup>th</sup> day of the month </td>
			</tr>
		</table> 
		<?php
	}

	public function view_vis()
	{
	    $vcode = $this->request->getGet('vcode');	
		$conditions = [ 'vendor_code' => $vcode ];
	    $vis_tbl 	= $this->concession_model->getfrmtblwhere('tbl_vis', $conditions);		
	    ?>
	   <table class="table">
			<tbody>
				<tr class="highlight">
					<td colspan="3"><h4><i class="fab fa-gitlab fa-fw text-primary"></i> Company Profile [<?= $vcode;?>]  </h4> </td>
				</tr>
				<tr>
					<td width="3%"> &nbsp; </td>
					<th width="20%"> <div style="text-align: right"> Structure </div>  </th>
					<td> <?= @$vis_tbl['structure'];?> </td>
				</tr>									
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Business Name </div>  </th>
					<td> <?= @$vis_tbl['business_name'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Business Address </div>  </th>
					<td> <?= @$vis_tbl['business_address'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Business Ownership </div>  </th>
					<td> <?= @$vis_tbl['business_ownership'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Date Established </div>  </th>
					<td> <?= @$vis_tbl['date_established'];?> </td>
				</tr>
				
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Years in Operation </div>  </th>
					<td> <?= @$vis_tbl['years_operation'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Nature of Business </div>  </th>
					<td> <?= @$vis_tbl['nature_of_business'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> TIN No.</th>
					<td> <?= @$vis_tbl['tin'];?> </td>
				</tr>
				<tr class="highlight">
					<td colspan="3">
						<h4><i class="fab fa-gitlab fa-fw text-primary"></i> Contact Details & Business Registration Numbers </h4> 
					</td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Telephone Number </div> </th>
					<td> <?= @$vis_tbl['tel_no'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right">  Mobile Number </div> </th>
					<td> <?= @$vis_tbl['mobile_no'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Fax Number </div> </th>
					<td> <?= @$vis_tblp['fax_no'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Email Address </div> </th>
					<td>  <?= @$vis_tbl['email_add'];?></td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> DTI Registration No. </div> </th>
					<td>  <?= @$vis_tbl['dti_regno'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right">  VAT Registration No. </div> </th>
					<td>  <?= @$vis_tbl['vat_regno'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Municipal License No.</div> </th>
					<td>  <?= @$vis_tbl['mun_licenseno'];?>  </td>
				</tr>
				<tr class="highlight">
					<td colspan="3"> <h4><i class="fab fa-gitlab fa-fw text-primary"></i> Product Details </h4> </td>
				</tr>								
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Product Line </div> </th>
					<td> <?= @$vis_tbl['product_line'];?> </td>
				</tr>		
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Discounts  </div> </th>
					<td> <?= @$vis_tbl['regular_disc'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Trading Terms  </div> </th>
					<td> <?= @$vis_tbl['trading_terms'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> PO Transmittal  </div>  </th>
					<td> <?= @$vis_tbl['po_transmittal'];?>  </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Shipment Details  </div>  </th>
					<td> <?= @$vis_tbl['shipment_details'];?> </td>   
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Freight Subsidy  </div> </th>
					<td> <?= @$vis_tbl['freight_subsidy'];?> </td>
				</tr>										
		    	<tr class="highlight">
					<td colspan="3">  <h4><i class="fab fa-gitlab fa-fw text-primary"></i> For Single Proprietorship </h4>   </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Name </div> </th>
					<td> <?= @$vis_tbl['sole_name'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> Address </div> </th>
					<td> <?= @$vis_tbl['sole_address'];?> </td>
				</tr>
				<tr>
					<td> &nbsp; </td>
					<th> <div style="text-align: right"> TIN No. </div> </th>
					<td> <?= @$vis_tbl['sole_tin'];?> </td>
				</tr>				
			</tbody>
		</table> 
	    <?php
	}

	public function view_items()
	{
		$vcode = $this->request->getGet('vcode');	
		$conditions = [ 'vendor_code' => $vcode ];		
		$items = $this->concession_model->getalltblwhere("tbl_items", $conditions);
		?> Vendor Code : <?= $vcode;?>
		
		<table id="itemDTable" class="table table-bordered table-sm dt-responsive nowrap" style='font-size:13px'>
			<thead>
				<tr>
					<th class="text-nowrap"> Code </th>
					<th class="text-nowrap"> Description </th>
					<th class="text-nowrap"> Comm Rate </th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($items as $row)
			{														
				echo "
				<tr>
					<td> $row[item_code] </td>
					<td> $row[ext_desc] </td>
					<td> $row[comm_rate]% </td>
				</tr>";
			} ?>
			</tbody>
		</table>
		<script type="text/javascript">
        	$(document).ready(function() {
        		var dataTable = $('#itemDTable').DataTable({});
        	});
        </script>
		<?php
	}
	
	public function view_cas()
	{   
	    $vcode = $this->request->getGet('vcode');	
		$conditions = [ 'vendor_code' => $vcode ];		

	    $cas_tbl = $this->concession_model->getalltblwhere('tbl_cas', $conditions);
		
	    ?>
	    <table class="table table-striped table-bordered table-hover" width="100%">
  			<thead>			                
  				<tr>
  					<th> CAS No. </th>
  					<th> Vendor Code </th>
  					<th><i class="fa fa-fw fa-calendar hidden-md hidden-sm hidden-xs"></i> Date </th>
  					<th> Brand </th>
  					<th> Merchandise </th>
					<th> Commission Rate </th>
  					<th><i class="fa fa-fw fa-calendar hidden-md hidden-sm hidden-xs"></i> Period Start </th>
  					<th><i class="fa fa-fw fa-calendar hidden-md hidden-sm hidden-xs"></i> Period End </th>
  					<th> Action</th>
  				</tr>
  			</thead>
  			<tbody>	
				<?php foreach($cas_tbl as $row): ?>
  				<tr>
  					<td> <?= $row['cas_id'];?> </td>
  					<td> <?= $row['vendor_code'];?> </td>
					<td> <?= $row['dated'];?> </td>
  					<td> <?= $row['brand'];?> </td>
  					<td> <?= $row['merchandise_category'];?> </td>
  					<td> <?= $row['commission_percentage'];?> </td>
  					<td> <?= $row['period_start'];?> </td>
  					<td> <?= $row['period_end'];?> </td>
  					<td> <a href='#show-agreement' data-toggle='modal' title="View Details"  onclick="show_cas('<?= $row['cas_id'];?>')"> <i class="fa fa-fw fa-list txt-color-blue hidden-md"></i> </a>  
					</td>
  				</tr>
				<?php endforeach;?>	
  			</tbody>
  		</table>

		<!-- #modal-dialog -->
		<div class="modal fade" id="show-agreement">
			<div class="modal-dialog modal-lg" style="width: 70%; max-width: 100%;">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"> Concessionaire Agreement Summary Details </h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body show-cas" id='casdetails'>				
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
				</div>
				</div>
			</div>
		</div>

	    <?php  
	}

	public function view_deductions()
	{
		$vcode = $this->request->getGet('vcode');	
		$deductions = @$this->concession_model->get_deductions($vcode);
		?>
  	 	<table id="deDTable" width="100%" class="table table-bordered table-sm dt-responsive nowrap" style='font-size:13px'>
  	 		<thead>	
  	 			<tr>
  	 				<th> Doc No. </th>
  	 				<th> Vendor </th>
	 				<th> Store </th>
	 				<th> Posting Date </th>
	 				<th> Deduction Type </th>
	 				<th> Details </th>
	 				<th> Amount </th>
  	 			</tr>
  	 		</thead>
  	 		<tbody>	 
	 			<?php 
				if(!empty($deductions)){
    				foreach($deductions as $row):    				
    					if($row['status'] == 'Downloaded'){
							$label    = "<span class='badge badge-success' id='status_$row[ded_no]'> $row[status] </span>";
						}else if($row['status'] == 'Paid'){
							$label    = "<span class='badge badge-warning' id='status_$row[ded_no]'> $row[status] </span>";									
						}
							
						echo "	
						<tr>	
							<td> ".$row['doc_no']." </td>
							<td> ".$row['vendor_code']." </td>
							<td> ".$row['name']." </td>
							<td class='text-center'> ".$row['posting_date']." </td>
							<td> ".$row['description']." </td>
							<td> ".$row['deduction_details']." </td>
							<td align='right'> ".number_format($row['amount'],2)." </td>
						</tr>";    				
    				endforeach;
				}?>
  			</tbody>
  		</table>     
  		<script type="text/javascript">
        	$(document).ready(function() {
        		var dataTable = $('#deDTable').DataTable({});
        	});
        </script> <?php
	}

	public function view_po()
	{
		$vcode = $this->request->getGet('vcode');	
		$po    = @$this->concession_model->get_po($vcode);
		$columns    = array("PO #","Vendor Code","Store","Department","Date Requested","Status","Print","Received","View"); ?>

    	<table class="table table-striped table-bordered table-hover" id='poDtable' width="100%" style='font-size:13px'>
  			<thead>	
				<tr> <?php
					foreach($columns as $key => $value):
						echo "<th> $value </th>";
					endforeach; ?>
				</tr>
			</thead>
  			<tbody>	
				<?php 
				if(!empty($po)){
				foreach($po as $row):
																						
					$refno = trim($row['ref_no']);
					if($row['received_status'] == 'RECEIVED'){	
						$rlabel = "<span class='badge badge-success'>$receivedstat</span>"; 
					} else{	
						$rlabel = "";	
					}
											
					if($row['received_status'] !=""){ 
						$view="<a href='#show-receiveditems' data-toggle='modal' onclick=show_received('$refno')> View </a>";}else{ $view=""; }	
					
					echo "	
					<tr>	
						<td> ".$row['ref_no']." </td>
						<td> ".$row['vendor_code']." </td>
						<td> ".$row['name']." </td>
						<td> ".$row['dept_name']." </td>
						<td class='text-center'> ".$row['date_requested']." </td>
						<td> ".$row['status']." </td>";?>
						<td> <a href='<?php echo base_url('vendor_po_pdf/'.$row['ref_no']); ?>') target='_blank'> PDF </a> </td> <?php echo "
						<td> $rlabel </td>
						<td> $view </td>
					</tr>";
				endforeach;
				}?>
			</tbody>
  		</table>
  		
  	 	<script type="text/javascript">
         	$(document).ready(function() {
        		var dataTable = $('#poDtable').DataTable({});
         	});
         </script> <?php
	}

	public function view_sales()
	{
		$vcode = $this->request->getGet('vcode');
		$sales = $this->concession_model->get_sales($vcode); 
		$columns = array("#","CUTOFF","STORE/DEPT","NET SALE","SALES<br>COMMISSION","CONSIGNMENT<br>PAYABLE","STATUS","DATE POSTED","ACTION"); ?>
	    Vendor Code : <?= $vcode;?>
		<table class="table table-striped table-bordered table-sm table-hover" id='salesDtable' width="100%" style='font-size:13px'>
  	 		<thead>	
				<tr> <?php
					foreach($columns as $key => $value):
						echo "<th> $value </th>";
					endforeach; ?>
				</tr>
			</thead>  
  	 		<tbody>	
	 		<?php 
			if(@$sales)
			{
				$i=0; 
				foreach($sales as $row): 
					$i++; ?>
					<tr>
						<td> <?= $i;?></td>
						<td> <?= $row['p_start'];?> .. <?= $row['p_end'];?> </td>
						<td> <?= $row['shortcut'];?> .. <?= $row['dept_name'];?> </td>
						<td> <?php echo number_format($row['net_sale'],2);?> </td>
						<td> <?php echo number_format($row['sales_commission'],2);?> </td>
						<td> <?php echo number_format($row['consignment_payable'],2);?> </td>
						<td> <?= $row['status']?> </td>
						<td class='text-center'> <?= $row['date_posted'];?> </td>	
						<td class='text-center'> <a href='<?php echo base_url('vendor_sales_pdf/'.$row['iss_no']); ?>') target='_blank' title='Export in PDF'> PDF </a> </td>
					</tr>		  				
				<?php endforeach; 
			} ?>
  			</tbody>
  		</table>
  		
  		<script type="text/javascript">
        	$(document).ready(function() {
        		var dataTable = $('#salesDtable').DataTable({});
        	});
        </script> <?php
	}

	public function view_check()
	{
		$vcode = $this->request->getGet('vcode');
		$check = $this->concession_model->get_check($vcode);
	    ?>
		
  	 	<table id='checkDTable' class="table table-striped table-sm table-bordered table-hover" style='font-size:13px' width="100%">
  	 		<thead>			                
  	 			<tr>
					<th>Vendor Code</th>
	 				<th>CV No.</th>
	 				<th>Check No.</th>
	 				<th>Check Date</th>
	 				<th>Check Amount</th>
	 				<th>Bank</th>
	 				<th>Status</th>
	 				<th>Date Released</th>
	 			</tr> 
  	 		</thead>
  	 		<tbody>	 
  	 			<?php
				$status = "";
				foreach($check as $row):	
					echo "
					<tr>
						<td> $row[vendor_code]  </td>	
						<td> $row[cv_no]  </td>	
						<td> ".$row['check_no']." </td>
						<td> ".$row['check_date']." </td>
						<td align='right'> ".number_format($row['total_paid_amt'],2)." </td>
						<td> ".$row['bank_name']." </td>
						<td> ".$row['check_status']." </td>
						<td> ".$row['check_release_date']." </td>	
					</tr>";
				endforeach;
				?>			  				
  			</tbody>
  		</table> 
  		<script type="text/javascript">
        	$(document).ready(function() {
        		var dataTable = $('#checkDTable').DataTable({});
        	});
        </script> <?php
	}

	public function items_pdf($vcode)
    {        
		//QUERY DATA      
		$conditions = [ 'vendor_code' => $vcode ];
		$items = $this->concession_model->getalltblwhere("tbl_items", $conditions); 
		$vendorname = $this->concession_model->getVendorName($vcode);
		
		//DISPLAY PDF VALUE
        $html = '<style>
                    .violation {
                        font-family: "opensans", sans-serif;
                        font-style: normal;
                        font-weight: 300;
                        font-stretch: 100%;
                    }';
        $html .= '</style>';
		$html .= '<h3> Item Masterfile Report</h3>';
		$html .= '<div style="width:100%; height:0.5px; background:#ccc; margin:0 0 10 0"></div>';
        $html .= '<p> Item Vendor Filter: '.$vcode.' '.$vendorname;
        $html .= '<br> Date Generate: '.date("F d, Y").'</p>';
        $html .= '<style>
                    .items_table, .items_table th, .items_table td {
                        border: 0.3px solid #ccc;
                        border-collapse: collapse;
                        padding: 4px;
                        font-family: "opensans", sans-serif;
                        font-size: 12px;
                    }';
        $html .= '</style>';
        $html .= '<table class="items_table" width="100%">';
        $html .= '<tr>';
        $html .= '<th style="text-align:center; width:10%"> No </th>';
        $html .= '<th style="text-align:center; width:20%"> Item Code </th>';
        $html .= '<th style="text-align:center; width:70%">Description</th>';
        $html .= '</tr>';  
		
		$no = 0;
		foreach($items as $row){
			$no++;

			$html .= '<tr style="font-size:12px">';
			$html .= '<td style="text-align:center; width:10%"> '.$no.' </th>';
			$html .= '<td style="text-align:center; width:20%"> '.$row["item_code"].'</th>';
			$html .= '<td> '.$row["ext_desc"].'</th>';
			$html .= '</tr>'; 
		}
        $html .= '</table>';

        $this->pdf->SetMargins(12, 12, 12);
		$this->pdf->SetTitle('Item Masterfile Report');
        $this->pdf->WriteHTML($html);
        return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setHeader('Content-Disposition', 'inline; filename="Items.pdf"')
			->setBody($this->pdf->Output('', 'S'));
    }

	public function vendor_po_pdf($refno)
    {               
        //QUERY DATA 
		$conditions = [ 'ref_no' => $refno ];     
		$popdf 	 	= $this->concession_model->get_po_request($refno);		
		$vcode 	 	= $popdf->vendor_code;
		$rdate 	 	= $popdf->date_requested;
		$company 	= $popdf->name;
		$department = $popdf->dept_name;
		$vendorname = $this->concession_model->getVendorName($vcode);		
		$poitems = $this->concession_model->getalltblwhere("tbl_po_requisition_details", $conditions);
		
		//DISPLAY PDF VALUE
        $html = '<style>
                    .violation {
                        font-family: "opensans", sans-serif;
                        font-style: normal;
                        font-weight: 300;
                        font-stretch: 100%;
                    }';
        $html .= '</style>';
		$html .= '<b> &nbsp;Purchase Order </b>';
		$html .= '<br> &nbsp;'.$company.' - '.$department;
		$html .= '<div style="width:100%; height:0.6px; background:#ccc; margin:8 0 10 0"></div>';
		$html .= '<table width="100%">';
		$html .= '<tr>';
		$html .= '<td width="12%">PO No. </td> <td width="48%">: '.$refno.'</td> <td align="right"> Request Date </td> <td align="right"> : '.$rdate.' </td> ';
		$html .= '</tr> <tr>';
		$html .= '<td width="12%">Supplier </td> <td width="48%">: '.$vcode.' '.$vendorname.'</td> ';
		$html .= '</tr>';
		$html .= '</table><br>';


        $html .= '<style>
                    .items_table, .items_table th, .items_table td {
                        border: 0.3px solid #ccc;
                        border-collapse: collapse;
                        padding: 4px;
                        font-family: "opensans", sans-serif;
                        font-size: 13px;
                    }';
        $html .= '</style>';
        $html .= '<table class="items_table" width="100%">';
        $html .= '<tr>';
        $html .= '<th style="text-align:center; width:5%"> # </th>';
        $html .= '<th style="text-align:center; width:15%"> Item Code </th>';
        $html .= '<th style="text-align:center; width:65%"> Description </th>';
        $html .= '<th style="text-align:center; width:15%"> PO Qty</th>';
        $html .= '</tr>';  
		
		$no = 0;
		$total_po_qty = 0;
		foreach($poitems as $row){
			$no++;

			$total_po_qty += $row['qty'];

			$html .= '<tr>';
			$html .= '<td style="text-align:center; width:5%"> '.$no.' </th>';
			$html .= '<td style="text-align:center;"> '.$row["item_code"].'</th>';
			$html .= '<td> '.$row["item_desc"].'</th>';
			$html .= '<td style="text-align:center;"> '.$row['qty'].' </th>';
			$html .= '</tr>'; 
		}
		$html .= '<tr> <td colspan="3"></td> <td style="text-align:center;">'.$total_po_qty.'</td> </tr>';
        $html .= '</table>';
		$html .= '<br><i style="font-size:12px"> Note: Please attach Purchase Order with Invoice upon delivery or indicate the PO number in the Invoice. </i>';
	

        $this->pdf->SetMargins(12, 12, 12);
		$this->pdf->SetTitle('Purchase Order');
        $this->pdf->WriteHTML($html);
        return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setHeader('Content-Disposition', 'inline; filename="PO.pdf"')
			->setBody($this->pdf->Output('', 'S'));
    }
	
	public function vendor_sales_pdf($issno)
    {        
		//QUERY DATA      
		$sales 		= $this->concession_model->get_sales_issno($issno); 
		$company 	= $sales->name;
		$department = $sales->dept_name;
		$deptcode 	= $sales->dept_code;
		$vcode 		= $sales->vendor_code;
		$sufid 		= $sales->suf_id;
		$issno 		= $sales->iss_no;
		$vendorname = $this->concession_model->getVendorName($sales->vendor_code);

		if($deptcode =="01.03.1.04" || $deptcode =="01.01.1.04" || $deptcode =="06.03" || $deptcode =="01.01.1.09" || $deptcode =="01.04.1.04" || $deptcode == "01.02.1.04"){
        	$type = "special";
			//$condition 		        = "vendor_code = '".$vcode."' and item_division like 'X%' and item_dept_code like 'Y%'";
			
        }else{
			$type = "normal";
        	//$condition 		        = "vendor_code = '".$sales->vendor_code."' and item_division not like 'X%' and item_dept_code not like 'Y%' ";
        }	
		$division = $this->concession_model->get_sales_division($type,$vcode);
		
		//DISPLAY PDF VALUE
		$html = '<b> Item Vendor Sales Report </b>';
		$html .= '<br> '.$company.' - '.$department;
		$html .= '<div style="width:100%; height:0.5px; background:#ccc; margin:0 0 10 0"></div>';
        $html .= '<p style="font-size:14px"> Item Vendor Filter: '.$vcode.' '.$vendorname;
        $html .= '<br> Date Filter: '.$sales->p_start.' .. '.$sales->p_end.' </p>';
        $html .= '<style>
                    .items_table, .items_table th, .items_table td {
                        border: 0.3px solid #ccc;
                        border-collapse: collapse;
                        padding: 4px;
                        font-family: "opensans", sans-serif;
                        font-size: 14px;
                    }';
        $html .= '</style>';
        $html .= '<table class="items_table" width="100%">';
        $html .= '<tr style="font-size:14px">';
        $html .= '<th style="text-align:center; width:5%"> No </th>';
        $html .= '<th style="text-align:center; width:10%"> Item Code </th>';
        $html .= '<th style="text-align:center; width:45px;%"> Description </th>';
        $html .= '<th style="text-align:center; width:10%"> Qty </th>';
        $html .= '<th style="text-align:center; width:10%"> NetSale <br>w/ VAT </th>';
        $html .= '<th style="text-align:center; width:10%"> Comm <br> Rate </th>';
        $html .= '<th style="text-align:center; width:10%"> Sales <br> Commission </th>';
        $html .= '<th style="text-align:center; width:10%"> Consignment <br> Payable </th>';
        $html .= '</tr>';  
				
		$ctr = 0;
		$gt_qty = 0;
		$gt_netsales = 0;
		$gt_vatinput = 0;
		$gt_salescommission      = 0;
		$gt_consignmentpayable   = 0;
		$comm_rate  = "";
		
		foreach($division as $rows){

			$itemsales  = $this->concession_model->get_item_sales_report($rows['item_division'],$sufid,$vcode);
			//$itemdivname= $this->concession_model->getDivision($rows['item_division']);

			//totals
			$total_qty  = 0;
			$total_netsales = 0;
			$total_vatinput = 0;
			$total_salescommission      = 0;
			$total_consignmentpayable   = 0;		

			foreach($itemsales as $row){

				$ctr++;
				$tbl_isr    = "tbl_item_sales_report";
			
				$itemcode   = $row['item_code'];   
				$itemdesc   = $row['item_desc'];
				$qty        = $row['qty'];
				$netsales   = $row['net_sale_with_vat'];

				$condition  = ['item_code' => $itemcode, 'vendor_code' => $vcode ];
				$comm_rate  = $this->concession_model->getOneField("comm_rate","tbl_items", $condition)->comm_rate;    
				
				$commission             = floatval($comm_rate) / 100;                         
				$salescommission        = $netsales * $commission;
				$consignmentpayable     = $netsales - $salescommission;
				
				$total_qty += $qty;
				$total_netsales += $netsales;
				$total_salescommission += $salescommission;
				$total_consignmentpayable += $consignmentpayable;

				$desc1 = $itemdesc;//substr($itemdesc, 0,25);					
				if (strpos($comm_rate, '%') === false) {
					$comm_rate .= '%';
				}
							
				$html .= '<tr style="font-size:14px">';
				$html .= '<td style="text-align:center; width:5%"> '.$ctr.' </td>';
				$html .= '<td style="text-align:center; width:10%"> '.$itemcode.'</td>';
				$html .= '<td style="width:45%"> '.$desc1.'</td>';
				$html .= '<td style="text-align:center; width:10%"> '.$qty.'</td>';
				$html .= '<td style="text-align:right; width:10%"> '.number_format($netsales,2).'</td>';
				$html .= '<td style="text-align:center; width:10%"> '.$comm_rate.' </td>';
				$html .= '<td style="text-align:right; width:10%"> '.number_format($salescommission,2).'</td>';
				$html .= '<td style="text-align:right; width:10%"> '.number_format($consignmentpayable,2).'</td>';
				$html .= '</tr>'; 
			}		
			
			$html .= '<tr> <td colspan="8"> <br> </td> <tr>';
			$html .= '<td colspan="3"><b>Total for Item Division '.$rows['item_division'].' - '.$rows['division_name'].' </b>  </td>';
			$html .= '<td style="text-align:center; width:10%"> '.$total_qty.'</td>';
			$html .= '<td style="text-align:right; width:10%"> '.number_format($total_netsales,2).'</td>';
			$html .= '<td style="text-align:center; width:10%"> '.$comm_rate.' </td>';
			$html .= '<td style="text-align:right; width:10%"> '.number_format($total_salescommission,2).'</td>';
			$html .= '<td style="text-align:right; width:10%"> '.number_format($total_consignmentpayable,2).'</td>';
			$html .= '</tr>'; 
			$html .= '<tr> <td colspan="8"> <br> </td> <tr>';

			$gt_qty += $total_qty; 
			$gt_netsales += $total_netsales;
			$gt_salescommission      += $total_salescommission;
			$gt_consignmentpayable   += $total_consignmentpayable;    
			$concession_sales = $gt_netsales - $gt_vatinput;			
		}
        $html .= '</table>';

		$html .= '<br><table>';
        $html .= '<tr>';
        $html .= '<td colspan="3"> </td>';       
        $html .= '<th style="text-align:right; width:10%">Qty</th>';
        $html .= '<th style="text-align:right; width:10%">Net Sale <br>w/ VAT</th>';
        $html .= '<th style="text-align:center; width:10%">Comm <br> Rate</th>';
        $html .= '<th style="text-align:center; width:10%">Sales <br> Commission</th>';
        $html .= '<th style="text-align:center; width:10%">Consignment <br> Payable</th>';
        $html .= '</tr>';  

		$html .= '<tr style="font-size:14px;"><td colspan="3"><b>Total for Vendor No. '.$vcode.' '.$vendorname.' </td>';
		$html .= '<td style="text-align:right; width:10%"> '.$gt_qty.' </td>';
		$html .= '<td style="text-align:right; width:10%"> '.number_format($gt_netsales,2).' </td>';
		$html .= '<td style="text-align:center; width:10%"> '.$comm_rate.' </td>';
		$html .= '<td style="text-align:center; width:10%"> '.number_format($gt_salescommission,2).'</td>';
		$html .= '<td style="text-align:center; width:10%"> '.number_format($gt_consignmentpayable,2).'</td>';
		$html .= '</tr>'; 
		$html .= '<tr> <td colspan="8"> <br> </td> <tr>';
		$html .= '</table>';

        $this->pdf->SetMargins(12, 12, 12);
		$this->pdf->SetTitle('Item Masterfile Report');
        $this->pdf->WriteHTML($html);
        return $this->response
			->setHeader('Content-Type', 'application/pdf')
			->setHeader('Content-Disposition', 'inline; filename="Items.pdf"')
			->setBody($this->pdf->Output('', 'S'));
    }

	public function view_received_po()
	{
		$refno 		= $this->request->getGet('refno');
		$conditions = ['ref_no' => $refno ];
			
		$podetails  = $this->concession_model->get_po_request($refno); //get po request details
        $vendorname = $this->concession_model->getVendorName($podetails->vendor_code); //get vendor name
        $poreceived = $this->concession_model->get_po_receive($refno);  // get po receive details
        $poitems    = $this->concession_model->getalltblwhere("tbl_po_receiving_details", $conditions); // get po items
        		
        echo "
        <center> 
            <b style='font-size:18px;font-weight:bold'> ".$podetails->name." - ".$podetails->dept_name." </b> <br> 
        </center> <br>
		<hr style='width:90%;margin:10px; margin:auto;'> <br>
        <table width='100%' cellpadding='3'>
            <tr> 
                <td width='10%' align='right'> PO No. : </td>
                <td width='40%'> <b> ".$refno." </b> </td>
                <td width='15%' align='right'> Date Requested : </td>
                <td width='20%'> ".$podetails->date_requested." </td>                
            </tr>
			<tr> 
                <td width='10%' align='right'> Vendor : </td>
                <td width='40%'> ".$vendorname." </td>
                <td width='15%' align='right'> Date Received : </td>
                <td width='20%'> ".$poreceived->date_received." </td>                
            </tr>
        </table><br>  
     
        <table class='table-bordered' width='90%' cellpadding='3' style='margin:10px; margin:auto; border: 0.3px solid #ccc;'>
            <tr align='center'>
                <th> # </th>
                <th> ITEM <br>CODE </th>
                <th> ITEM DESCRIPTION </th> 
                <th> PO <br>QUANTITY </th> 
                <th> QUANTITY <br>RECEIVED</th>
                <th> QUANTITY <br>VARIANCE </th>
            </tr>";
        
            $totpoqty = $totrecqty = $totvar = $counter = 0;
            foreach ($poitems as $row){
				                           
				$counter++;
                $po_qty = $row['po_qty'];
				$qty_var = abs($po_qty-$row['received_qty']);
                $totpoqty += $po_qty;
                $totrecqty +=  $row['received_qty'];
                $totvar += $qty_var;

                echo "
                <tr>
                    <td align='center'> $counter </td>
                    <td align='center'> $row[item_code] </td>
                    <td> &nbsp; $row[item_desc] </td>
                    <td align='center'> ".$row['po_qty']." </td>
                    <td align='center'> ".$row['received_qty']." </td>
                    <td align='center'> <b style='color:red'> $qty_var </b> </td>
                </tr>";
            }            
           
            echo " 
            <tr>
                <td colspan='3' align='right'> TOTAL </td>
                <td align='center'> $totpoqty </td>
                <td align='center'> $totrecqty </td>
                <td align='center'> <b style='color:red'> $totvar </b> </td>
            </tr>
        </table> 
        <br> <i style='display:block; text-indent:50px'> Note: Excess goods must be return to Supplier. </i>";       
    }	

}