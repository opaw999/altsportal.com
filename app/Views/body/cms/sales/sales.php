	<!-- BEGIN #page-header -->
	<div id="page-header" class="section-container page-header-container bg-black">
		<!-- BEGIN page-header-cover -->
		<div class="page-header-cover">
			<img src="<?php echo base_url('assets/img/cover/cover-15.jpg'); ?>" alt="" />
		</div>
		<!-- END page-header-cover -->
		<!-- BEGIN container -->
		<div class="container">
			<h1 class="page-header"><b>Concessionaires </b> [ Sales & Commission ] </h1>
		</div>
		<!-- END container -->
	</div>
	<!-- BEGIN #page-header -->

	<div id="product" class="section-container p-t-20">
		<div class="container">
			<ul class="breadcrumb m-b-10 f-s-12">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active">  Supplier's Data </li>
			</ul>
			<div class="card" style='width:100%'>
			  	<div class="card-header"><i class="fa fa-list-ol"></i> Sales & Commission  </div>
			  	<div class="card-body bg-light">
			  	    <p class="card-text">
						<div class="btn-group btn-group-md">
							<a href='javascript:void(0);' id='sales_<?= @$vcodes[0];?>' class='btn btn-danger vcodes'> <?= @$vcodes[0];?></a> 
							<a href='javascript:void(0);' id='sales_<?= @$vcodes[1];?>' class='btn btn-primary vcodes'> <?= @$vcodes[1];?> </a> 					
						</div>						
					</p>
			  	    <div class="table-responsive" id='div_sales'>
    			  		<table class="table table-striped table-bordered table-sm table-hover" id='salesDtable' width="100%" style='font-size:13px'>
    			  			<thead>	
							<tr> <?php
								$columns = array("#","CUTOFF","STORE/DEPT","NET SALE","SALES<br>COMMISSION","CONSIGNMENT<br>PAYABLE","STATUS","DATE POSTED","ACTION");
								foreach($columns as $key => $value):
									echo "<th class='text-center'> $value </th>";
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
										<td class='text-center'> <?= $i;?></td>
										<td class='text-center'> <?= $row['p_start'];?> .. <?= $row['p_end'];?> </td>
										<td> <?= $row['shortcut'];?> .. <?= $row['dept_name'];?> </td>
										<td> <?php echo number_format($row['net_sale'],2);?> </td>
										<td> <?php echo number_format($row['sales_commission'],2);?> </td>
										<td> <?php echo number_format($row['consignment_payable'],2);?> </td>
										<td class='text-center'> <?= $row['status']?> </td>
										<td class='text-center'> <?= $row['date_posted'];?> </td>	
										<td class='text-center'> <a href='<?php echo base_url('vendor_sales_pdf/'.$row['iss_no']); ?>') target='_blank' title='Export in PDF'> PDF </a> </td>
									</tr>		  				
    			  				<?php endforeach; 
							} ?>
    			  			</tbody>
    			  		</table>
			  		</div>
			  	</div>
			</div>
		</div>
	</div>

  	<div id="show-sales" class="modal fade">
        <div class="modal-dialog modal-lg" style="width: 80%; max-width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Sales Report Details </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body show-sales" id='modal_sales_report_details'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
