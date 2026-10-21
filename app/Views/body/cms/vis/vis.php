	<!-- BEGIN #page-header -->
	<div id="page-header" class="section-container page-header-container bg-black">
		<!-- BEGIN page-header-cover -->
		<div class="page-header-cover">
			<img src="<?php echo base_url('assets/img/cover/cover-15.jpg'); ?>" alt="" />
		</div>
		<!-- END page-header-cover -->
		<!-- BEGIN container -->
		<div class="container">
			<h1 class="page-header"><b>Concessionaires </b> [ Vendor's Information ] </h1>
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
			<div class="card mb-5">
			  	<!-- <div class="card-header"><i class="fa fa-list-ol"></i> Vendor's Information  </div> -->
			  	<div class="card-body bg-light">

			  		<div class="account-body">
			  			<div class="row">	  		
					  		<div class="col-md-12">
					  			<p class="card-text">	
									<div class="btn-group btn-group-md">
										<a href='javascript:void(0);' id='vis_<?php echo @$vcodes[0];?>' class='btn btn-danger vcodes'> <?= @$vcodes[0];?></a> 
										<a href='javascript:void(0);' id='vis_<?php echo @$vcodes[1];?>' class='btn btn-primary vcodes'> <?= @$vcodes[1];?> </a> 					
									</div> 							
								</p>
                               
					  			<div class="table-responsive" id='div_vis'>
								<table class="table">

									<tbody>
										<tr class="highlight">
											<td colspan="3"><h4><i class="fab fa-gitlab fa-fw text-primary"></i> Company Profile  </h4> </td>
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
											<td> <?= @$vis_tbl['fax_no'];?> </td>
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
							</div>
						</div>
					</div>
			  	</div>
			</div>
		</div>
	</div>