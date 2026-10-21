	<!-- BEGIN #page-header -->
	<div id="page-header" class="section-container page-header-container bg-black">
		<!-- BEGIN page-header-cover -->
		<div class="page-header-cover">
			<img src="<?php echo base_url('assets/img/cover/cover-15.jpg'); ?>" alt="" />
		</div>
		<!-- END page-header-cover -->
		<!-- BEGIN container -->
		<div class="container">
			<h1 class="page-header"><b>Concessionaires </b> [ Check Monitoring ]  </h1>
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
			<div class="card">
			  	<div class="card-header"><i class="fa fa-list-ol"></i> Check Monitoring  </div>
			  	<div class="card-body bg-light">
			  	
			  		<p class="card-text">
						<div class="btn-group btn-group-md">
							<a href='javascript:void(0);' id='check_<?= @$vcodes[0];?>' class='btn btn-danger vcodes'> <?= @$vcodes[0];?></a> 
							<a href='javascript:void(0);' id='check_<?= @$vcodes[1];?>' class='btn btn-primary vcodes'> <?= @$vcodes[1];?> </a> 					
						</div>
					</p>
					<div class="table-responsive" id='div_check'>
			  		<table id='checkDTable' class="table table-striped table-bordered table-sm table-hover" style='font-size:13px' width="100%">
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
						if(@$check)
						{
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
						}?>			  				
			  			</tbody>
			  		</table>
			  	</div>
			</div>
		</div>
	</div>