	<div id="page-header" class="section-container page-header-container bg-black">	
		<div class="page-header-cover">
			<img src="<?php echo base_url('assets/img/cover/cover-15.jpg'); ?>" alt="" />
		</div>
		<div class="container">
			<h1 class="page-header"><b>Concessionaires </b> [ Purchase Order ] </h1>
		</div>
	</div>

	<div id="product" class="section-container p-t-20">
		<div class="container">
			<ul class="breadcrumb m-b-10 f-s-12">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active"> Concessionaires </li>
			</ul>
			<div class="card">
			  	<div class="card-header"><i class="fa fa-list-ol"></i> Purchase Order (P.O.) </div>
			  	<div class="card-body bg-light">			  		
			  		<p class="card-text">
						<div class="btn-group btn-group-md">
							<a href='javascript:void(0);' id='ded_<?= @$vcodes[0];?>' class='btn btn-danger vcodes'> <?= @$vcodes[0];?></a> 
							<a href='javascript:void(0);' id='ded_<?= @$vcodes[1];?>' class='btn btn-primary vcodes'> <?= @$vcodes[1];?> </a> 					
						</div>
					</p>			  		
					<div class="table-responsive" id='div_po'>
    			  		<table id="poDTable" width="100%" class="table table-bordered table-sm dt-responsive nowrap" style='font-size:13px'>
    			  			<thead>	
    			  				<tr> <?php
									$columns    = array("#","PO Reference #","Vendor Code","Store","Department","Date Requested","Status","Print","Received","View");
    			  				    foreach($columns as $key => $value):
    			  				        echo "<th> $value </th>";
    			  				    endforeach; ?>
    			  				</tr>
    			  			</thead>
    			  			<tbody>	
    							<?php 
    							if(!empty($po)){
								$counter = 0;
    							foreach($po as $row):
									    	
									$counter++;
                                    $refno = trim($row['ref_no']);
    								if($row['received_status'] !=""){ 
										$view="<a href='#show-receiveditems' data-toggle='modal' onclick=show_received('$refno')> View </a>";}else{ $view=""; }	
    								
									echo "	
    								<tr>	
    									<td class='text-center'> ".$counter." </td>
    									<td> ".$row['ref_no']." </td>
    									<td class='text-center'> ".$row['vendor_code']." </td>
    									<td> ".$row['name']." </td>
    									<td> ".$row['dept_name']." </td>
    									<td class='text-center'> ".$row['date_requested']." </td>
    									<td> ".$row['status']." </td>";?>
    									<td> <a href='<?php echo base_url('vendor_po_pdf/'.$row['ref_no']); ?>') target='_blank'> PDF </a> </td> <?php echo "
    									<td> ". $row['received_status']." </td>
    									<td> $view </td>
    								</tr>";
    							endforeach;
    							}?>
    			  			</tbody>
    			  		</table>
			  		</div>
			  	</div>
			</div>
		</div>
	</div>	

	<div class="modal fade" id="show-receiveditems">
		<div class="modal-dialog modal-lg" style="width: 70%; max-width: 100%;">
			<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"> Stock Receiving </h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body show-cas" id='receiveditems'>				
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
			</div>
			</div>
		</div>
	</div>