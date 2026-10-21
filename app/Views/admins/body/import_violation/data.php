<div id="content" class="app-content">
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home </a></li>
		<li class="breadcrumb-item"><a href="javascript:;">Import Violation</a></li>
		<li class="breadcrumb-item active">Database</li>
	</ol>
	<h1 class="page-header">Import Violation <small>(Database)</small></h1>
	<div class="panel panel-inverse">
		<div class="panel-heading" style="display: flex; align-items: center; justify-content: space-between;">
			<h4 class="panel-title">File Upload</h4>
		</div>
		<div class="panel-body">
			<form id="data_import_violation" enctype="multipart/form-data" autocomplete="off" data-parsley-validate="true">
				<div class="row">
					<div class="col-md-6 mb-2">
						<div class="note alert-warning mb-2">
							<div class="note-icon"><i class="fa fa-lightbulb"></i></div>
							<div class="note-content">
								<h4><b>Note:</b></h4>
								<p>Only zip/rar file are allowed.</p>
							</div>
						</div>
					</div>
					<div class="col-md-6"></div>
					<div class="col-md-4">
						<input type="file" class="form-control" name="imported_data" accept=".zip,.rar" required>
					</div>
					<div class="col-md-2">
						<button type="submit" class="btn btn-primary w-100">Import Violation Data</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="content" class="content">
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item active"><a href="javascript:;">Import Violation</a></li>
	</ol>
	<h1 class="page-header">Import Violation<small>database</small></h1>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">File Upload</h4>
				</div>
				<div class="panel-body">
					<div class="note note-yellow m-b-15">
						<div class="note-icon f-s-20">
							<i class="fa fa-lightbulb fa-2x"></i>
						</div>
						<div class="note-content">
							<h4 class="m-t-5 m-b-5 p-b-2">Demo Notes</h4>
							<ul class="m-b-5 p-l-25">
								<li>Only zip/rar file are allowed.</li>
							</ul>
						</div>
					</div>
					<div class="row fileupload-buttonbar">
						<form id="data_import_violation" class="form-inline" action="" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-2">File:</label>
								<input type="file" name="imported_data" class="btn btn-white col-md-10" required="">
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary ml-2 import_btn">Import Violation Data</button>
								<button class="btn btn-primary ml-2 loading_btn" disabled="" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Importing Violation Data...</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>