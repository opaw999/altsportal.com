	<!-- BEGIN #page-header -->
	<div id="page-header" class="section-container page-header-container bg-black">
		<!-- BEGIN page-header-cover -->
		<div class="page-header-cover">
			<img src="<?php echo base_url('assets/img/cover/cover-15.jpg'); ?>" alt="" />
		</div>
		<!-- END page-header-cover -->
		<!-- BEGIN container -->
		<div class="container">
			<h1 class="page-header"><b>Concessionaires </b> [ Deductions ] </h1>
		</div>
		<!-- END container -->
	</div>
	<!-- BEGIN #page-header -->

	<div id="product" class="section-container p-t-20">
		<div class="container">
			<ul class="breadcrumb m-b-10 f-s-12">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active"> Supplier's Data </li>
			</ul>
			<div class="card mb-5">
			  	<div class="card-header"><i class="fa fa-list-ol"></i> Items </div>
			  	<div class="card-body bg-light">
					
			  	    <p class="card-text">
						<div class="btn-group btn-group-md">
							<a href='javascript:void(0);' id='items_<?= @$vcodes[0];?>' class='btn btn-danger vcodes'> <?= @$vcodes[0];?></a> 
							<a href='javascript:void(0);' id='items_<?= @$vcodes[1];?>' class='btn btn-primary vcodes'> <?= @$vcodes[1];?> </a> 					
						</div>
						<?php
						if(@$vcodes[0]){
						    ?><a href='<?php echo base_url('items_pdf/'.@$vcodes[0]); ?>') class='btn btn-success' target='_blank'> Export Items of <?= @$vcodes[0];?> </a> <?php
						}
						if(@$vcodes[1]){
						    ?><a href='<?php echo base_url('items_pdf/'.@$vcodes[1]); ?>') class='btn btn-success' target='_blank'> Export Items of <?= @$vcodes[1];?> </a> <?php
						} ?>
						
					</p>
					
		  			<div class="table-responsive" id='div_items'>
		  			    
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
						if(@$items)
						{
							foreach($items as $row)
							{														
								echo "
								<tr>
									<td> $row[item_code] </td>
									<td> $row[ext_desc] </td>
									<td> $row[comm_rate]% </td>
								</tr>";
							} 
						}?>
						</tbody>
					</table>		  			  		
			  		
			  	</div>
			</div>
		</div>
	</div>  	