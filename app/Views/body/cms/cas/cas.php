	<!-- BEGIN #page-header -->
	<div id="page-header" class="section-container page-header-container bg-black">
		<!-- BEGIN page-header-cover -->
		<div class="page-header-cover">
			<img src="<?php echo base_url('assets/img/cover/cover-15.jpg'); ?>" alt="" />
		</div>
		<!-- END page-header-cover -->
		<!-- BEGIN container -->
		<div class="container">
			<h1 class="page-header"><b>Concessionaires </b> [ Concessionaires Agreement ] </h1>
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
			  	<div class="card-header"><i class="fa fa-list-ol"></i> Concessionaires Agreement List  </div>
			  	<div class="card-body bg-light">
			  	    
			  	    <p class="card-text">
						<div class="btn-group btn-group-md">
							<a href='javascript:void(0);' id='cas_<?= @$vcodes[0];?>' class='btn btn-danger vcodes'> <?= @$vcodes[0];?></a> 
							<a href='javascript:void(0);' id='cas_<?= @$vcodes[1];?>' class='btn btn-primary vcodes'> <?= @$vcodes[1];?> </a> 					
						</div>
					</p>
                   
		  			<div class="table-responsive" id='div_cas'>
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
							<?php 
							if(@$cas_tbl){
								foreach($cas_tbl as $row): ?>
								<tr>
									<td> <?= $row['cas_id'];?> </td>
									<td> <?= $row['vendor_code'];?> </td>
									<td> <?= $row['dated'];?> </td>
									<td> <?= $row['brand'];?> </td>
									<td> <?= $row['merchandise_category'];?> </td>
									<td> <?= $row['commission_percentage'];?> </td>
									<td> <?= $row['period_start'];?> </td>
									<td> <?= $row['period_end'];?> </td>
									<td> 
										<a href='#show-agreement' data-bs-toggle="modal" title="View Details" 
										onclick="show_cas('<?= $row['cas_id'];?>')"> <i class="fa fa-fw fa-list txt-color-blue hidden-md"></i> </a>  
									</td>
								</tr><?php 
								endforeach;
							}?>	
			  			</tbody>
			  		</table>
			  		</div>
			  	</div>
			</div>
		</div>
	</div>

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

    <div id="show-cas" class="modal fade">
      <div class="modal-dialog" style="width: 45%; max-width: 100%;">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title"> Uploaded Scanned Concessionaire Agreement </h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body show-cas">
                 	<!-- <img src='<?= base_url();?>/assets/img/CAS.jpg' width="100%" height="100%"> -->
					No File Uploaded!!!
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
    </div>

    <div id="show-items" class="modal fade">
        <div class="modal-dialog modal-lg" style="width: 60%; max-width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> ITEM LIST </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body show-cas" id='items_masterfile'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="show-items-scan" class="modal fade">
      <div class="modal-dialog" style="width: 45%; max-width: 100%;">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title"> Uploaded Item List </h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body show-cas">
                 <embed src='<?= base_url();?>/assets/img/items.pdf' width="100%" height="500px"> </embed>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
    </div> 