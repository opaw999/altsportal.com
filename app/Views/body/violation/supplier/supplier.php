<div id="page-header" class="section-container page-header-container bg-black">
	<div class="page-header-cover">
		<img src="<?= base_url('../assets/main/img/cover/cover-15.jpg') ?>" alt="" />
	</div>
	<div class="container">
		<h1 class="page-header"><b>Employee's Violation</b> (Per Supplier)</h1>
	</div>
</div>

<div id="product" class="section-container pt-20px">
	<div class="container">
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

<!-- The Modal -->
<div class="modal fade" id="view_company">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Employee's Violation <b>( <span id="emp_cutoff"></span> )</b></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body view_company"></div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
			</div>
		</div>
	</div>
</div>

<!-- The Modal -->
<div class="modal fade" id="view_violation">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Employee's Violation <b>( <span id="emp_cutoff"></span> )</b></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
			</div>
			<form action="<?= base_url('print_violation') ?>" method="get" target="_blank">
				<div class="modal-body view_violation"></div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Print to PDF</button>
					<a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
				</div>
			</form>
		</div>
	</div>
</div>