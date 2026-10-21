<!-- BEGIN #page-header -->
<div id="page-header" class="section-container page-header-container bg-black">
	<!-- BEGIN page-header-cover -->
	<div class="page-header-cover">
		<img src="<?= base_url('../assets/main/img/cover/cover-15.jpg') ?>" alt="" />
	</div>
	<!-- END page-header-cover -->
	<!-- BEGIN container -->
	<div class="container">
		<h1 class="page-header"><b>Daily Time Record</b></h1>
	</div>
	<!-- END container -->
</div>
<!-- BEGIN #page-header -->

<!-- BEGIN #product -->
<div id="product" class="section-container pt-20px">
	<div class="container">
		<!-- <ul class="breadcrumb m-b-10 f-s-12">
			<li class="breadcrumb-item"><a href="#">Home</a></li>
			<li class="breadcrumb-item active">Daily Time Record</li>
		</ul> -->
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-info" role="alert">
					<h4 class="alert-heading">Important Notice!</h4>
					<p>
						For all DTR concerns, kindly contact Corporate HRD c/o Maam Coney Yelen Cornito and
						Maam Beberly Membreve Añana through their company mobile #09190796028 and #09088152975 or
						email them at corporatehrd@alturasbohol.com to facilitate your concerns. Thank you.
					</p>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header"><i class="fa- fa-list-ol"></i> List of Cut-offs </div>
			<div class="card-body bg-light">
				<div class="btn-group btn-group-sm mb-1">
					<?php
					$i = 1;
					foreach ($cutoffs as $row) {

						if ($row['endFC'] == '') {
							$endFC = 'Last';
						} else {
							$endFC = $row['endFC'];
						}

						$statCut = $row['statCut'];
						$sched = $row['startFC'] . '-' . $endFC . ' / ' . $row['startSC'] . '-' . $row['endSC'];
						if ($i == 1) {
							$class = 'btn btn-danger cutoff cutoff active';
						} else {
							$class = 'btn btn-primary cutoff cutoff';
						}
						echo '<a href="javascript:;" id="' . $statCut . '" class="' . $class . '">' . $sched . '</a>';
						$i++;
					}

					?>
				</div>
				<table id="dt_dtr" class="table table-striped table-bordered table-hover" width="100%">
					<thead>
						<tr>
							<th>Date From</th>
							<th>Date To</th>
							<th>Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- END #product -->

<!-- The Modal -->
<div class="modal fade" id="view_employee">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<form id="data_print_dtr" action="<?= base_url('print_dtr_all') ?>" method="get" target="_blank">
				<div class="modal-header">
					<h4 class="modal-title">Employee's DTR <b>( <span id="emp_cutoff"></span> )</b></h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body view_employee" style="font-size:smaller"></div>
				<div class="modal-footer">
					<input type="hidden" name="server">
					<button type="submit" class="btn btn-primary" id="print_dtr" disabled>Print to PDF</button>
					<a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- The Modal -->
<div class="modal fade" id="view_dtr">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Employee's DTR <b>( <span id="emp_cutoff"></span> )</b></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<form id="data_print_dtr" action="<?= base_url('print_dtr') ?>" method="get" target="_blank">
				<div class="modal-body view_dtr" style="font-size:smaller"></div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Print to PDF</button>
					<a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="requestDetails" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Request Details...</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body requestDetails"></div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
			</div>
		</div>
	</div>
</div>

<div id="show-entry" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">View Time Entry</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body show-entry"></div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
			</div>
		</div>
	</div>
</div>