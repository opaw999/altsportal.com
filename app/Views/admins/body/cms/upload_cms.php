<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;"><?= ucwords($menu) ?> </a></li>
		<li class="breadcrumb-item active"><?= ucwords($submenu) ?> </li>
	</ol>
	<h1 class="page-header"> CMS <small>VIS</small></h1>		
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4> Upload Tables from 48 Database.</h4>
		</div>
		<div class="panel-body">
			<form id="uploadTable" enctype="multipart/form-data" autocomplete="off" data-parsley-validate="true">
				<div class="row">
					<div class="col-md-12 mb-2">
						<div class="note alert-warning mb-2">
							<div class="note-icon"><i class="fa fa-lightbulb"></i></div>
							<div class="note-content">
								<h4><b>Note:</b></h4>
								<p>Tables such as Vendors, VIS, CAS and Items will be overridden if the same vendor code already exists.</p>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label> Choose Table </label>
							<select class="form-control" name="table_name" id="table_name">
								<option>  </option>
								<option value="tbl_vendor"> Vendor </option>
								<option value="tbl_vis"> VIS </option>
								<option value="tbl_cas"> CAS </option>
								<option value="tbl_items"> Items </option>
								<option value="tbl_deductions"> Deduction </option>
								<option value="tbl_sales"> Sales </option>
								<option value="tbl_check_monitoring"> Checks Monitoring </option>
								<option value="tbl_check_voucher"> Check Vouchers </option>
								<option value="tbl_check_details"> Checks Details </option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label>Import CSV File</label>	
						<input type="file" class="form-control" name="csvfile" id="csvfile" accept=".csv,.txt" required>
					</div>
					<div class="col-md-2">
						<br>
						<button type="submit" class="btn btn-primary w-100">Import Table</button>
					</div>
				</div>				
			</form>
			<div id="uploadResult" class="mt-2"></div>
		</div>
	</div>			
</div>	