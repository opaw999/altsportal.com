	<div id="page-header" class="section-container page-header-container bg-black">
		<div class="page-header-cover">
			<img src="<?php echo base_url('assets/img/cover/cover-15.jpg'); ?>" alt="" />
		</div>
		<div class="container">
			<h1 class="page-header"><b>Concessionaires </b> [ Deductions ] </h1>
		</div>
	</div>

	<div id="product" class="section-container p-t-20">
		<div class="container">
			<ul class="breadcrumb m-b-10 f-s-12">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active"> Concessionaires </li>
			</ul>
			<div class="card">
			  	<div class="card-header"><i class="fa fa-list-ol"></i> Deductions </div>
			  	<div class="card-body bg-light">
					<!-- 
			  		<div class="row">
			  			<div class="col-md-3">
					  		<p class="card-text">
					  			<div class="btn-group btn-group-md">
					  				
						  			<a href='javascript:void(0);' class='btn btn-danger cutoff'>
						  				Bimonthly
						  			</a>
						  			<a href='javascript:void(0);' class='btn btn-primary cutoff'>
						  				Monthly
						  			</a>
						  			&nbsp;&nbsp;

						  			<select class="form-control">
						  				<option> Select Stores </option>
						  				<option selected=""> Island City Mall </option>
						  				<option> Alturas Mall </option>
						  				<option> Alta Citta </option>
						  			</select>

					  			</div>
					  		</p>
					  	</div>
			  		</div> -->
			  			  		
			  		<!--<table class="table table-striped table-bordered table-hover" width="100%">-->
			  		
			  		<p class="card-text">
						<div class="btn-group btn-group-md">
							<a href='javascript:void(0);' id='ded_<?= @$vcodes[0];?>' class='btn btn-danger vcodes'> <?= @$vcodes[0];?></a> 
							<a href='javascript:void(0);' id='ded_<?= @$vcodes[1];?>' class='btn btn-primary vcodes'> <?= @$vcodes[1];?> </a> 					
						</div>
					</p>
					<div class="table-responsive" id='div_ded'>
    			  		<table id="deDTable" width="100%" class="table table-bordered table-sm dt-responsive nowrap" style='font-size:13px'>
    			  			<thead>	
    			  				<tr>
    			  					<th> Doc No. </th>
    		  						<th> Vendor Code </th>
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
			  		</div>
			  	</div>
			</div>
		</div>
	</div>

	<div id="show-deductions" class="modal fade">
        <div class="modal-dialog modal-lg" style="width: 80%; max-width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Deductions / Penalty </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body show-deductions">
                   	<table class="table table-striped table-bordered table-hover" width="100%">
                   		<thead> </thead>
	                   		<tr>
	                   			<tr> 
	                   				<th> NO </th>
	                   				<th> DEDUCTION TYPE </th>
	                   				<th> AMOUNT </th>
	                   				<th> ACTION </th>
	                   			</tr>
	                   		</tr>
                   		</thead>
                   		<tbody>
	                   		<tr>
	                   			<tr> 
	                   				<td> 1 </td>
	                   				<td> Barcode </td>
	                   				<td> P 1200.00 </td>
	                   				<td> </td>
	                   			</tr>
	                   			
	                   		</tr>
                   		</tbody>
                   	</table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

  	<div id="show-deduction" class="modal fade">
        <div class="modal-dialog modal-lg" style="width: 68%; max-width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Penalty Details </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body show-deduction-details">
                   	
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->